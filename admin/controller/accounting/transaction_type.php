<?php
class ControllerAccountingTransactionType extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction_type->addTransactionType($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction_type->editTransactionType($this->request->get['transaction_type_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $transaction_type_id) {
				$this->model_accounting_transaction_type->deleteTransactionType($transaction_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_client_label',
			'column_category_label',
			'column_name',
			'column_account_credit',
			'column_account_debit',
			'column_sort_order',
			'column_transaction_label',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
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
			'href' => $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/transaction_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/transaction_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['transaction_types'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$transaction_type_count = $this->model_accounting_transaction_type->getTransactionTypesCount();

		$results = $this->model_accounting_transaction_type->getTransactionTypes($filter_data);

		foreach ($results as $result) {
			$transaction_type_account_data = [];

			$transaction_type_accounts = $this->model_accounting_transaction_type->getTransactionTypeAccounts($result['transaction_type_id']);
			
			foreach ($transaction_type_accounts as $value) {
				$transaction_type_account_data[] = [
					'account_label'		=> $value['account_label'],
					'account_debit'		=> $value['account_debit_id'] ? $value['account_debit_id'] . '-' . $value['account_debit'] : '-',
					'account_credit'	=> $value['account_credit_id'] ? $value['account_credit_id'] . '-' . $value['account_credit'] : '-'
				];
			}

			$data['transaction_types'][] = array(
				'transaction_type_id'   => $result['transaction_type_id'],
				'name'                  => $result['name'],
				'client_label'			=> $result['client_label'],
				'category_label'		=> $result['category_label'],
				'transaction_label'		=> $result['transaction_label'],
				'account_debit'			=> array_column($transaction_type_account_data, 'account_debit'),
				'account_credit'		=> array_column($transaction_type_account_data, 'account_credit'),
				'sort_order'            => $result['sort_order'],
				'edit'                  => $this->url->link('accounting/transaction_type/edit', 'token=' . $this->session->data['token'] . '&transaction_type_id=' . $result['transaction_type_id'] . $url, true)
			);
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_client_label'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=client_label' . $url, true);
		$data['sort_category_label'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=category_label' . $url, true);
		$data['sort_name'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_type_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_type_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($transaction_type_count - $this->config->get('config_limit_admin'))) ? $transaction_type_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $transaction_type_count, ceil($transaction_type_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_type_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['transaction_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_account',
			'text_none',
			'text_select',
			'text_enabled',
			'text_disabled',
			'entry_account_credit',
			'entry_account_debit',
			'entry_account_label',
			'entry_account_type',
			'entry_client_label',
			'entry_category_label',
			'entry_manual_select',
			'entry_name',
			'entry_sort_order',
			'entry_status',
			'entry_transaction_label',
			'column_action',
			'button_account_add',
			'button_remove',
			'button_save',
			'button_cancel',
			'help_manual_select'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$error_items = array(
			'warning',
			'client_label',
			'category_label',
			'transaction_label',
			'account_label',
			'account_type',
			'name'
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}

		$url = '';

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
			'href' => $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['transaction_type_id'])) {
			$data['action'] = $this->url->link('accounting/transaction_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/transaction_type/edit', 'token=' . $this->session->data['token'] . '&transaction_type_id=' . $this->request->get['transaction_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['transaction_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->get['transaction_type_id']);
		}

		$input_items = array(
			'name',
			'client_label',
			'category_label',
			'transaction_label',
			'account_type',
			'manual_select',
			'sort_order',
			'status'
		);
		foreach ($input_items as $input_item) {
			if (isset($this->request->post[$input_item])) {
				$data[$input_item] = $this->request->post[$input_item];
			} elseif (!empty($transaction_type_info)) {
				$data[$input_item] = $transaction_type_info[$input_item];
			} else {
				$data[$input_item] = '';
			}
		}

		if (isset($this->request->post['transaction_type_account'])) {
			$data['transaction_type_accounts'] = $this->request->post['transaction_type_account'];
		} elseif (isset($this->request->get['transaction_type_id'])) {
			$data['transaction_type_accounts'] = $this->model_accounting_transaction_type->getTransactionTypeAccounts($this->request->get['transaction_type_id']);
		} else {
			$data['transaction_type_accounts'] = array();
		}

		$data['transaction_type_accounts_idx'] = ($data['transaction_type_accounts'] ? max(array_keys($data['transaction_type_accounts'])) + 1 : 0);

		$data['clients_label'] = $this->model_accounting_transaction_type->getClientsLabel();
		$data['categories_label'] = $this->model_accounting_transaction_type->getCategoriesLabel();
		$data['transactions_label'] = $this->model_accounting_transaction_type->getTransactionsLabel();
		$data['accounts_type'] = $this->model_accounting_transaction_type->getAccountsType();

		$this->load->model('accounting/account');
		$data['accounts'] = $this->model_accounting_account->getAccountsMenuByComponent();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_type_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'accounting/transaction_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['client_label'])) {
			$this->error['client_label'] = $this->language->get('error_client_label');
		}

		if (empty($this->request->post['category_label'])) {
			$this->error['category_label'] = $this->language->get('error_category_label');
		}

		if (empty($this->request->post['transaction_label'])) {
			$this->error['transaction_label'] = $this->language->get('error_transaction_label');
		}

		if (empty($this->request->post['account_type'])) {
			$this->error['account_type'] = $this->language->get('error_account_type');
		}

		if ((utf8_strlen(trim($this->request->post['name'])) < 3) || (utf8_strlen(trim($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!isset($this->request->post['transaction_type_account'])) {
			$this->error['warning'] = $this->language->get('error_account');
		} else {
			foreach ($this->request->post['transaction_type_account'] as $transaction_type_account) {
				if (!$transaction_type_account['account_label']) {
					$this->error['warning'] = $this->language->get('error_account_label');
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
		if (!$this->user->hasPermission('modify', 'accounting/transaction_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('accounting/transaction');

		foreach ($this->request->post['selected'] as $transaction_type_id) {
			$transaction_count = $this->model_accounting_transaction->getTransactionsCountByTransactionTypeId($transaction_type_id);

			if ($transaction_count) {
				$this->error['warning'] = sprintf($this->language->get('error_transaction'), $transaction_count);
			}
		}

		return !$this->error;
	}
}
