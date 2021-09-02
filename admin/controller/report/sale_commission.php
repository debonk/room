<?php
class ControllerReportSaleCommission extends Controller
{
	private $filter_items = array(
		'date_start',
		'date_end',
		'username'
	);

	private function urlFilter()
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
			}
		}

		return $url_filter;
	}

	public function index()
	{
		$this->load->language('report/sale_commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/sale');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_summary',
			'text_no_results',
			'text_total',
			'column_username',
			'column_order_count',
			'column_total',
			'column_commission',
			'column_commission1',
			'column_commission2',
			'entry_username',
			'entry_date_start',
			'entry_date_end',
			'tab_summary',
			'tab_commission1',
			'tab_commission2',
			'button_filter',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['date_start'])) {
			$filter['date_start'] = date('Y-m-d', strtotime(date('Y') . '-01-01'));
		}

		if (isset($this->request->get['c_sort'])) {
			$sort = $this->request->get['c_sort'];
		} else {
			$sort = 'username';
		}

		if (isset($this->request->get['c_order'])) {
			$order = $this->request->get['c_order'];
		} else {
			$order = 'ASC';
		}

		$url = $this->urlFilter();

		if (isset($this->request->get['c_sort'])) {
			$url .= '&c_sort=' . $this->request->get['c_sort'];
		}

		if (isset($this->request->get['c_order'])) {
			$url .= '&c_order=' . $this->request->get['c_order'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/sale_commission', 'token=' . $this->session->data['token'], true)
		);

		$data['commissions'] = [];

		$usernames = [];
		$commission1_count = [];
		$commission1_total = [];
		$commission2_count = [];
		$commission2_total = [];

		# Commission1 - Commission by order
		if (in_array('commission1', $this->config->get('config_sales_commission'))) {
			$filter['order_status'] = $this->config->get('config_sales_commission1_status');

			$filter['date_added_start'] = $filter['date_start'];
			$filter['date_added_end'] = $filter['date_end'];
			$filter['date_event_start'] = '';
			$filter['date_event_end'] = '';

			$filter_data = array(
				'filter'	=> $filter,
			);

			$commission1_amount = $this->config->get('config_sales_commission1_amount');

			$results = $this->model_report_sale->getOrdersCommission($filter_data);

			foreach ($results as $result) {
				if (!isset($usernames[$result['user_id']])) {
					$usernames[$result['user_id']] = $result['username'];
				}

				if (isset($commission1_count[$result['user_id']])) {
					$commission1_count[$result['user_id']]++;
				} else {
					$commission1_count[$result['user_id']] = 1;
				}

				if (isset($commission1_total[$result['user_id']])) {
					$commission1_total[$result['user_id']] += $commission1_amount;
				} else {
					$commission1_total[$result['user_id']] = $commission1_amount;
				}
			}
		}

		# Commission2 - Commission by product
		if (in_array('commission2', $this->config->get('config_sales_commission'))) {
			$filter['order_status'] = $this->config->get('config_sales_commission2_status');

			$filter['date_added_start'] = '';
			$filter['date_added_end'] = '';
			$filter['date_event_start'] = $filter['date_start'];
			$filter['date_event_end'] = $filter['date_end'];

			$filter_data = array(
				'filter'	=> $filter,
			);


			$results = $this->model_report_sale->getOrdersCommission($filter_data);

			foreach ($results as $result) {
				if (!isset($usernames[$result['user_id']])) {
					$usernames[$result['user_id']] = $result['username'];
				}

				if (isset($commission2_count[$result['user_id']])) {
					$commission2_count[$result['user_id']]++;
				} else {
					$commission2_count[$result['user_id']] = 1;
				}

				$product_commission = $this->model_report_sale->getOrderProductsCommission($result['order_id']);

				if (isset($commission2_total[$result['user_id']])) {
					$commission2_total[$result['user_id']] += $product_commission;
				} else {
					$commission2_total[$result['user_id']] = $product_commission;
				}
			}
		}
		
		$grandtotal = 0;

		foreach ($usernames as $user_id => $username) {
			$commission_total = isset($commission1_total[$user_id]) ? $commission1_total[$user_id] : 0;
			$commission_total += isset($commission2_total[$user_id]) ? $commission2_total[$user_id] : 0;

			$grandtotal += $commission_total;

			$data['commissions'][] = [
				'username'        	=> $username,
				'commission1_count' => isset($commission1_count[$user_id]) ? $commission1_count[$user_id] : 0,
				'commission1_total'	=> isset($commission1_total[$user_id]) ? $this->currency->format($commission1_total[$user_id], $this->config->get('config_currency')) : 0,
				'commission2_count' => isset($commission2_count[$user_id]) ? $commission2_count[$user_id] : 0,
				'commission2_total'	=> isset($commission2_total[$user_id]) ? $this->currency->format($commission2_total[$user_id], $this->config->get('config_currency')) : 0,
				'commission_total'	=> $this->currency->format($commission_total, $this->config->get('config_currency'))
			];
		}

		$data['total'] = $this->currency->format($grandtotal, $this->config->get('config_currency'));

		if (isset($order) && ($order == 'DESC')) {
			$result_order = SORT_DESC;
		} else {
			$result_order = SORT_ASC;
		}

		array_multisort(array_column($data['commissions'], $sort), $result_order, $data['commissions']);

		$result_count = count($data['commissions']);

		$data['url'] = $url;

		$url = $this->urlFilter();

		if ($order == 'ASC') {
			$url .= '&c_order=DESC';
		} else {
			$url .= '&c_order=ASC';
		}

		// if (isset($this->request->get['page'])) {
		// 	$url .= '&page=' . $this->request->get['page'];
		// }

		$data['sort_username'] = $this->url->link('report/sale_commission', 'token=' . $this->session->data['token'] . '&c_sort=username' . $url, true);

		$data['results'] = sprintf($this->language->get('text_record'), $result_count);

		$data['token'] = $this->session->data['token'];
		$data['filter_items'] = json_encode($this->filter_items);
		$data['commission1_total'] = $this->currency->format($commission1_total, $this->config->get('config_currency'));

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_commission', $data));
	}

	public function commissionOrder()
	{
		$this->load->language('report/sale_commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/sale');

		$language_items = array(
			'heading_title',
			'text_commission1',
			'text_no_results',
			'text_title',
			'text_invoice',
			'text_total',
			'column_username',
			'column_date_added',
			'column_event_date',
			'column_order_detail',
			'column_customer',
			'column_order_total',
			'column_order_status',
			'column_commission'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['date_start'])) {
			$filter['date_start'] = date('Y-m-d', strtotime(date('Y') . '-01-01'));
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['commissions1'] = [];
		$limit = $this->config->get('config_limit_admin');

		# Commission1
		if (in_array('commission1', $this->config->get('config_sales_commission'))) {
			$filter['order_status'] = $this->config->get('config_sales_commission1_status');

			$filter['date_added_start'] = $filter['date_start'];
			$filter['date_added_end'] = $filter['date_end'];
			$filter['date_event_start'] = '';
			$filter['date_event_end'] = '';

			$filter_data = array(
				'filter'	=> $filter,
				'sort'      => $sort,
				'order'     => $order,
				'start'     => ($page - 1) * $limit,
				'limit'     => $limit
			);
			
			$result_count = $this->model_report_sale->getOrdersCommissionCount($filter_data);
			$commission1_amount = $this->config->get('config_sales_commission1_amount');
			$commission1_total = 0;

			$results = $this->model_report_sale->getOrdersCommission($filter_data);

			foreach ($results as $result) {
				$commission1_total += $commission1_amount;

				$data['commissions1'][] = array(
					'username'        => $result['username'],
					'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'event_date'      => date($this->language->get('date_format_short'), strtotime($result['event_date'])),
					'title'        	  => $result['title'],
					'primary_product' => $result['primary_product'],
					'invoice_no'      => '#' . $result['order_id'] . ': ' . ($result['invoice_no'] ? $result['invoice_prefix'] . str_pad($result['invoice_no'], 4, 0, STR_PAD_LEFT) : ''),
					'customer'        => $result['customer'],
					'order_status'    => $result['order_status'],
					'total'           => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'href'            => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
					'commission1'     => $this->currency->format($commission1_amount, $this->config->get('config_currency'))
				);
			}
		}

		$url = $this->urlFilter();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_added'] = $this->url->link('report/sale_commission/commissionOrder', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_event_date'] = $this->url->link('report/sale_commission/commissionOrder', 'token=' . $this->session->data['token'] . '&sort=o.event_date' . $url, true);
		$data['sort_order_status'] = $this->url->link('report/sale_commission/commissionOrder', 'token=' . $this->session->data['token'] . '&sort=order_status' . $url, true);
		$data['sort_username'] = $this->url->link('report/sale_commission/commissionOrder', 'token=' . $this->session->data['token'] . '&sort=username' . $url, true);

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $result_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/sale_commission/commissionOrder', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_count - $limit)) ? $result_count : ((($page - 1) * $limit) + $limit), $result_count, ceil($result_count / $limit));

		$data['commission1_total'] = $this->currency->format($commission1_total, $this->config->get('config_currency'));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/sale_commission_order', $data));
	}

	public function commissionProduct()
	{
		$this->load->language('report/sale_commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/sale');

		$language_items = array(
			'heading_title',
			'text_commission2',
			'text_no_results',
			'text_title',
			'text_invoice',
			'text_total',
			'column_username',
			'column_date_added',
			'column_event_date',
			'column_order_detail',
			'column_customer',
			'column_order_total',
			'column_order_status',
			'column_commission'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['date_start'])) {
			$filter['date_start'] = date('Y-m-d', strtotime(date('Y') . '-01-01'));
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['commissions2'] = [];
		$limit = $this->config->get('config_limit_admin');

		# commission2
		if (in_array('commission2', $this->config->get('config_sales_commission'))) {
			$filter['order_status'] = $this->config->get('config_sales_commission2_status');

			$filter['date_added_start'] = '';
			$filter['date_added_end'] = '';
			$filter['date_event_start'] = $filter['date_start'];
			$filter['date_event_end'] = $filter['date_end'];

			$filter_data = array(
				'filter'	=> $filter,
				'sort'      => $sort,
				'order'     => $order,
				'start'     => ($page - 1) * $limit,
				'limit'     => $limit
			);
				
			$result_count = $this->model_report_sale->getOrdersCommissionCount($filter_data);

			$commission2_total = 0;

			$results = $this->model_report_sale->getOrdersCommission($filter_data);
			
			foreach ($results as $result) {
				$product_commission = $this->model_report_sale->getOrderProductsCommission($result['order_id']);

				$commission2_total += $product_commission;
				
				$data['commissions2'][] = array(
					'username'        => $result['username'],
					'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'event_date'      => date($this->language->get('date_format_short'), strtotime($result['event_date'])),
					'title'        	  => $result['title'],
					'primary_product' => $result['primary_product'],
					'invoice_no'      => '#' . $result['order_id'] . ': ' . ($result['invoice_no'] ? $result['invoice_prefix'] . str_pad($result['invoice_no'], 4, 0, STR_PAD_LEFT) : ''),
					'customer'        => $result['customer'],
					'order_status'    => $result['order_status'],
					'total'           => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'href'            => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
					'commission2'     => $this->currency->format($product_commission, $this->config->get('config_currency'))
				);
			}
		}

		$url = $this->urlFilter();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_added'] = $this->url->link('report/sale_commission/commissionProduct', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_event_date'] = $this->url->link('report/sale_commission/commissionProduct', 'token=' . $this->session->data['token'] . '&sort=o.event_date' . $url, true);
		$data['sort_order_status'] = $this->url->link('report/sale_commission/commissionProduct', 'token=' . $this->session->data['token'] . '&sort=order_status' . $url, true);
		$data['sort_username'] = $this->url->link('report/sale_commission/commissionProduct', 'token=' . $this->session->data['token'] . '&sort=username' . $url, true);

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $result_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/sale_commission/commissionProduct', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_count - $limit)) ? $result_count : ((($page - 1) * $limit) + $limit), $result_count, ceil($result_count / $limit));

		$data['commission2_total'] = $this->currency->format($commission2_total, $this->config->get('config_currency'));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/sale_commission_product', $data));
	}
}
