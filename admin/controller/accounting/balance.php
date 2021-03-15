<?php
class ControllerAccountingBalance extends Controller
{
	private $error = array();

	private $filter_items = array(
		'date_start',
		'date_end',
		'transaction_type_id',
		'account_id',
		'reference',
		'description',
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
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->request->post['transaction_account'][] = [
				'account_id'	=> $this->request->post['account_debit_id'],
				'debit'			=> $this->request->post['amount'],
				'credit'		=> 0
			];
			$this->request->post['transaction_account'][] = [
				'account_id'	=> $this->request->post['account_credit_id'],
				'debit'			=> 0,
				'credit'		=> $this->request->post['amount']
			];

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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->request->post['transaction_account'][] = [
				'account_id'	=> $this->request->post['account_debit_id'],
				'debit'			=> $this->request->post['amount'],
				'credit'		=> 0
			];
			$this->request->post['transaction_account'][] = [
				'account_id'	=> $this->request->post['account_credit_id'],
				'debit'			=> 0,
				'credit'		=> $this->request->post['amount']
			];

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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('accounting/balance');

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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_all',
			'text_confirm',
			'text_list',
			'text_no_results',
			'text_none',
			'text_select',
			'text_success',
			'text_total',
			'column_reference',
			'column_date',
			'column_account_credit',
			'column_account_debit',
			'column_description',
			'column_customer_name',
			'column_amount',
			'column_username',
			'column_action',
			'entry_account',
			'entry_date_start',
			'entry_date_end',
			'entry_reference',
			'entry_description',
			'entry_customer_name',
			'entry_transaction_type',
			'entry_username',
			'button_filter',
			'button_add',
			'button_edit',
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

		if (is_null($filter['date_start'])) {
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
			'href' => $this->url->link('accounting/balance', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/balance/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/balance/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['transactions'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter['category_label'] = 'asset';

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
				'debit'		=> '',
				'credit'	=> ''
			];

			$transaction_accounts = $this->model_accounting_transaction->getTransactionAccounts($result['transaction_id']);
			foreach ($transaction_accounts as $transaction_account) {
				if ($transaction_account['debit'] > 0 || $transaction_account['credit'] < 0) {
					$account_data['debit'] = $transaction_account['account_id'] . ' - ' . $transaction_account['account'];
				} else {
					$account_data['credit'] = $transaction_account['account_id'] . ' - ' . $transaction_account['account'];
				}
			}

			$data['transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'account_debit'		=> $account_data['debit'],
				'account_credit'	=> $account_data['credit'],
				'description'		=> $result['description'],
				'reference'  		=> $reference,
				'customer_name'		=> $result['customer_name'],
				'amount'      		=> $this->currency->format(abs($result['amount']), $this->config->get('config_currency')),
				'username'      	=> $result['username'],
				'order_url'     	=> $order_url,
				'edit'          	=> $this->url->link('accounting/balance/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true)
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

		$data['sort_date'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.date' . $url, true);
		$data['sort_description'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.description' . $url, true);
		$data['sort_reference'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=reference' . $url, true);
		$data['sort_customer_name'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.customer_name' . $url, true);
		$data['sort_amount'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.amount' . $url, true);
		$data['sort_username'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);

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
		$pagination->url = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_count - $limit)) ? $transaction_count : ((($page - 1) * $limit) + $limit), $transaction_count, ceil($transaction_count / $limit));

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/transaction_type');
		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesMenu(['category_label' => 'asset']);

		$this->load->model('accounting/account');
		$data['accounts'] = $this->model_accounting_account->getAccountsMenuByParentId([111, 114, 711]);

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['total'] = $this->currency->format($transaction_total, $this->config->get('config_currency'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/balance_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['transaction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_none',
			'text_select',
			'text_reference',
			'entry_account_credit',
			'entry_account_debit',
			'entry_date',
			'entry_description',
			'entry_amount',
			'entry_customer_name',
			'entry_transaction_type',
			'button_save',
			'button_cancel',
			'help_amount'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = array(
			'warning',
			'account_debit',
			'account_credit',
			'date',
			'description',
			'amount'
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
			'href' => $this->url->link('accounting/balance', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['transaction_id'])) {
			$data['action'] = $this->url->link('accounting/balance/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/balance/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['transaction_id'])) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->get['transaction_id']);

			$transaction_accounts = $this->model_accounting_transaction->getTransactionAccounts($this->request->get['transaction_id']);

			foreach ($transaction_accounts as $transaction_account) {
				if ($transaction_account['debit'] > 0 || $transaction_account['credit'] < 0) {
					$account_debit_id = $transaction_account['account_id'];
				} else {
					$account_credit_id = $transaction_account['account_id'];
				}
			}
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
				$data[$input_item] = null;
			}
		}

		if (isset($this->request->post['account_debit_id'])) {
			$data['account_debit_id'] = $this->request->post['account_debit_id'];
		} elseif (!empty($transaction_info)) {
			$data['account_debit_id'] = $account_debit_id;
		} else {
			$data['account_debit_id'] = null;
		}

		if (isset($this->request->post['account_credit_id'])) {
			$data['account_credit_id'] = $this->request->post['account_credit_id'];
		} elseif (!empty($transaction_info)) {
			$data['account_credit_id'] = $account_credit_id;
		} else {
			$data['account_credit_id'] = null;
		}

		$data['reference'] = !empty($transaction_info) ? $transaction_info['reference'] : '-';

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/transaction_type');
		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesMenu(['category_label' => 'asset']);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/balance_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'accounting/balance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['account_debit_id'])) {
			$this->error['account_debit'] = $this->language->get('error_account_debit');
		}

		if (empty($this->request->post['account_credit_id'])) {
			$this->error['account_credit'] = $this->language->get('error_account_credit');
		}

		if (empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen($this->request->post['description']) > 256)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if ((float)$this->request->post['amount'] <= 0) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		if (isset($this->request->get['transaction_id'])) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->get['transaction_id']);

			if (!$transaction_info || !$transaction_info['edit_permission']) {
				$this->error['warning'] = $this->language->get('error_edit');
			} elseif ($transaction_info['order_id']) {
				$this->error['warning'] = $this->language->get('error_order');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'accounting/balance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $transaction_id) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($transaction_id);

			if (!$transaction_info || !$transaction_info['edit_permission']) {
				$this->error['warning'] = $this->language->get('error_permission');

				break;
			} elseif ($transaction_info['order_id']) {
				$this->error['warning'] = $this->language->get('error_order');

				break;
			}
		}

		return !$this->error;
	}

	public function transactionTypeAccounts()
	{
		$json = [];

		$this->load->model('accounting/transaction_type');
		$this->load->model('accounting/account');
		$transaction_type_accounts = $this->model_accounting_transaction_type->getTransactionTypeAccounts($this->request->get['transaction_type_id']);

		$accounts_debit_id = array_column($transaction_type_accounts, 'account_debit_id');
		$accounts_credit_id = array_column($transaction_type_accounts, 'account_credit_id');

		if (empty(array_sum($accounts_debit_id))) {
			$accounts_debit_id = [111, 114];
		}

		if (empty(array_sum($accounts_credit_id))) {
			$accounts_credit_id = [111, 114];
		}

		$json['account_debit'] = array_values($this->model_accounting_account->getAccountsMenuByParentId($accounts_debit_id));
		$json['account_credit'] = array_values($this->model_accounting_account->getAccountsMenuByParentId($accounts_credit_id));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
