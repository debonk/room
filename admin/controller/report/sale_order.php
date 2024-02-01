<?php
class ControllerReportSaleOrder extends Controller
{
	public function index()
	{
		$this->load->language('report/sale_order');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'month';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		// $url = '';

		// if (isset($this->request->get['filter_date_start'])) {
		// 	$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		// }

		// if (isset($this->request->get['filter_date_end'])) {
		// 	$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		// }

		// if (isset($this->request->get['filter_group'])) {
		// 	$url .= '&filter_group=' . $this->request->get['filter_group'];
		// }

		// if (isset($this->request->get['filter_order_status_id'])) {
		// 	$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		// }

		// if (isset($this->request->get['page'])) {
		// 	$url .= '&page=' . $this->request->get['page'];
		// }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true)
		);

		$this->load->model('report/sale');

		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_sale->getTotalOrders($filter_data);

		$results = $this->model_report_sale->getOrders($filter_data);

		foreach ($results as $result) {
			switch ($filter_group) {
				case 'day';
					$key = date($this->language->get('date_format_long'), strtotime($result['event_date']));
					break;

				case 'week':
					$key = $result['year'] . ' - Week: ' . $result['week'];
					break;

				case 'month':
					$key =  $result['month'] . ' ' . $result['year'];
					break;

				case 'year':
					$key = $result['year'];
					break;

				default:
			}

			$url = '&filter_model=' . $result['venue_code'] . '&filter_date_start=' . $result['date_start'] . '&filter_date_end=' . $result['date_end'];

			if (!empty($filter_order_status_id)) {
				$url .= '&filter_order_status=' . $filter_order_status_id;
			}

			$data['orders'][$key][] = array(
				'venue_code'	=> $result['venue_code'],
				'date_start'	=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'		=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'orders'  		=> $result['orders'],
				'tax'     		=> $this->currency->format($result['tax'], $this->config->get('config_currency')),
				'tax_value'		=> $result['tax'],
				'total'   		=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'total_value'	=> $result['total'],
				'href'			=> $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . $url, true)
			);
		}

		$data['subtotal'] = [];

		foreach (array_keys($data['orders']) as $key) {
			$data['subtotal'][$key] = [
				'orders_count'	=> array_sum(array_column($data['orders'][$key], 'orders')),
				'taxes_total'	=> $this->currency->format(array_sum(array_column($data['orders'][$key], 'tax_value')), $this->config->get('config_currency')),
				'totals_total'	=> $this->currency->format(array_sum(array_column($data['orders'][$key], 'total_value')), $this->config->get('config_currency'))
			];
		}

		$language_items = [
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all_status',
			'text_subtotal',
			'column_period',
			'column_venue',
			'column_date_start',
			'column_date_end',
			'column_orders',
			'column_products',
			'column_tax',
			'column_total',
			'entry_date_start',
			'entry_date_end',
			'entry_group',
			'entry_status',
			'button_filter'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_order_status_id'] = $filter_order_status_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_order', $data));
	}
}
