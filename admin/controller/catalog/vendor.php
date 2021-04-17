<?php
class ControllerCatalogVendor extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->addVendor($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_vendor_name'])) {
				$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->editVendor($this->request->get['vendor_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_vendor_name'])) {
				$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $vendor_id) {
				$this->model_catalog_vendor->deleteVendor($vendor_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_vendor_name'])) {
				$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['filter_vendor_name'])) {
			$filter_vendor_name = $this->request->get['filter_vendor_name'];
		} else {
			$filter_vendor_name = null;
		}

		if (isset($this->request->get['filter_vendor_type_id'])) {
			$filter_vendor_type_id = $this->request->get['filter_vendor_type_id'];
		} else {
			$filter_vendor_type_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'v.vendor_name';
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

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_vendor_type_id'])) {
			$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/vendor/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/vendor/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['vendors'] = array();

		$filter_data = array(
			'filter_vendor_name'	=> $filter_vendor_name,
			'filter_vendor_type_id'	=> $filter_vendor_type_id,
			'filter_status'     	=> $filter_status,
			'sort'             		=> $sort,
			'order'            		=> $order,
			'start'            		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'            		=> $this->config->get('config_limit_admin')
		);

		$vendor_count = $this->model_catalog_vendor->getVendorsCount($filter_data);

		$results = $this->model_catalog_vendor->getVendors($filter_data);

		$this->load->model('accounting/transaction');

		foreach ($results as $result) {
			$filter_summary_data = [
				'filter'	=> [
					'client_label'	=> 'vendor',
					'client_id'		=> $result['vendor_id']
				]
			];

			$balance = $this->model_accounting_transaction->getTransactionsTotal($filter_summary_data);

			$data['vendors'][] = array(
				'vendor_id'    => $result['vendor_id'],
				'vendor_name'  => $result['vendor_name'],
				'vendor_type'  => $result['vendor_type'],
				'telephone'    => $result['telephone'],
				'email'        => $result['email'],
				'balance'      => $this->currency->format($balance, $this->config->get('config_currency')),
				'status'       => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'   => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'         => $this->url->link('catalog/vendor/edit', 'token=' . $this->session->data['token'] . '&vendor_id=' . $result['vendor_id'] . $url, true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_enabled',
			'text_disabled',
			'text_no_results',
			'text_confirm',
			'entry_vendor_name',
			'entry_vendor_type',
			'entry_status',
			'column_vendor_name',
			'column_vendor_type',
			'column_telephone',
			'column_email',
			'column_balance',
			'column_status',
			'column_date_added',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete',
			'button_filter'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_vendor_type_id'])) {
			$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_vendor_name'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=v.vendor_name' . $url, true);
		$data['sort_vendor_type'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=vendor_type' . $url, true);
		$data['sort_telephone'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=v.telephone' . $url, true);
		$data['sort_email'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=v.email' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=v.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=v.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_vendor_type_id'])) {
			$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $vendor_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendor_count - $this->config->get('config_limit_admin'))) ? $vendor_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendor_count, ceil($vendor_count / $this->config->get('config_limit_admin')));

		$data['filter_vendor_name'] = $filter_vendor_name;
		$data['filter_vendor_type_id'] = $filter_vendor_type_id;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('catalog/vendor_type');
		$data['vendor_types'] = $this->model_catalog_vendor_type->getVendorTypes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vendor_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['vendor_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_enabled',
			'text_disabled',
			'entry_vendor_name',
			'entry_vendor_type',
			'entry_telephone',
			'entry_email',
			'entry_website',
			'entry_address',
			'entry_contact_person',
			'entry_status',
			'button_save',
			'button_cancel',
			'tab_general',
			'tab_transaction'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = array(
			'warning',
			'vendor_name',
			'vendor_type',
			'telephone',
			'email',
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor_type_id'])) {
			$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
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
			'href' => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['vendor_id'])) {
			$data['action'] = $this->url->link('catalog/vendor/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/vendor/edit', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->get['vendor_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['vendor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$vendor_info = $this->model_catalog_vendor->getVendor($this->request->get['vendor_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['vendor_id'])) {
			$data['vendor_id'] = $this->request->get['vendor_id'];
		} else {
			$data['vendor_id'] = 0;
		}

		if (isset($this->request->post['vendor_name'])) {
			$data['vendor_name'] = $this->request->post['vendor_name'];
		} elseif (!empty($vendor_info)) {
			$data['vendor_name'] = $vendor_info['vendor_name'];
		} else {
			$data['vendor_name'] = '';
		}

		if (isset($this->request->post['vendor_type_id'])) {
			$data['vendor_type_id'] = $this->request->post['vendor_type_id'];
		} elseif (!empty($vendor_info)) {
			$data['vendor_type_id'] = $vendor_info['vendor_type_id'];
		} else {
			$data['vendor_type_id'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($vendor_info)) {
			$data['telephone'] = $vendor_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($vendor_info)) {
			$data['email'] = $vendor_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['website'])) {
			$data['website'] = $this->request->post['website'];
		} elseif (!empty($vendor_info)) {
			$data['website'] = $vendor_info['website'];
		} else {
			$data['website'] = '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($vendor_info)) {
			$data['address'] = $vendor_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['contact_person'])) {
			$data['contact_person'] = $this->request->post['contact_person'];
		} elseif (!empty($vendor_info)) {
			$data['contact_person'] = $vendor_info['contact_person'];
		} else {
			$data['contact_person'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($vendor_info)) {
			$data['status'] = $vendor_info['status'];
		} else {
			$data['status'] = true;
		}

		$this->load->model('catalog/vendor_type');
		$data['vendor_types'] = $this->model_catalog_vendor_type->getVendorTypes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vendor_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['vendor_name'])) < 1) || (utf8_strlen(trim($this->request->post['vendor_name'])) > 64)) {
			$this->error['vendor_name'] = $this->language->get('error_vendor_name');
		}

		if (!$this->request->post['vendor_type_id']) {
			$this->error['vendor_type'] = $this->language->get('error_vendor_type');
		}

		if ($this->request->post['email']) {
			if ((utf8_strlen($this->request->post['email']) > 96) || (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL))) {
				$this->error['email'] = $this->language->get('error_email');
			}

			$vendor_info = $this->model_catalog_vendor->getVendorByEmail($this->request->post['email']);

			if (!isset($this->request->get['vendor_id'])) {
				if ($vendor_info) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			} else {
				if ($vendor_info && ($this->request->get['vendor_id'] != $vendor_info['vendor_id'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');
		$this->load->model('accounting/transaction');

		foreach ($this->request->post['selected'] as $vendor_id) {
			$order_count = $this->model_sale_order->getOrdersCountByVendorId($vendor_id);

			if ($order_count) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_count);
			}

			$transaction_count = $this->model_accounting_transaction->getTransactionsCountByClientLabel('vendor', $vendor_id);

			if ($transaction_count) {
				$this->error['warning'] = $this->language->get('error_transaction');
			}
		}

		return !$this->error;
	}

	public function transaction()
	{
		$this->load->language('catalog/vendor');

		$this->load->model('accounting/transaction');

		$language_items = array(
			'text_no_results',
			'text_balance',
			'column_date',
			'column_transaction_type',
			'column_payment_method',
			'column_description',
			'column_reference',
			'column_amount',
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

		$limit = 10;

		$data['transactions'] = array();

		$filter_data = [
			'filter'	=> [
				'client_label'	=> 'vendor',
				'client_id'		=> $this->request->get['vendor_id']
			],
			'start'	=> ($page - 1) * $limit,
			'limit'	=> $limit
		];

		$results = $this->model_accounting_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$reference = '#' . $result['order_id'] . ': ' . $result['reference'];
			} else {
				$reference = $result['reference'];
			}

			$amount = ($result['account_type'] == 'D' ? 1 : -1) * $result['amount'];

			$data['transactions'][] = array(
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'reference'   		=> $reference,
				'description'		=> $result['description'],
				'payment_method' 	=> $result['payment_method'],
				'amount'      		=> $this->currency->format($amount, $this->config->get('config_currency')),
				'username'      	=> $result['username'],
				'order_url'     	=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
			);
		}

		$data['balance'] = $this->currency->format($this->model_accounting_transaction->getTransactionsTotal($filter_data), $this->config->get('config_currency'));

		$transaction_count = $this->model_accounting_transaction->getTransactionsCount($filter_data);

		$pagination = new Pagination();
		$pagination->total = $transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('catalog/vendor/transaction', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->get['vendor_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_count - 10)) ? $transaction_count : ((($page - 1) * 10) + 10), $transaction_count, ceil($transaction_count / 10));

		$this->response->setOutput($this->load->view('catalog/vendor_transaction', $data));
	}
}
