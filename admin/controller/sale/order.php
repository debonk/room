<?php
class ControllerSaleOrder extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getView();
	}

	public function list()
	{
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}

	public function yearView()
	{
		$this->load->language('sale/order');

		// $this->document->addScript('https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.js');
		// $this->document->addStyle('https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.css');
		$this->document->addScript('view/javascript/yearcalendar/js-year-calendar.js');
		$this->document->addStyle('view/javascript/yearcalendar/js-year-calendar.css');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$language_items = array(
			'heading_title',
			'text_year_view',
			'button_list',
			'button_add'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['list'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'], true);

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_year_view', $data));
	}

	protected function getView()
	{
		$language_items = array(
			'heading_title',
			'text_loading',
			'text_view',
			'entry_month',
			'button_year_view',
			'button_list',
			'button_add',
			'button_filter',
			'button_ip_add'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['filter_month'])) {
			$filter_month = $this->request->get['filter_month'];
		} else {
			$filter_month = date('M Y', strtotime('today'));
		}

		$url = '';

		$url .= '&filter_month=' . $filter_month;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['list'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'], true);
		$data['year_view'] = $this->url->link('sale/order/yearView', 'token=' . $this->session->data['token'], true);

		//Calendar Start
		$data['calendars'] = array();

		$date_data = date('Y-m', strtotime($this->db->escape($filter_month))) . '-01';

		//Title in Indonesia Format
		$this->load->model('localisation/local_date');
		$in_date = $this->model_localisation_local_date->getInFormatDate($date_data);
		$data['title'] = $in_date['month'] . ' ' . $in_date['year'];

		$date_data = getdate(strtotime($date_data));

		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $date_data['mon'], $date_data['year']);
		$total_days = ceil(($date_data['wday'] + $days_in_month) / 7) * 7;

		// /* array "blank" days until the first of the current week */
		$counter = -$date_data['wday'];

		$slot_data = array(
			'prp'	=> 1,
			'prf'	=> 0,
			'cdp'	=> 1,
			'cdf'	=> 0,
			'krp'	=> 0,
			'prm'	=> 1,
			'cdm'	=> 1,
			'krm'	=> 0,
			'krf'	=> 0,
			'pop'	=> 1,
			'pom'	=> 1,
			'pof'	=> 0
		);

		for ($i = 0; $i < $total_days; $i++) {
			$date = date('Y-m-d', strtotime('+' . $counter . ' day', $date_data['0']));

			if ($counter >= 0 && $counter < $days_in_month) {

				$data['calendars'][$date] = array(
					'date'		=> $date,
					'text'		=> $counter + 1,
					'slot_data'	=> $slot_data,
					'url'		=> $this->url->link('sale/order/add', 'token=' . $this->session->data['token'] . '&event_date=' . $date . $url, true),
				);
			} else {
				$data['calendars'][$date] = array(
					'date'		=> '',
					'text'		=> '',
					'slot_data'	=> array(),
					'url'		=> ''
				);
			}

			$counter++;
		}

		$data['weekdays'] = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
		//Calendar End		

		$data['token'] = $this->session->data['token'];

		// $data['filter_month'] = $filter_month;
		$data['filter_month'] = date('M Y', strtotime($this->db->escape($filter_month)));

		// Create Legend
		$this->load->model('localisation/order_status');
		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

		// $processing_statuses = $this->config->get('config_processing_status');
		$processing_statuses = array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'));

		$data['order_statuses'] = array_filter($order_statuses, function ($order_status) use ($processing_statuses) {
			return (in_array($order_status['order_status_id'], $processing_statuses));
		});

		// API login
		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_view', $data));
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_balance',
			'text_loading',
			'text_no_results',
			'text_list',
			'text_confirm',
			'text_missing',
			'text_invoice',
			'text_title',
			'text_slot',
			'entry_order_id',
			'entry_event_date',
			'entry_order_status',
			'entry_customer',
			'entry_total',
			'entry_date_added',
			'column_order_id',
			'column_event_date',
			'column_primary_product',
			'column_customer',
			'column_transaction',
			'column_balance',
			'column_vendor_balance',
			'column_status',
			'column_date_added',
			'column_total',
			'column_username',
			'column_action',
			'button_calendar',
			'button_add',
			'button_filter',
			'button_view',
			'button_year_view',
			'button_edit',
			'button_delete',
			'button_ip_add'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_event_date'])) {
			$filter_event_date = $this->request->get['filter_event_date'];
		} else {
			$filter_event_date = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.event_date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_event_date'])) {
			$url .= '&filter_event_date=' . $this->request->get['filter_event_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['year_view'] = $this->url->link('sale/order/yearView', 'token=' . $this->session->data['token'], true);
		$data['calendar'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'] . $url, true);

		$data['orders'] = array();
		$this->load->model('accounting/transaction');

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_event_date'    => $filter_event_date,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_sale_order->getTotalOrders($filter_data);

		$results = $this->model_sale_order->getOrders($filter_data);

		$processing_statuses = $this->config->get('config_processing_status');

		foreach ($results as $result) {
			$transaction_total['customer'] = $this->model_accounting_transaction->getTransactionsTotalByOrderId($result['order_id'], ['client_label' => 'customer']);

			$transaction_total['vendor'] = $this->model_accounting_transaction->getTransactionsTotalByOrderId($result['order_id'], ['client_label' => 'vendor']);

			$payment_status = '';

			if (in_array($result['order_status_id'], $processing_statuses)) {
				$payment_phases = $this->model_sale_order->getPaymentPhases($result['order_id']);

				foreach ($payment_phases as $payment_phase) {
					if (!$payment_status) {
						$payment_status = $payment_phase['limit_status'];
					}
				}
			}

			$data['orders'][] = array(
				'order_id'        => $result['order_id'],
				'title'        	  => $result['title'],
				'invoice_no'      => $result['invoice_no'] ? $result['invoice_prefix'] . str_pad($result['invoice_no'], 4, 0, STR_PAD_LEFT) : '',
				'event_date'      => date($this->language->get('date_format_short'), strtotime($result['event_date'])),
				'slot'      	  => $result['slot'],
				'primary_product' => $result['primary_product'],
				// 'ceremony'        => $result['ceremony'],
				'customer'        => $result['customer'],
				'order_status'    => $result['order_status'],
				'payment_status'  => $payment_status,
				'total'           => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'balance'         => $this->currency->format($transaction_total['customer'], $result['currency_code'], $result['currency_value']),
				'vendor_balance'  => $this->currency->format($transaction_total['vendor'], $result['currency_code'], $result['currency_value']),
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'        => $result['username'],
				'shipping_code'   => $result['shipping_code'],
				'view'            => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
				'edit'            => $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
			);
		}

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_event_date'])) {
			$url .= '&filter_event_date=' . $this->request->get['filter_event_date'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, true);
		$data['sort_event_date'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=o.event_date' . $url, true);
		$data['sort_primary_product'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=primary_product' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, true);
		$data['sort_username'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_event_date'])) {
			$url .= '&filter_event_date=' . $this->request->get['filter_event_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_event_date'] = $filter_event_date;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_list', $data));
	}

	public function getForm()
	{
		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_loading',
			'text_no_results',
			'text_none',
			'text_order_detail',
			'text_product',
			'text_product_list',
			'text_select',
			'entry_address',
			'entry_address_1',
			'entry_address_2',
			'entry_affiliate',
			'entry_category',
			'entry_city',
			'entry_comment',
			'entry_company',
			'entry_country',
			'entry_coupon',
			'entry_currency',
			'entry_customer',
			'entry_customer_group',
			'entry_email',
			'entry_event_date',
			'entry_fax',
			'entry_firstname',
			'entry_id_no',
			'entry_lastname',
			'entry_marketing_budget',
			'entry_option',
			'entry_order_status',
			'entry_payment_method',
			'entry_postcode',
			'entry_price',
			'entry_product',
			'entry_profession',
			'entry_position',
			'entry_quantity',
			'entry_reward',
			'entry_slot',
			'entry_store',
			'entry_sub_category',
			'entry_telephone',
			'entry_title',
			'entry_zone',
			'entry_zone_code',
			'column_action',
			'column_category',
			'column_model',
			'column_price',
			'column_product',
			'column_quantity',
			'column_total',
			'column_unit_class',
			'button_apply',
			'button_back',
			'button_cancel',
			'button_continue',
			'button_ip_add',
			'button_product_add',
			'button_refresh',
			'button_remove',
			'button_save',
			'button_upload',
			'tab_customer',
			'tab_order',
			'tab_payment',
			'tab_product',
			'tab_total'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . $this->request->get['filter_month'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_event_date'])) {
			$url .= '&filter_event_date=' . $this->request->get['filter_event_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (isset($this->request->get['filter_month'])) {
			$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['cancel'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . $url, true);
		}

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $this->request->get['order_id'];
			$data['store_id'] = $order_info['store_id'];
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = $order_info['customer'];
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_group_id'] = $order_info['customer_group_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['id_no'] = $order_info['id_no'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['fax'] = $order_info['fax'];
			$data['account_custom_field'] = $order_info['custom_field'];

			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);

			$data['title'] = $order_info['title'];
			$data['event_date'] = date('Y-m-d', strtotime($order_info['event_date']));
			$data['slot_id'] = $order_info['slot_id'];
			// $data['ceremony_id'] = $order_info['ceremony_id'];
			// $data['primary_type'] = $order_info['primary_type'];

			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_profession'] = $order_info['payment_profession'];
			$data['payment_position'] = $order_info['payment_position'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];

			// Products
			$data['order_products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			$this->load->model('catalog/product');

			foreach ($products as $product) {
				if ($product['primary_type']) {
					$product_slots = $this->model_catalog_product->getProductSlots($product['product_id']);
				}

				$data['order_products'][] = array(
					'product_id'   => $product['product_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
					'quantity'     => $product['quantity'],
					'price'        => $product['price'],
					'total'        => $product['total'],
					'reward'       => $product['reward'],
					'primary_type' => $product['primary_type'],
					'category_id'  => $product['category_id'],
					'category'     => $product['category']
				);
			}

			// Vouchers
			// $data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			$data['marketing_budget'] = '';
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['order_totals'] = array();

			$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}

				if ($order_total['code'] == 'marketing_budget') {
					$data[$order_total['code']] = abs($order_total['value']);
				}
			}

			$data['order_status_id'] = $order_info['order_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
			$data['currency_code'] = $order_info['currency_code'];
		} else {
			$data['order_id'] = 0;
			$data['store_id'] = '';
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['id_no'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['title'] = '';

			if (isset($this->request->get['event_date'])) {
				$data['event_date'] = $this->request->get['event_date'];
			} else {
				$data['event_date'] = '';
			}

			$data['slot_id'] = '';
			// $data['ceremony_id'] = '';
			// $data['primary_type'] = '';

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_profession'] = '';
			$data['payment_position'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();

			$data['order_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['marketing_budget'] = '';
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		$data['url'] = $url;

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Categories
		$this->load->model('catalog/category');
		$data['main_categories'] = [];

		$main_categories = $this->model_catalog_category->getCategoriesByParentId();

		foreach ($main_categories as $category) {
			if ($category['top']) {
				$data['main_categories'][] = [
					'category_id'	=> $category['category_id'],
					'name'			=> $category['name'],
				];
			}
		}

		// Slots
		$data['slots'] = array();

		$this->load->model('localisation/slot');

		$slots = $this->model_localisation_slot->getSlots();

		foreach ($slots as $slot) {
			if (isset($product_slots[$slot['slot_id']])) {
				$prefix = $product_slots[$slot['slot_id']]['price'] < 0 ? '-' : '+';
				$text_price = ' (' . $prefix . ' ' . $this->currency->format($product_slots[$slot['slot_id']]['price'], $this->session->data['currency']) . ')';
			} else {
				$text_price = '';
			}

			$data['slots'][] = array(
				'slot_id'		=> $slot['slot_id'],
				'name'			=> $slot['name'] . $text_price
			);
		}

		// Ceremonies
		// $this->load->model('localisation/ceremony');

		// $data['ceremonies'] = $this->model_localisation_ceremony->getCeremonies();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		// $data['voucher_min'] = $this->config->get('config_voucher_min');

		// $this->load->model('sale/voucher_theme');

		// $data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['user_id'] = $this->user->getId();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_form', $data));
	}

	public function info()
	{
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->setTitle($this->language->get('heading_title'));

			$language_items = array(
				'heading_title',
				'text_accept_language',
				'text_account_custom_field',
				'text_affiliate',
				'text_browser',
				'text_comment',
				'text_confirm',
				'text_customer_detail',
				'text_customer',
				'text_date_added',
				'text_email',
				'text_event_date',
				'text_forwarded_ip',
				'text_history_add',
				'text_history',
				'text_invoice',
				'text_ip',
				'text_loading',
				'text_option',
				'text_order_detail',
				'text_package',
				'text_payment_address',
				'text_payment_custom_field',
				'text_payment_method',
				'text_preview',
				'text_print_confirm',
				'text_print',
				'text_reward',
				'text_sales',
				'text_secondary_type',
				'text_slot',
				'text_telephone',
				'text_title',
				'text_user_agent',
				'text_vendor',
				'text_venue',
				'entry_vendor',
				'entry_order_status',
				'entry_notify',
				'entry_override',
				'entry_comment',
				'column_category',
				'column_model',
				'column_price',
				'column_product',
				'column_quantity',
				'column_total',
				'column_phase_title',
				'column_phase_amount',
				'column_phase_limit_date',
				'column_phase_status',
				'help_generate_first',
				'help_override',
				'tab_additional',
				'tab_history',
				'tab_order',
				'tab_customer',
				'tab_purchase',
				'tab_vendor',
				'button_agreement',
				'button_receipt',
				'button_cancel',
				'button_document',
				'button_edit',
				'button_expired',
				'button_generate',
				'button_reward_add',
				'button_reward_remove',
				'button_commission_add',
				'button_commission_remove',
				'button_vendor_add',
				'button_vendor_remove',
				'button_history_add',
				'button_ip_add'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_month'])) {
				$url .= '&filter_month=' . $this->request->get['filter_month'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_event_date'])) {
				$url .= '&filter_event_date=' . $this->request->get['filter_event_date'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['document'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'], true);
			$data['agreement_preview'] = $this->url->link('sale/order/agreement', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['agreement_print'] = $this->url->link('sale/order/agreement', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'] . '&print=1', true);
			$data['edit'] = $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'] . $url, true);

			$data['printed'] = $order_info['printed'] ? 1 : 0;

			if (isset($this->request->get['filter_month'])) {
				$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, true);
			} else {
				$data['cancel'] = $this->url->link('sale/order/list', 'token=' . $this->session->data['token'] . $url, true);
			}

			$data['order_id'] = $this->request->get['order_id'];

			$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);

			$this->load->model('localisation/local_date');
			$event_date = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);
			$data['event_date'] = $event_date['day'] . ', ' . $event_date['long_date'];

			if (!$order_info['title']) {
				$order_info['title'] = sprintf($this->language->get('text_atas_nama'), $order_info['firstname'] . ' ' . $order_info['lastname']);
			}

			$data['title'] = $order_info['title'];
			$data['slot'] = $order_info['slot'];

			$data['date_added'] = $this->model_localisation_local_date->getInFormatDate($order_info['date_added'])['long_date'];

			$data['store_url'] = $this->request->server['HTTPS'] ? str_replace("http", "https", $order_info['store_url']) : $order_info['store_url'];

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . str_pad($order_info['invoice_no'], 4, 0, STR_PAD_LEFT);
			} else {
				$data['invoice_no'] = '';
			}

			$this->load->model('user/user');
			$sales_info = $this->model_user_user->getUserByUsername($order_info['username']);

			$data['sales'] = $sales_info['firstname'] . ' ' . $sales_info['lastname'];

			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];

			$data['payment_method'] = $order_info['payment_method'];

			// Payment Address
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}{profession}{position}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}, {country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{profession}',
				'{position}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' 	=> $order_info['payment_firstname'],
				'lastname'  	=> $order_info['payment_lastname'],
				'company'   	=> $order_info['payment_company'],
				'profession'	=> $order_info['payment_profession'] ? ', ' . $order_info['payment_profession'] : '',
				'position'  	=> $order_info['payment_position'] ? ', ' . $order_info['payment_position'] : '',
				'address_1' 	=> $order_info['payment_address_1'],
				'address_2' 	=> $order_info['payment_address_2'],
				'city'      	=> $order_info['payment_city'],
				'postcode'  	=> $order_info['payment_postcode'],
				'zone'      	=> $order_info['payment_zone'],
				'zone_code' 	=> $order_info['payment_zone_code'],
				'country'   	=> $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['package'] = '';
			$data['venue'] = '';

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				if ($product['primary_type']) {
					$data['package'] = $product['name'];
				}

				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
							);
						}
					}
				}

				$attribute_data = array();

				$attributes = $this->model_sale_order->getOrderAttributes($this->request->get['order_id'], $product['order_product_id']);

				foreach ($attributes as $attribute) {
					if ($product['primary_type']) {
						if ($attribute['attribute_group'] == 'Kelengkapan Paket' && $attribute['attribute'] == 'Venue') {
							$data['venue'] = $attribute['text'];
						}
					}

					$attribute_data[$attribute['attribute_group']][] = array(
						'name'	=> $attribute['attribute'],
						'value'	=> $attribute['text']
					);
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'primary_type'	   => $product['primary_type'],
					'category'		   => $product['category'],
					'option'   		   => $option_data,
					'attribute'   	   => $attribute_data,
					'quantity'		   => $product['quantity'] . ' ' . $product['unit_class'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			// Totals
			$this->load->model('accounting/transaction');

			$data['totals'] = array();
			$data['transactions'] = array();

			$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			$summary_data = [
				'client_label'		=> 'customer',
				'category_label'	=> 'order',
				'transaction_label'	=> 'cash',
			];
	
			$transaction_total = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $summary_data);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' 	=> $total['title'],
					'text'  	=> $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			if ($transaction_total) {
				$data['totals'][] = array(
					'title' => $this->language->get('text_total'),
					'text'  => $this->currency->format(-$transaction_total, $order_info['currency_code'], $order_info['currency_value'])
				);

				$data['totals'][] = array(
					'title' => $this->language->get('text_balance'),
					'text'  => $this->currency->format($order_info['total'] - $transaction_total, $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Vendors
			$data['order_vendors'] = array();

			$order_vendors = $this->model_sale_order->getOrderVendors($order_id);

			# Maintain Version 1
			$this->load->model('catalog/vendor');
			# End Maintain
			
			foreach ($order_vendors as $order_vendor) {
				# Maintain Version 1
				if (empty($order_vendor['vendor_name'])) {
					$vendor_info = $this->model_catalog_vendor->getVendor($order_vendor['vendor_id']);
					$order_vendor['vendor_name'] = $vendor_info['vendor_name'];
					$order_vendor['vendor_type'] = $vendor_info['vendor_type'];
				}
				# End Maintain

				$data['order_vendors'][] = array(
					'vendor_id' 		=> $order_vendor['vendor_id'],
					'title' 			=> $order_vendor['vendor_name'] . ' - ' . $order_vendor['vendor_type'],
					// 'document'			=> $document_data
				);
			}

			# Vendor List
			$this->load->model('catalog/vendor');

			$data['vendors'] = array();

			$filter_data = array(
				'filter_status'	=> 1,
				'sort'			=> 'vt.sort_order ASC, v.vendor_name',
				'order'         => 'ASC'
			);

			$vendors = $this->model_catalog_vendor->getVendors($filter_data);

			foreach ($vendors as $vendor) {
				if (!in_array($vendor['vendor_id'], array_column($data['order_vendors'], 'vendor_id'))) {
					$data['vendors'][] = array(
						'vendor_id' => $vendor['vendor_id'],
						'title' 	=> $vendor['vendor_name'] . ' - ' . $vendor['vendor_type']
					);
				}
			}

			# Payment Phase
			$this->load->model('localisation/order_status');

			$data['payment_phases'] = [];
			$data['information'] = '';
			$data['auto_expired'] = false;
			// $expired = false;

			$payment_phases = $this->model_sale_order->getPaymentPhases($order_id);

			foreach ($payment_phases as $payment_phase) {
				$limit_date_in = $this->model_localisation_local_date->getInFormatDate(date('Y-m-d', $payment_phase['limit_stamp']));

				if (!$data['information']) {
					if ($payment_phase['limit_status'] == 'expired') {
						$data['information'] = sprintf($this->language->get('info_expired'), $payment_phase['title']);

						if (!$data['auto_expired']) {
							$data['auto_expired'] = $payment_phase['auto_expired'];
						}
					} elseif ($payment_phase['limit_status'] == 'warning') {
						$data['information'] = sprintf($this->language->get('info_warning'), $payment_phase['title'], $limit_date_in['long_date']);
					}
				}

				$data['payment_phases'][] = array(
					'title'			=> $payment_phase['title'],
					'amount'		=> $this->currency->format($payment_phase['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'limit_date'	=> $limit_date_in['long_date'],
					'status'		=> $payment_phase['paid_status']
				);
			}

			$data['initial_payment'] = $payment_phases['initial_payment']['paid_status'];

			$data['comment'] = nl2br($order_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$data['order_status_id'] = $order_info['order_status_id'];

			$filter_data['parent_status_id']  = $order_info['order_status_id'];
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses($filter_data);

			$data['account_custom_field'] = $order_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC',
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			$this->load->model('extension/extension');

			$content = $this->load->controller('payment/' . $order_info['payment_code'] . '/order');

			if ($content) {
				$this->load->language('payment/' . $order_info['payment_code']);

				$data['tabs'][] = array(
					'code'    => $order_info['payment_code'],
					'title'   => $this->language->get('heading_title'),
					'content' => $content
				);
			}

			$extensions = $this->model_extension_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('fraud/' . $extension);

					$content = $this->load->controller('fraud/' . $extension . '/order');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}

			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}

			$data['user_id'] = $this->user->getId();

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/order_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}

	public function yearViewData()
	{
		$this->load->language('sale/order');

		$json = array();

		if (isset($this->request->get['filter_year'])) {
			$filter_year = $this->request->get['filter_year'];
		} else {
			$filter_year = date('Y', strtotime('today'));
		}

		$this->load->model('sale/order');

		$filter_data = array(
			'filter_order_status' => implode(',', array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))),
			'filter_year'         => $filter_year
		);

		$results = $this->model_sale_order->getOrdersEventDate($filter_data);

		$json['results'] = array_unique(array_column($results, 'event_date'));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function viewData()
	{
		$this->load->language('sale/order');

		$json = array();

		if (isset($this->request->get['filter_month'])) {
			$filter_month = $this->request->get['filter_month'];
		} else {
			$filter_month = date('M Y', strtotime('today'));
		}

		$url = '';
		$url .= '&filter_month=' . $filter_month;

		$this->load->model('sale/order');

		$filter_data = array(
			'filter_month'         => $filter_month
		);

		$results = $this->model_sale_order->getOrders($filter_data);

		// $processing_statuses = $this->config->get('config_processing_status');
		$processing_statuses = array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'));

		foreach ($results as $result) {
			// $session_slot = explode(': ', $result['session_slot']);
			// $slot_idx = strtolower($session_slot[0]);
			$slot_idx = strtolower((empty($result['slot_prefix']) ? $result['model'] : $result['slot_prefix']) . $result['slot_code']);

			if (in_array($result['order_status_id'], $processing_statuses)) {
				$slot_remove = $this->model_sale_order->getSlotUsed($slot_idx);

				$order_summary = sprintf($this->language->get('text_order_summary'), $result['title'], $result['primary_product'], $result['customer'], $result['order_status']);

				// Check payment status
				$payment_phases = $this->model_sale_order->getPaymentPhases($result['order_id']);

				$auto_expired = false;
				$payment_status = '';

				foreach ($payment_phases as $payment_phase) {
					$payment_status = $payment_phase['limit_status'];

					if ($payment_phase['limit_status'] == 'expired' && !$auto_expired) {
						$auto_expired = $payment_phase['auto_expired'];
					}
				}

				$json['orders'][] = array(
					'slot_idx'        		=> $slot_idx,
					'slot_name'        		=> $slot_idx,
					'event_date'      		=> $result['event_date'],
					'slot_remove'     		=> $slot_remove,
					'order_summary'   		=> $order_summary,
					'order_status_class'	=> $result['order_status_class'],
					'url'			  		=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, true),
					'payment_status'   		=> $payment_status,
					'auto_expired'   		=> $auto_expired
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function createInvoiceNo()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addOrderVendor()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/vendor')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('catalog/vendor');
				$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['vendor_id']);

				if (!$vendor_info) {
					$json['error'] = $this->language->get('error_vendor');
				}
			} else {
				$json['error'] = $this->language->get('error_order');
			}
		}

		if (!$json) {
			if (!$order_info['invoice_no']) {
				$this->model_sale_order->createInvoiceNo($order_id);
			}

			$order_vendors = $this->model_sale_order->getOrderVendors($order_id);

			if (!in_array($this->request->post['vendor_id'], array_column($order_vendors, 'vendor_id'))) {

				$vendor_data = [
					'vendor_id'		=> $vendor_info['vendor_id'],
					'vendor_name'	=> $vendor_info['vendor_name'],
					'vendor_type'	=> $vendor_info['vendor_type']
				];

				$this->model_sale_order->addOrderVendor($order_id, $vendor_data);

				$json['title'] = $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'];

				$json['success'] = $this->language->get('text_vendor_added');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function deleteOrderVendor()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/vendor')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/purchase');
			$order_purchase_info = $this->model_sale_purchase->getOrderPurchase($order_id, $this->request->post['vendor_id']);

			if ($order_purchase_info) {
				$json['error'] = $this->language->get('error_order_purchase');
			}
		}

		if (!$json) {
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($order_id);

			$this->load->model('accounting/transaction');

			$filter_data = array(
				'client_label'	=> 'vendor',
				'client_id'		=> $this->request->post['vendor_id']
			);

			$transaction_total = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_data);

			if ($order_info && ($transaction_total == 0)) {
				$this->model_sale_order->deleteOrderVendor($order_id, $this->request->post['vendor_id']);

				$this->load->model('catalog/vendor');

				$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['vendor_id']);

				$json['title'] 	= $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'];

				$json['success'] = $this->language->get('text_vendor_removed');
			} else {
				$json['error'] = sprintf($this->language->get('error_vendor_transaction'), $this->currency->format($transaction_total, $order_info['currency_code'], $order_info['currency_value']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addReward()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($order_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('marketing/affiliate');

				$affiliate_total = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($order_id);

				if (!$affiliate_total) {
					$this->model_marketing_affiliate->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission()
	{
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('marketing/affiliate');

				$this->model_marketing_affiliate->deleteTransaction($order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history()
	{
		$this->load->language('sale/order');

		$language_items = array(
			'text_no_results',
			'column_date_added',
			'column_status',
			'column_notify',
			'column_comment',
			'column_username'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'   => $result['username']
			);
		}

		$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);


		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/order/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/order_history', $data));
	}

	public function agreement()
	{
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		$payment_phases = $this->model_sale_order->getPaymentPhases($order_id);

		if ($order_info && $payment_phases['initial_payment']['paid_status']) {
			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');

			$this->load->language('sale/order');
			$this->load->language('sale/document');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$language_items = array(
				'title_agreement',
				'text_address',
				'text_belum_ppn',
				'text_comment',
				'text_company',
				'text_customer_group',
				'text_customer',
				'text_day_date',
				'text_dst',
				'text_id_no',
				'text_info_tambahan',
				'text_invoice_no',
				'text_kelengkapan',
				'text_layanan_tambahan',
				'text_mark',
				'text_order_vendor',
				'text_package',
				'text_pembatalan_1',
				'text_pembatalan_2',
				'text_pembatalan_3',
				'text_pembatalan_acara',
				'text_pihak_penyewa',
				'text_position',
				'text_price',
				'text_profession',
				'text_quantity',
				'text_slot',
				'text_snk',
				'text_telah_bayar',
				'text_telephone',
				'text_total',
				'text_transfer_ke',
				'text_ubah_tanggal',
				'text_venue',
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$data['letter_head'] = HTTP_CATALOG . 'image/catalog/letter_head.png';

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . str_pad($order_info['invoice_no'], 4, 0, STR_PAD_LEFT);
			} else {
				$data['invoice_no'] = $this->model_sale_order->createInvoiceNo($order_id);
			}

			$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

			if ($store_info) {
				$data['store_logo'] = HTTP_CATALOG . 'image/' . $store_info['config_logo'];
				$data['store_name'] = $store_info['config_name'];
				$data['store_slogan'] = htmlspecialchars_decode($store_info['config_slogan'], ENT_NOQUOTES);
				$data['store_address'] = strtoupper($store_info['config_address']);
				$data['store_email'] = $store_info['config_email'];
				$data['store_telephone'] = $store_info['config_telephone'];
				$data['store_fax'] = $store_info['config_fax'];
				$data['store_owner'] = $store_info['config_owner'];
			} else {
				$data['store_logo'] = HTTP_CATALOG . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $this->config->get('config_name');
				$data['store_slogan'] = htmlspecialchars_decode($this->config->get('config_slogan'), ENT_NOQUOTES);
				$data['store_address'] = strtoupper($this->config->get('config_address'));
				$data['store_email'] = $this->config->get('config_email');
				$data['store_telephone'] = $this->config->get('config_telephone');
				$data['store_fax'] = $this->config->get('config_fax');
				$data['store_owner'] = $this->config->get('config_owner');
			}

			$data['store_url'] = ltrim(rtrim($order_info['store_url'], '/'), 'http://');

			$date_added_in = $this->model_localisation_local_date->getInFormatDate($order_info['date_added']);

			if (!$order_info['title']) {
				$order_info['title'] = sprintf($this->language->get('text_atas_nama'), $order_info['firstname'] . ' ' . $order_info['lastname']);
			}

			$data['text_pada_hari'] = sprintf($this->language->get('text_pada_hari'), $order_info['title'], $date_added_in['day'], $date_added_in['long_date']);

			$address_format = array(
				$order_info['payment_address_1'] . ($order_info['payment_address_2'] ? ', ' . $order_info['payment_address_2'] : ''),
				$order_info['payment_city'],
				$order_info['payment_zone'],
				$order_info['payment_country'],
				$order_info['payment_postcode']
			);

			$address_format = implode(', ', $address_format);

			// Custom Fields
			$custom_fields = array();

			$this->load->model('customer/custom_field');

			foreach ($order_info['custom_field'] as $custom_field_id => $custom_field_value) {
				$custom_field_info = $this->model_customer_custom_field->getCustomField($custom_field_id);

				if ($custom_field_info) {
					$custom_fields[] = array(
						'name'			=> $custom_field_info['name'],
						'value'			=> $custom_field_value
					);
				}
			}

			foreach ($order_info['payment_custom_field'] as $custom_field_id => $custom_field_value) {
				$custom_field_info = $this->model_customer_custom_field->getCustomField($custom_field_id);

				if ($custom_field_info) {
					$custom_fields[] = array(
						'name'			=> $custom_field_info['name'],
						'value'			=> $custom_field_value
					);
				}
			}
			// END Custom Fields

			// Customer Data
			$data['customer'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$data['id_no'] = $order_info['id_no'];
			$data['customer_group'] = $order_info['customer_group'];
			$data['company'] = $order_info['payment_company'];
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($address_format)));
			$data['profession'] = $order_info['payment_profession'];
			$data['position'] = $order_info['payment_position'];
			$data['telephone'] = $order_info['telephone'];
			$data['custom_fields'] = $custom_fields;

			$data['text_sewa_tempat'] = sprintf($this->language->get('text_sewa_tempat'), $data['store_name']);

			// Event Data
			$event_date_in = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);

			$data['event_date'] = $event_date_in['day'] . '/' . $event_date_in['long_date'];
			$data['slot'] = $order_info['slot'];
			// $data['ceremony'] = $order_info['ceremony'];

			// Product Data
			$data['products'] = array();
			$data['venue'] = '';

			$products = $this->model_sale_order->getOrderProducts($order_id);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
					);
				}

				$attribute_data = array();

				$attributes = $this->model_sale_order->getOrderAttributes($order_id, $product['order_product_id']);

				foreach ($attributes as $attribute) {
					if ($product['primary_type'] && $attribute['attribute'] == 'Venue') {
						$data['venue'] = $attribute['text'];
					} else {
						$attribute_data[$attribute['attribute_group']][] = array(
							'name'	=> $attribute['attribute'],
							'value'	=> $attribute['text']
						);
					}
				}

				if ($product['primary_type']) {
					$key = 'primary';
				} elseif ($product['category'] == 'Included in Package') {
					$key = 'included';
				} else {
					$key = 'additional';
				}

				$data['products'][$key][] = array(
					'category'	=> $product['category'],
					'name'    	=> $product['name'],
					'quantity'	=> $product['quantity'] . '&nbsp;' . $product['unit_class'],
					'option'  	=> $option_data,
					'attribute'	=> $attribute_data,
					'total'		=> $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['product_primary'] = $data['products']['primary'][0];

			$data['package'] = $data['products']['primary'][0]['name'];
			$data['price'] = $data['products']['primary'][0]['total'];

			$data['text_termasuk'] = sprintf($this->language->get('text_termasuk'), $data['products']['primary'][0]['name']);

			// Vendors
			$data['order_vendors'] = array();

			$order_vendors = $this->model_sale_order->getOrderVendors($order_id);

			foreach ($order_vendors as $order_vendor) {
				$data['order_vendors'][] = array(
					'type' => $order_vendor['vendor_type'],
					'name' => $order_vendor['vendor_name']
				);
			}

			// Total
			$data['totals'] = array();

			$totals = $this->model_sale_order->getOrderTotals($order_id);

			foreach ($totals as $total) {
				$data['totals'][$total['code']] = array(
					'title' => $total['title'],
					'value' => $total['value'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			if (isset($data['totals']['sub_total']['value']) && isset($data['totals']['total']['value']) && $data['totals']['sub_total']['value'] == $data['totals']['total']['value']) {
				unset($data['totals']['sub_total']);
			}

			// Transaction
			$this->load->model('accounting/transaction');

			$data['transactions'] = array();

			$filter_data = array(
				'client_label'		=> 'customer',
				'category_label'	=> 'order',
				'transaction_label'	=> 'cash'
			);

			$transactions_total['order'] = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_data);

			$filter_data = array(
				'client_label'		=> 'customer',
				'category_label'	=> 'deposit',
				'transaction_label'	=> 'cash'
			);

			$transactions_total['deposit'] = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_data);

			foreach ($transactions_total as $category => $total) {
				if ($total) {
					$data['transactions'][] = array(
						'title'	=> $category ? $this->language->get('text_category_' . $category) : '',
						'text'	=> $this->currency->format($total, $order_info['currency_code'], $order_info['currency_value'])
					);
				}
			}

			$data['text_transactions'] = array();

			$down_payment = round($order_info['total'] * $this->config->get('config_down_payment_amount') / 100000, 0) * 1000;

			if ($down_payment > $transactions_total['order']) {
				$data['text_transactions']['text_uang_muka'] = sprintf($this->language->get('text_uang_muka'), $this->currency->format($down_payment - $transactions_total['order'], $order_info['currency_code'], $order_info['currency_value']));
				$data['text_transactions']['text_pelunasan'] = sprintf($this->language->get('text_pelunasan'), $this->currency->format($order_info['total'] - $down_payment, $order_info['currency_code'], $order_info['currency_value']));
			} elseif ($order_info['total'] > $transactions_total['order']) {
				$data['text_transactions']['text_pelunasan'] = sprintf($this->language->get('text_pelunasan'), $this->currency->format($order_info['total'] - $transactions_total['order'], $order_info['currency_code'], $order_info['currency_value']));
			}

			$deposit = $this->config->get('config_customer_deposit');
			if ($deposit) {
				$data['text_transactions']['text_uang_jaminan'] = sprintf($this->language->get('text_jaminan_customer'), $this->currency->format($deposit, $order_info['currency_code'], $order_info['currency_value']));
			}

			$no_rekening = $this->config->get($order_info['payment_code'] . '_bank' . $order_info['language_id']);
			$data['no_rekening'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($no_rekening)));

			$data['text_tukar_bukti'] = sprintf($this->language->get('text_tukar_bukti'), $data['store_owner']);
			$data['text_demikian'] = sprintf($this->language->get('text_demikian'), $data['store_owner']);

			// User Info
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->user->getId());

			$data['text_manajemen'] = $user_info['user_group'];
			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';

			$data['comment'] = nl2br($order_info['comment']);

			$print = isset($this->request->get['print']) && $this->request->get['print'] == 1;
			$preview = $order_info['printed'] || !$print || !$this->user->hasPermission('modify', 'sale/order');
			// $preview = 0;// Development Purpose

			if ($preview) {
				$data['preview'] = 1;
				# Agar preview only bisa diprint.
				// $data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_sale_order->editOrderPrintStatus($order_id, 1);
			}
		} else {
			return false;
		}

		$this->response->setOutput($this->load->view('sale/order_agreement', $data));
	}

	public function receipt()
	{
		$this->load->model('sale/order');
		$this->load->model('accounting/transaction');

		if (isset($this->request->get['transaction_id'])) {
			$transaction_id = $this->request->get['transaction_id'];
		} else {
			$transaction_id = '';
		}

		$transaction_info = $this->model_accounting_transaction->getTransaction($transaction_id);

		if ($transaction_info) {
			$order_id = $transaction_info['order_id'];
			$order_info = $this->model_sale_order->getOrder($order_id);

			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');

			$this->load->language('sale/order');
			$this->load->language('sale/document');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$language_items = array(
				'title_receipt',
				'text_mark',
				'text_invoice_no',
				'text_attn',
				'text_address',
				'text_sejumlah'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

			if ($store_info) {
				$data['store_logo'] = HTTP_CATALOG . 'image/' . $store_info['config_logo'];
				$data['store_name'] = $store_info['config_name'];
				$data['store_slogan'] = htmlspecialchars_decode($store_info['config_slogan'], ENT_NOQUOTES);
				$data['store_address'] = strtoupper($store_info['config_address']);
				$data['store_email'] = $store_info['config_email'];
				$data['store_telephone'] = $store_info['config_telephone'];
				$data['store_fax'] = $store_info['config_fax'];
				$data['store_owner'] = $store_info['config_owner'];
			} else {
				$data['store_logo'] = HTTP_CATALOG . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $this->config->get('config_name');
				$data['store_slogan'] = htmlspecialchars_decode($this->config->get('config_slogan'), ENT_NOQUOTES);
				$data['store_address'] = strtoupper($this->config->get('config_address'));
				$data['store_email'] = $this->config->get('config_email');
				$data['store_telephone'] = $this->config->get('config_telephone');
				$data['store_fax'] = $this->config->get('config_fax');
				$data['store_owner'] = $this->config->get('config_owner');
			}

			$data['store_url'] = ltrim(rtrim($order_info['store_url'], '/'), 'http://');

			if ($transaction_info['amount'] < 0) {
				$income = false;

				$data['text_subject'] = $this->language->get('text_from');
			} else {
				$income = true;

				$data['text_subject'] = $this->language->get('text_to');
			}

			$data['invoice_no'] = $transaction_info['reference'];

			// Event Data
			if (!$order_info['title']) {
				$order_info['title'] = sprintf($this->language->get('text_atas_nama'), $order_info['firstname'] . ' ' . $order_info['lastname']);
			}

			$event_date_in = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);

			// Product Data
			$primary_product = $this->model_sale_order->getOrderPrimaryProduct($order_id);

			$venue_info = '';

			$attributes = $this->model_sale_order->getOrderAttributes($order_id, $primary_product['order_product_id']);

			foreach ($attributes as $attribute) {
				if ($attribute['attribute'] == 'Venue') {
					$venue_info = $attribute['text'];
				}
			}

			$venue_info .= ($venue_info ? ' - ' : '') . $data['store_name'];
		
			// Transaction Info
			$transaction_date_in = $this->model_localisation_local_date->getInFormatDate($transaction_info['date']);
			$transaction_title	= $transaction_info['transaction_type'];
			// $transaction_title	= $transaction_info['description'];

			$data['date']	= $transaction_date_in['long_date'];
			$data['amount']	= $this->currency->format(abs($transaction_info['amount']), $order_info['currency_code'], $order_info['currency_value']);
			$data['terbilang'] = sprintf($this->language->get('text_terbilang'), $this->model_localisation_local_date->getInWord($transaction_info['amount']));

			switch ($transaction_info['client_label']) {
				case 'customer':
					$data['name'] = $order_info['firstname'] . ' ' . $order_info['lastname'] . ($order_info['payment_company'] ? ' - ' . $order_info['payment_company'] : '');

					$address_format = sprintf($this->language->get('text_address_format'), $order_info['payment_address_1'] . ($order_info['payment_address_2'] ? ', ' . $order_info['payment_address_2'] : ''), $order_info['payment_city'], $order_info['payment_postcode'], $order_info['payment_zone'], $order_info['payment_country']);

					$data['text_hal'] = sprintf($this->language->get('text_atas_persewaan'), $transaction_title, $primary_product['name'], $venue_info, $order_info['title'], $event_date_in['long_date']);
					$data['text_pihak'] = $this->language->get('text_pihak_penyewa');
					$data['tanda_tangan'] = '( ' . $order_info['firstname'] . ' ' . $order_info['lastname'] . ' )';
					$data['company'] = $order_info['payment_company'] ? $order_info['payment_company'] : '';

					break;

				case 'vendor':
					$this->load->model('catalog/vendor');

					$vendor_info = $this->model_catalog_vendor->getVendor($transaction_info['client_id']);

					$data['name'] = $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'] . ($vendor_info['contact_person'] ? ' (' . $vendor_info['contact_person'] . ')' : '');

					$address_format = $vendor_info['address'];

					$data['text_hal'] = sprintf($this->language->get('text_atas_pelaksanaan'), $transaction_title, $order_info['title'], $venue_info, $event_date_in['long_date']);
					$data['text_pihak'] = $this->language->get('text_pihak_vendor');
					$data['tanda_tangan'] = $this->language->get('text_tanda_tangan');
					$data['company'] = $vendor_info['vendor_name'];

					break;

				default:
					$data['name'] = '';
					$address_format = '';
					$data['text_hal'] = '';
					$data['text_pihak'] = '';
					$data['tanda_tangan'] = '';
					$data['company'] = '';
			}

			// Customer Data
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($address_format)));

			// Note
			$this->load->model('user/user');

			$data['notes'] = array();

			if ($transaction_info['user_id'] != $this->user->getId()) {
				$transaction_user_info = $this->model_user_user->getUser($transaction_info['user_id']);
				$data['notes'][] = sprintf($this->language->get('text_diterima'), $transaction_user_info['firstname'] . ' ' . $transaction_user_info['lastname'], $transaction_user_info['user_group']);
			}

			if (!$income) {
				$data['notes'][] = sprintf($this->language->get('text_disimpan'), $data['store_owner']);
			} else {
				$data['text_pihak'] = '';
				$data['tanda_tangan'] = '';
				$data['company'] = '';
			}

			// User Info
			$user_info = $this->model_user_user->getUser($this->user->getId());

			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';
			$data['text_manajemen'] = $user_info['user_group'];

			$print = isset($this->request->get['print']) && $this->request->get['print'] == 1;
			$preview = $transaction_info['printed'] || !$print || !$this->user->hasPermission('modify', 'sale/order');
			// $preview = 0;// Development Purpose

			if ($preview) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_accounting_transaction->editTransactionPrintStatus($transaction_id, 1);
			}
		} else {
			return false;
		}

		$this->response->setOutput($this->load->view('sale/order_receipt', $data));
	}

	public function document()
	{
		$filename = 'Tata_Tertib_Vendor.pdf';
		$file = DIR_DOWNLOAD . $filename;

		if (!headers_sent()) {
			if (file_exists($file)) {
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="' . $filename . '"');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');

				if (ob_get_level()) {
					ob_end_clean();
				}

				readfile($file);

				exit();
			} else {
				exit('Error: Could not find file ' . $file . '!');
			}
		} else {
			exit('Error: Headers already sent out!');
		}
	}

	public function productSelect()
	{
		$json = [];

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$categories = $this->model_catalog_category->getCategoriesByParentId($this->request->get['main_category_id']);

		foreach ($categories as $category) {
			$product_data = [];

			$filter_data = [
				'filter_category'	=> $category['category_id'],
				'filter_status'	 	=> 1
			];

			$products = $this->model_catalog_product->getProducts($filter_data);

			foreach ($products as $product) {
				// $product_data[$category['category_id']][] = [
				$product_data[] = [
					'product_id'	=> $product['product_id'],
					'name'			=> $product['name'],
				];
			}

			$json[] = [
				'category_id'	=> $category['category_id'],
				'name'			=> $category['name'],
				'product'		=> $product_data
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function product()
	{
		$json = array();

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

		if ($product_info) {
			$this->load->model('catalog/option');

			$option_data = array();

			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);

			foreach ($product_options as $product_option) {
				$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

				if ($option_info) {
					$product_option_value_data = array();

					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

						if ($option_value_info) {
							$product_option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $option_value_info['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);
						}
					}

					$option_data[] = array(
						'product_option_id'    => $product_option['product_option_id'],
						'product_option_value' => $product_option_value_data,
						'option_id'            => $product_option['option_id'],
						'name'                 => $option_info['name'],
						'type'                 => $option_info['type'],
						'value'                => $product_option['value'],
						'required'             => $product_option['required']
					);
				}
			}

			$json = array(
				'product_id'    => $product_info['product_id'],
				'name'          => $product_info['name'],
				'price'        	=> $this->currency->format($product_info['price'], $this->session->data['currency']),
				'option'        => $option_data
				// 'slot'        	=> $slot_data
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
