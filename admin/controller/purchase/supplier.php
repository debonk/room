<?php
class ControllerPurchaseSupplier extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		$this->getList();
	}

	public function add() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_supplier->addSupplier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_supplier_name'])) {
				$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_supplier_name'])) {
				$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_purchase_supplier->deleteSupplier($supplier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_supplier_name'])) {
				$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_vendor_type_id'])) {
				$url .= '&filter_vendor_type_id=' . $this->request->get['filter_vendor_type_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_supplier_name'])) {
			$filter_supplier_name = $this->request->get['filter_supplier_name'];
		} else {
			$filter_supplier_name = null;
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
			$sort = 's.supplier_name';
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

		if (isset($this->request->get['filter_supplier_name'])) {
			$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchase/supplier/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('purchase/supplier/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['suppliers'] = array();

		$filter_data = array(
			'filter_supplier_name'	=> $filter_supplier_name,
			'filter_vendor_type_id'	=> $filter_vendor_type_id,
			'filter_status'     	=> $filter_status,
			'sort'             		=> $sort,
			'order'            		=> $order,
			'start'            		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'            		=> $this->config->get('config_limit_admin')
		);

		$supplier_count = $this->model_purchase_supplier->getSuppliersCount($filter_data);

		$results = $this->model_purchase_supplier->getSuppliers($filter_data);

		$this->load->model('accounting/transaction');
		
		foreach ($results as $result) {
			$balance = $this->model_accounting_transaction->getTransactionsTotalByLabel('supplier', $result['supplier_id']);

			$data['suppliers'][] = array(
				'supplier_id'   => $result['supplier_id'],
				'supplier_name' => $result['supplier_name'],
				'vendor_type'   => $result['vendor_type_id'] ? $result['vendor_type'] : '-',
				'telephone'     => $result['telephone'],
				'email'         => $result['email'],
				'balance'       => $this->currency->format($balance, $this->config->get('config_currency')),
				'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'          => $this->url->link('purchase/supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, true)
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
			'text_not_vendor',
			'entry_supplier_name',
			'entry_vendor_type',
			'entry_status',
			'column_supplier_name',
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

		if (isset($this->request->get['filter_supplier_name'])) {
			$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
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

		$data['sort_supplier_name'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=v.supplier_name' . $url, true);
		$data['sort_vendor_type'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=vendor_type' . $url, true);
		$data['sort_telephone'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=v.telephone' . $url, true);
		$data['sort_email'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=v.email' . $url, true);
		$data['sort_status'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=v.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=v.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_supplier_name'])) {
			$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->total = $supplier_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($supplier_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($supplier_count - $this->config->get('config_limit_admin'))) ? $supplier_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $supplier_count, ceil($supplier_count / $this->config->get('config_limit_admin')));

		$data['filter_supplier_name'] = $filter_supplier_name;
		$data['filter_vendor_type_id'] = $filter_vendor_type_id;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('catalog/vendor_type');
		$data['vendor_types'] = $this->model_catalog_vendor_type->getVendorTypes();

		array_unshift($data['vendor_types'], ['vendor_type_id' => 0, 'name' => $this->language->get('text_not_vendor')]);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/supplier_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['supplier_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_enabled',
			'text_disabled',
			'text_not_vendor',
			'entry_supplier_name',
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
			'supplier_name',
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

		if (isset($this->request->get['filter_supplier_name'])) {
			$url .= '&filter_supplier_name=' . urlencode(html_entity_decode($this->request->get['filter_supplier_name'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['supplier_id'])) {
			$data['action'] = $this->url->link('purchase/supplier/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('purchase/supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier_info = $this->model_purchase_supplier->getSupplier($this->request->get['supplier_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['supplier_id'])) {
			$data['supplier_id'] = $this->request->get['supplier_id'];
		} else {
			$data['supplier_id'] = 0;
		}

		if (isset($this->request->post['supplier_name'])) {
			$data['supplier_name'] = $this->request->post['supplier_name'];
		} elseif (!empty($supplier_info)) {
			$data['supplier_name'] = $supplier_info['supplier_name'];
		} else {
			$data['supplier_name'] = '';
		}

		if (isset($this->request->post['vendor_type_id'])) {
			$data['vendor_type_id'] = $this->request->post['vendor_type_id'];
		} elseif (!empty($supplier_info)) {
			$data['vendor_type_id'] = $supplier_info['vendor_type_id'];
		} else {
			$data['vendor_type_id'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($supplier_info)) {
			$data['telephone'] = $supplier_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($supplier_info)) {
			$data['email'] = $supplier_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['website'])) {
			$data['website'] = $this->request->post['website'];
		} elseif (!empty($supplier_info)) {
			$data['website'] = $supplier_info['website'];
		} else {
			$data['website'] = '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($supplier_info)) {
			$data['address'] = $supplier_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['contact_person'])) {
			$data['contact_person'] = $this->request->post['contact_person'];
		} elseif (!empty($supplier_info)) {
			$data['contact_person'] = $supplier_info['contact_person'];
		} else {
			$data['contact_person'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($supplier_info)) {
			$data['status'] = $supplier_info['status'];
		} else {
			$data['status'] = true;
		}

		$this->load->model('catalog/vendor_type');
		$data['vendor_types'] = $this->model_catalog_vendor_type->getVendorTypes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/supplier_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['supplier_name'])) < 1) || (utf8_strlen(trim($this->request->post['supplier_name'])) > 64)) {
			$this->error['supplier_name'] = $this->language->get('error_supplier_name');
		}

		if ($this->request->post['email']) {
			if ((utf8_strlen($this->request->post['email'] ) > 96) || (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL))) {
				$this->error['email'] = $this->language->get('error_email');
			}

			$supplier_info = $this->model_purchase_supplier->getSupplierByEmail($this->request->post['email']);

			if (!isset($this->request->get['supplier_id'])) {
				if ($supplier_info) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			} else {
				if ($supplier_info && ($this->request->get['supplier_id'] != $supplier_info['supplier_id'])) {
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

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');
		$this->load->model('accounting/transaction');

		foreach ($this->request->post['selected'] as $supplier_id) {
			$order_count = $this->model_sale_order->getOrdersCountBySupplierId($supplier_id);

			if ($order_count) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_count);
			}
			
			$transaction_count = $this->model_accounting_transaction->getTransactionsCountBySupplierId($supplier_id);

			if ($transaction_count) {
				$this->error['warning'] = $this->language->get('error_transaction');
			}
		}

		return !$this->error;
	}

	public function transaction() {
		$this->load->language('purchase/supplier');

		$this->load->model('accounting/transaction');

		$language_items = array(
			'text_no_results',
			'text_balance',
			'column_date',
			'column_payment_method',
			'column_description',
			'column_invoice_no',
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

		$results = $this->model_accounting_transaction->getTransactionsByLabel('supplier', $this->request->get['supplier_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$invoice_no = '#' . $result['order_id'] . ($result['invoice_no'] ? ': ' . $result['invoice_prefix'] . str_pad($result['invoice_no'],4,0,STR_PAD_LEFT) : '');
			} else {
				$invoice_no = '';
			}
			
			$data['transactions'][] = array(
				'date'	 		=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'payment_method'=> $result['payment_method'],
				'description'	=> $result['description'],
				'invoice_no'   	=> $invoice_no,
				'amount'      	=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'username'      => $result['username'],
				'order_url'     => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
			);
		}

		$data['balance'] = $this->currency->format($this->model_accounting_transaction->getTransactionsTotalByLabel('supplier', $this->request->get['supplier_id']), $this->config->get('config_currency'));

		$transaction_total = $this->model_accounting_transaction->getTransactionsCountByLabel('supplier', $this->request->get['supplier_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('purchase/supplier/transaction', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_total - 10)) ? $transaction_total : ((($page - 1) * 10) + 10), $transaction_total, ceil($transaction_total / 10));

		$this->response->setOutput($this->load->view('purchase/supplier_transaction', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('purchase/supplier');

			$filter_data = array(
				'filter_supplier_name' 	=> $this->request->get['filter_name'],
				'start'       			=> 0,
				'limit'       			=> 0
			);

			$results = $this->model_purchase_supplier->getSuppliers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'supplier_id' 	=> $result['supplier_id'],
					'supplier_name' => strip_tags(html_entity_decode($result['supplier_name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['supplier_name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
