<?php
class ControllerAccountingBalance extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		$this->getList();
	}

	public function add() {
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->request->post['label'] = 'balance';
			$this->request->post['reference_no'] = 'B' . date('ym');
			$this->request->post['transaction_no'] = $this->model_accounting_transaction->getTransactionNoMax($this->request->post['reference_no']) + 1;
			
			$this->model_accounting_transaction->addTransaction($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['filter_account_from_id'])) {
				$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
			}

			if (isset($this->request->get['filter_account_to_id'])) {
				$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_reference_no'])) {
				$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
			}

			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
			}

			if (isset($this->request->get['filter_username'])) {
				$url .= '&filter_username=' . $this->request->get['filter_username'];
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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function edit() {
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_accounting_transaction->editTransaction($this->request->get['transaction_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['filter_account_from_id'])) {
				$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
			}

			if (isset($this->request->get['filter_account_to_id'])) {
				$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_reference_no'])) {
				$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
			}

			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
			}

			if (isset($this->request->get['filter_username'])) {
				$url .= '&filter_username=' . $this->request->get['filter_username'];
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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('accounting/balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $transaction_id) {
				$this->model_accounting_transaction->deleteTransaction($transaction_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['filter_account_from_id'])) {
				$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
			}

			if (isset($this->request->get['filter_account_to_id'])) {
				$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}

			if (isset($this->request->get['filter_reference_no'])) {
				$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
			}

			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
			}

			if (isset($this->request->get['filter_username'])) {
				$url .= '&filter_username=' . $this->request->get['filter_username'];
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

			$this->response->redirect($this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_confirm',
			'text_total',
			'text_no_results',
			'text_success',
			'text_select',
			'column_reference_no',
			'column_date',
			'column_asset_from',
			'column_asset_to',
			'column_description',
			'column_customer_name',
			'column_amount',
			'column_username',
			'column_action',
			'entry_date_start',
			'entry_date_end',
			'entry_asset_from',
			'entry_asset_to',
			'entry_reference_no',
			'entry_description',
			'entry_customer_name',
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

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}

		if (isset($this->request->get['filter_account_from_id'])) {
			$filter_account_from_id = $this->request->get['filter_account_from_id'];
		} else {
			$filter_account_from_id = '';
		}

		if (isset($this->request->get['filter_account_to_id'])) {
			$filter_account_to_id = $this->request->get['filter_account_to_id'];
		} else {
			$filter_account_to_id = '';
		}

		if (isset($this->request->get['filter_description'])) {
			$filter_description = $this->request->get['filter_description'];
		} else {
			$filter_description = '';
		}

		if (isset($this->request->get['filter_reference_no'])) {
			$filter_reference_no = $this->request->get['filter_reference_no'];
		} else {
			$filter_reference_no = '';
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->get['filter_username'])) {
			$filter_username = $this->request->get['filter_username'];
		} else {
			$filter_username = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 't.date';
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_from_id'])) {
			$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
		}

		if (isset($this->request->get['filter_account_to_id'])) {
			$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}

		if (isset($this->request->get['filter_reference_no'])) {
			$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
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
			'href' => $this->url->link('accounting/balance', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/balance/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/balance/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['transactions'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter_label'	 		 => 'balance',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_account_from_id' => $filter_account_from_id,
			'filter_account_to_id'	 => $filter_account_to_id,
			'filter_description'	 => $filter_description,
			'filter_reference_no'	 => $filter_reference_no,
			'filter_customer_name'	 => $filter_customer_name,
			'filter_username'	 	 => $filter_username,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $limit,
			'limit'                  => $limit
		);

		$transaction_count = $this->model_accounting_transaction->getTransactionsCount($filter_data);
		$transaction_total = $this->model_accounting_transaction->getTransactionsTotal($filter_data);

		$results = $this->model_accounting_transaction->getTransactions($filter_data);
		
		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$reference_no = '#' . $result['order_id'] . ($result['transaction_no'] ? ': ' . $result['reference'] : '');
				$order_url = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true);
			} else {
				$reference_no = $result['reference'];
				$order_url = '';
			}
			
			$data['transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'account_from'		=> $result['account_from'],
				'account_to'		=> $result['account_to'],
				'description'		=> $result['description'],
				'reference_no'  	=> $reference_no,
				'customer_name'		=> $result['customer_name'],
				'amount'      		=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'username'      	=> $result['username'],
				'order_url'     	=> $order_url,
				'edit'          	=> $this->url->link('accounting/balance/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true)
			);
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_from_id'])) {
			$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
		}

		if (isset($this->request->get['filter_account_to_id'])) {
			$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}

		if (isset($this->request->get['filter_reference_no'])) {
			$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.date' . $url, true);
		$data['sort_account_from'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=account_from' . $url, true);
		$data['sort_account_to'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=account_to' . $url, true);
		$data['sort_description'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.description' . $url, true);
		$data['sort_reference'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=reference' . $url, true);
		$data['sort_customer_name'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.customer_name' . $url, true);
		$data['sort_amount'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=t.amount' . $url, true);
		$data['sort_username'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_from_id'])) {
			$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
		}

		if (isset($this->request->get['filter_account_to_id'])) {
			$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}

		if (isset($this->request->get['filter_reference_no'])) {
			$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
		}

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

		$this->load->model('accounting/account');
		$data['accounts_from'] = $this->model_accounting_account->getAccountsMenuByComponent('asset');
		$data['accounts_to'] = $this->model_accounting_account->getAccountsMenuByComponent('asset');
		
		$data['filter_account_from_id'] = $filter_account_from_id;
		$data['filter_account_to_id'] = $filter_account_to_id;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_description'] = $filter_description;
		$data['filter_reference_no'] = $filter_reference_no;
		$data['filter_customer_name'] = $filter_customer_name;
		$data['filter_username'] = $filter_username;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['total'] = $this->currency->format($transaction_total, $this->config->get('config_currency'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/balance_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['transaction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_reference_no',
			'entry_asset_from',
			'entry_asset_to',
			'entry_date',
			'entry_description',
			'entry_amount',
			'entry_customer_name',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$error_items = array(
			'warning',
			'account_from',
			'account_to',
			'date',
			'description',
			'amount'
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_from_id'])) {
			$url .= '&filter_account_from_id=' . $this->request->get['filter_account_from_id'];
		}

		if (isset($this->request->get['filter_account_to_id'])) {
			$url .= '&filter_account_to_id=' . $this->request->get['filter_account_to_id'];
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . $this->request->get['filter_description'];
		}

		if (isset($this->request->get['filter_reference_no'])) {
			$url .= '&filter_reference_no=' . $this->request->get['filter_reference_no'];
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
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
			'href' => $this->url->link('accounting/balance', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['transaction_id'])) {
			$data['action'] = $this->url->link('accounting/balance/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/balance/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('accounting/balance', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['transaction_id'])) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->get['transaction_id']);
		}
		
		if (!empty($transaction_info)) {
			$data['reference_no'] = $transaction_info['reference'];
		} else {
			$data['reference_no'] = '-';
		}

		if (isset($this->request->post['account_from_id'])) {
			$data['account_from_id'] = $this->request->post['account_from_id'];
		} elseif (!empty($transaction_info)) {
			$data['account_from_id'] = $transaction_info['account_from_id'];
		} else {
			$data['account_from_id'] = '';
		}

		if (isset($this->request->post['account_to_id'])) {
			$data['account_to_id'] = $this->request->post['account_to_id'];
		} elseif (!empty($transaction_info)) {
			$data['account_to_id'] = $transaction_info['account_to_id'];
		} else {
			$data['account_to_id'] = '';
		}

		if (isset($this->request->post['date'])) {
			$data['date'] = $this->request->post['date'];
		} elseif (!empty($transaction_info)) {
			$data['date'] = $transaction_info['date'];
		} else {
			$data['date'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($transaction_info)) {
			$data['description'] = $transaction_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} elseif (!empty($transaction_info)) {
			$data['amount'] = $transaction_info['amount'];
		} else {
			$data['amount'] = '';
		}

		if (isset($this->request->post['customer_name'])) {
			$data['customer_name'] = $this->request->post['customer_name'];
		} elseif (!empty($transaction_info)) {
			$data['customer_name'] = $transaction_info['customer_name'];
		} else {
			$data['customer_name'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/account');
		$data['accounts_from'] = $this->model_accounting_account->getAccountsMenuByComponent('asset');
		$data['accounts_to'] = $this->model_accounting_account->getAccountsMenuByComponent('asset');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/balance_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/balance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['account_from_id'])) {
			$this->error['account_from'] = $this->language->get('error_account_from');
		}

		if (empty($this->request->post['account_to_id'])) {
			$this->error['account_to'] = $this->language->get('error_account_to');
		}

		if (empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen($this->request->post['description']) > 256)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty((float)$this->request->post['amount'])) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		if (isset($this->request->get['transaction_id'])) {
			$transaction_info = $this->model_accounting_transaction->getTransaction($this->request->get['transaction_id']);
			
			if (!$transaction_info || !$transaction_info['edit_permission']) {
				$this->error['warning'] = $this->language->get('error_permission');
			} elseif ($transaction_info['order_id']) {
				$this->error['warning'] = $this->language->get('error_order');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
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
}
