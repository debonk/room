<?php
class ControllerAccountingTransaction extends Controller
{
	private $error = array();

	private $filter_items = array(
		'date_start',
		'date_end',
		'account_id',
		'transaction_type_id',
		'description',
		'reference',
		'order_id',
		'customer_name',
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
		$this->load->language('accounting/transaction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('accounting/transaction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction->addTransaction($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('accounting/transaction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction->editTransaction($this->request->get['transaction_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('accounting/transaction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $transaction_id) {
				$this->model_accounting_transaction->deleteTransaction($transaction_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_confirm',
			'text_total',
			'text_no_results',
			'text_success',
			'text_select',
			'text_none',
			'column_date',
			'column_account_credit',
			'column_account_debit',
			'column_description',
			'column_reference',
			'column_customer_name',
			'column_amount',
			'column_transaction_type',
			'column_username',
			'column_action',
			'entry_date_start',
			'entry_date_end',
			'entry_account',
			'entry_description',
			'entry_reference',
			'entry_order_id',
			'entry_customer_name',
			'entry_transaction_type',
			'entry_username',
			'button_filter',
			'button_add',
			'button_edit',
			'button_edit_lock',
			'button_edit_unlock',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (empty($filter['date_start'])) {
			$filter['date_start'] = date('Y-m-d', strtotime(date('Y') . '-01-01'));
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 't.date';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/transaction/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/transaction/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['transactions'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter'	=> $filter,
			'sort'      => $sort,
			'order'     => $order,
			'start'     => ($page - 1) * $limit,
			'limit'     => $limit
		);

		$transaction_count = $this->model_accounting_transaction->getTransactionsCount($filter_data);
		$transaction_total = $this->model_accounting_transaction->getTransactionsTotal($filter_data);

		$results = $this->model_accounting_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$reference = '#' . $result['order_id'] . ($result['reference_no'] ? ': ' . $result['reference'] : '');
				$order_url = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true);
			} else {
				$reference = $result['reference'];
				$order_url = '';
			}

			$account_data = [
				'debit'		=> [],
				'credit'	=> []
			];

			$transaction_accounts = $this->model_accounting_transaction->getTransactionAccounts($result['transaction_id']);
			foreach ($transaction_accounts as $transaction_account) {
				if ($transaction_account['debit'] > 0 || $transaction_account['credit'] < 0) {
					$account_data['debit'][] = $transaction_account['account_id'] . ' - ' . $transaction_account['account'];
				} else {
					$account_data['credit'][] = $transaction_account['account_id'] . ' - ' . $transaction_account['account'];
				}
			}

			if (empty($account_data['debit']) || empty($account_data['credit']) || (array_sum(array_column($transaction_accounts, 'debit')) != array_sum(array_column($transaction_accounts, 'credit')))) {
				$uncomplete = true;
			} else {
				$uncomplete = false;
			}

			$data['transactions'][] = array(
				'transaction_id' => $result['transaction_id'],
				'date'	 		=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'reference'  	=> $reference,
				'description'	=> $result['description'],
				'customer_name'	=> $result['customer_name'],
				'account'		=> $account_data,
				'amount'      	=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'username'      => $result['username'],
				'order_url'     => $order_url,
				'unlock'		=> $result['edit_permission'],
				'edit'          => $this->url->link('accounting/transaction/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true),
				'uncomplete'    => $uncomplete
			);
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

		$data['sort_date'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=t.date' . $url, true);
		$data['sort_transaction_type'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=transaction_type' . $url, true);
		$data['sort_description'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=t.description' . $url, true);
		$data['sort_reference'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=reference' . $url, true);
		$data['sort_customer_name'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=t.customer_name' . $url, true);
		$data['sort_amount'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=t.amount' . $url, true);
		$data['sort_username'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_count - $limit)) ? $transaction_count : ((($page - 1) * $limit) + $limit), $transaction_count, ceil($transaction_count / $limit));

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/transaction_type');
		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypes();

		$this->load->model('accounting/account');
		$data['accounts'] = $this->model_accounting_account->getAccountsMenuByComponent();

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['total'] = $this->currency->format($transaction_total, $this->config->get('config_currency'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['transaction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_account',
			'text_none',
			'text_select',
			'text_reference',
			'entry_account',
			'entry_credit',
			'entry_date',
			'entry_debit',
			'entry_description',
			'entry_amount',
			'entry_customer_name',
			'entry_reference',
			'entry_transaction_type',
			'column_action',
			'button_account_add',
			'button_remove',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = array(
			'warning',
			'transaction_type',
			'date',
			'description',
			'amount',
			'account'
		);
		foreach ($error_items as $error_item) {
			$data['error_' . $error_item] = isset($this->error[$error_item]) ? $this->error[$error_item] : '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['transaction_id'])) {
			$data['action'] = $this->url->link('accounting/transaction/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/transaction/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('accounting/transaction', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['transaction_id'])) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->get['transaction_id']);
		}

		$input_items = array(
			'transaction_type_id',
			'date',
			'description',
			'amount',
			'customer_name'
		);
		foreach ($input_items as $input_item) {
			if (isset($this->request->post[$input_item])) {
				$data[$input_item] = $this->request->post[$input_item];
			} elseif (!empty($transaction_info)) {
				$data[$input_item] = $transaction_info[$input_item];
			} else {
				$data[$input_item] = '';
			}
		}

		if (isset($this->request->post['transaction_account'])) {
			$data['transaction_accounts'] = $this->request->post['transaction_account'];
		} elseif (isset($this->request->get['transaction_id'])) {
			$data['transaction_accounts'] = $this->model_accounting_transaction->getTransactionAccounts($this->request->get['transaction_id']);
		} else {
			$data['transaction_accounts'] = array();
		}

		$data['transaction_accounts_idx'] = ($data['transaction_accounts'] ? max(array_keys($data['transaction_accounts'])) + 1 : 0);

		if (!empty($transaction_info)) {
			$data['reference'] = $transaction_info['reference'];
			$data['order_id'] = $transaction_info['order_id'];

			if ($transaction_info['order_id']) {
				$data['transaction_type'] = $transaction_info['transaction_type'];
			}
		} else {
			$data['reference'] = '-';
			$data['order_id'] = 0;
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/transaction_type');
		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesMenu(['client_label' => 'finance']);
		// $data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypes();

		$this->load->model('accounting/account');
		$data['accounts'] = $this->model_accounting_account->getAccountsMenuByComponent();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'accounting/transaction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['transaction_type_id']) && empty($this->request->post['transaction_type_id'])) {
			$this->error['transaction_type'] = $this->language->get('error_transaction_type');
		}

		if (isset($this->request->post['date']) && empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if (isset($this->request->post['description']) && ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen($this->request->post['description']) > 256))) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (isset($this->request->post['amount']) && (float)$this->request->post['amount'] <= 0) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		if (!isset($this->request->post['transaction_account'])) {
			$this->error['warning'] = $this->language->get('error_account');
		} else {
			foreach ($this->request->post['transaction_account'] as $transaction_account) {
				if (!$transaction_account['account_id']) {
					$this->error['warning'] = $this->language->get('error_account');
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'accounting/transaction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('accounting/transaction');
		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $transaction_id) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($transaction_id);

			if ($transaction_info['order_id']) {
				$order_status_id = $this->model_sale_order->getOrderStatusId($transaction_info['order_id']);
				
				if (in_array($order_status_id, $this->config->get('config_complete_status'))) {
					$this->error['warning'] = $this->language->get('error_order_status');
				}
			}
		}

		return !$this->error;
	}

	public function editPermission()
	{
		$this->load->language('accounting/transaction');

		$json = array();

		if (!$this->user->hasPermission('modify', 'accounting/transaction')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('accounting/transaction');
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->post['transaction_id']);

			if (!$transaction_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($transaction_info['order_id']) {
				$json['error'] = $this->language->get('error_order_permission');
			}
		}

		if (!$json) {
			if ($transaction_info['edit_permission']) {
				$set_permission = 0;
			} else {
				$set_permission = 1;
			}

			$this->model_accounting_transaction->editEditPermission($this->request->post['transaction_id'], $set_permission);

			$json['success'] = $this->language->get('text_success');
			$json['unlock_status'] = $set_permission;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
