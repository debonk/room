<?php
class ControllerSaleCustomer extends Controller
{
	public function index()
	{
		$this->load->language('sale/customer');

		$language_items = array(
			'text_no_results',
			'text_customer',
			'text_customer_transaction',
			'text_customer_transaction_add',
			'text_loading',
			'text_print_confirm',
			'text_select',
			'column_action',
			'column_amount',
			'column_balance',
			'column_credit',
			'column_date',
			'column_date_added',
			'column_debit',
			'column_description',
			'column_payment_method',
			'column_reference',
			'column_transaction_type',
			'column_username',
			'entry_account_credit',
			'entry_account_debit',
			'entry_amount',
			'entry_date',
			'entry_description',
			'entry_transaction_type',
			'entry_username',
			'help_amount',
			'button_print',
			'button_transaction_add',
			'button_view'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$this->load->model('sale/order');
		$this->load->model('accounting/transaction');

		$order_info = $this->model_sale_order->getOrder($order_id);

		$data['customers_transaction_summary'] = [];

		$filter_customer_data = [
			'client_label'	=> 'customer',
			'group'			=> 'category_label'
		];

		$transactions_summary = $this->model_accounting_transaction->getTransactionsSummaryGroupByLabel($order_id, $filter_customer_data);

		if ($this->config->get('config_customer_deposit') && !isset($transactions_summary['deposit'])) {
			$transactions_summary['deposit'] = [];
		}

		foreach ($transactions_summary as $key => $transaction_summary) {
			$text_category = $this->language->get('text_category_' . $key);
			
			if ($key == 'deposit') {
				$text_category .= ' (' . $this->currency->format($this->config->get('config_customer_deposit'), $order_info['currency_code'], $order_info['currency_value']) . ')';
			} elseif ($key == 'order') {
				$text_category .= ' (' . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']) . ')';
			}

			$debit = isset($transaction_summary['D']) ? $transaction_summary['D'] : 0;
			$credit = isset($transaction_summary['C']) ? $transaction_summary['C'] : 0;
			$balance = $debit - $credit;

			$data['customers_transaction_summary'][] = [
				'transaction_type' 	=> $text_category,
				'debit'				=> $this->currency->format($debit, $order_info['currency_code'], $order_info['currency_value']),
				'credit'			=> $this->currency->format($credit, $order_info['currency_code'], $order_info['currency_value']),
				'balance'			=> $this->currency->format($balance, $order_info['currency_code'], $order_info['currency_value'])
			];
		}

		$data['customer_transactions'] = [];

		$filter_data = [
			'filter'	=> ['order_id' => $order_id, 'client_label' => 'customer'],
			'sort'		=> 't.date DESC, t.transaction_id',
			'order'		=> 'DESC',
			'start'		=> ($page - 1) * $limit,
			'limit'		=> $limit
		];

		$results = $this->model_accounting_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			switch ($result['transaction_label']) {
				case 'cash':
					$href = $this->url->link('sale/order/receipt', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'], true);

					break;

				case 'initial':
					$href = $this->url->link('sale/order/agreement', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true);

					break;

				default:
					$href = '';

					break;
			}

			if ($result['account_type'] == 'C') {
				$result['amount'] = -$result['amount'];
			}

			$data['customer_transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'				=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'customer_name'		=> $result['customer_name'],
				'payment_method'	=> $result['payment_method'],
				'reference'			=> $result['reference'],
				'description'		=> $result['description'],
				'amount'			=> $this->currency->format($result['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'			=> $result['username'],
				'receipt'	 		=> $href,
				'print'				=> $result['printed']
			);
		}

		$customer_transaction_count = $this->model_accounting_transaction->getTransactionsCount($filter_data);

		$pagination = new Pagination();
		$pagination->total = $customer_transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($customer_transaction_count - $limit)) ? $customer_transaction_count : ((($page - 1) * $limit) + $limit), $customer_transaction_count, ceil($customer_transaction_count / $limit));

		# Transaction Types
		$this->load->model('accounting/transaction_type');

		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesMenu(['client_label' => 'customer']);

		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $order_id;

		$this->response->setOutput($this->load->view('sale/customer', $data));
	}

	public function transaction()
	{
		$this->load->language('sale/customer');

		$json = array();
		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

		$order_info = [];
		$transaction_type_info = [];
		$account_debit_info = [];
		$account_credit_info = [];

		switch (false) {
			case $json:
				if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/customer')) {
					$json['error']['warning'] = $this->language->get('error_permission');

					break;
				} else {
					if (empty($this->request->post['customer_transaction_date'])) {
						$json['error_customer_transaction']['date'] = $this->language->get('error_transaction_date');
					} elseif ($this->config->get('config_reverse_entry_limit') >= 0) {
						if ((date('Y-m-d', strtotime(-$this->config->get('config_reverse_entry_limit') . ' days'))) > $this->request->post['customer_transaction_date']) {
							$json['error_customer_transaction']['date'] = sprintf($this->language->get('error_reverse_entry_limit'), $this->config->get('config_reverse_entry_limit'));
						}
					}

					if (empty($this->request->post['customer_transaction_type_id'])) {
						$json['error_customer_transaction']['type'] = $this->language->get('error_transaction_type');
					}

					if (empty($this->request->post['customer_transaction_account_debit_id'])) {
						$json['error_customer_transaction']['account_debit'] = $this->language->get('error_transaction_account');
					}

					if (empty($this->request->post['customer_transaction_account_credit_id'])) {
						$json['error_customer_transaction']['account_credit'] = $this->language->get('error_transaction_account');
					}

					if (utf8_strlen($this->request->post['customer_transaction_description']) > 256) {
						$json['error_customer_transaction']['description'] = $this->language->get('error_transaction_description');
					}

					if (empty((float)$this->request->post['customer_transaction_amount']) || (float)$this->request->post['customer_transaction_amount'] <= 0) {
						$json['error_customer_transaction']['amount'] = $this->language->get('error_transaction_amount');
					}

					if ($json) {
						$json['error']['warning'] = $this->language->get('error_warning');

						break;
					}
				}

				$this->load->model('sale/order');
				$order_info = $this->model_sale_order->getOrder($order_id);

				if (!$order_info) {
					$json['error']['warning'] = $this->language->get('error_order');

					break;
				}

				# lock transaction if current order status is complete
				if ($this->config->get('config_lock_complete_order') && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
					$json['error']['warning'] = $this->language->get('error_status_complete');

					break;
				}

				if (!in_array($order_info['order_status_id'], $this->config->get('config_status_with_payment'))) {
					$json['error']['warning'] = $this->language->get('error_order_status');

					break;
				}

				$this->load->model('accounting/account');
				$account_debit_info = $this->model_accounting_account->getAccount($this->request->post['customer_transaction_account_debit_id']);
				$account_credit_info = $this->model_accounting_account->getAccount($this->request->post['customer_transaction_account_credit_id']);

				if (!$account_debit_info || !$account_credit_info) {
					$json['error']['warning'] = $this->language->get('error_account_not_found');

					break;
				}

				$this->load->model('accounting/transaction_type');
				$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->post['customer_transaction_type_id']);

				if (!$transaction_type_info) {
					$json['error']['warning'] = $this->language->get('error_type_not_found');

					break;
				}

				$this->load->model('accounting/transaction');

				# Cek Pemesanan Sewa Ballroom
				if ($transaction_type_info['category_label'] == 'order') {
					$summary_data = [
						'category_label'	=> 'order',
						'transaction_label'	=> 'initial',
						'client_id'			=> $order_info['customer_id'],
						'group'				=> 'transaction_label'
					];

					$transaction_summary = $this->model_accounting_transaction->getTransactionsSummaryGroupByLabel($order_id, $summary_data);

					if (empty($transaction_summary['initial'])) {
						$json['error']['warning'] = $this->language->get('error_order_not_found');

						break;
					}
				}

			default:
				break;
		}

		if (!$json) {
			$transaction_account = [];

			switch ($transaction_type_info['transaction_label']) {
				case 'cash':
					$reference_prefix = str_ireplace('{YEAR}', date('Y', strtotime($this->request->post['customer_transaction_date'])), $this->config->get('config_receipt_customer_prefix'));

					$last_reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix);

					if ($last_reference_no) {
						$reference_no = $last_reference_no + 1;
					} else {
						$reference_no = $this->config->get('config_reference_start') + 1;
					}

					break;

				case 'charged':
					$reference_prefix = 'C' . date('ym');

					$last_reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix);
					$reference_no = $last_reference_no + 1;

					break;

				default:
					break;
			}

			$this->load->model('accounting/account');

			if ($transaction_type_info['transaction_label'] == 'cash') {
				if ($transaction_type_info['account_type'] == 'D') {
					$payment_method = $account_debit_info['account_id'] . ' - ' . $account_debit_info['name'];
				} else {
					$payment_method = $account_credit_info['account_id'] . ' - ' . $account_credit_info['name'];
				}

			} else {
				$payment_method = '';
			}

			$transaction_account[] = [
				'account_id'		=> $this->request->post['customer_transaction_account_debit_id'],
				'debit'				=> $this->request->post['customer_transaction_amount'],
				'credit'			=> 0
			];

			$transaction_account[] = [
				'account_id'		=> $this->request->post['customer_transaction_account_credit_id'],
				'debit'				=> 0,
				'credit'			=> $this->request->post['customer_transaction_amount']
			];

			$transaction_data = array(
				'order_id'				=> $order_id,
				'transaction_type_id'	=> $this->request->post['customer_transaction_type_id'],
				'client_id'				=> $order_info['customer_id'],
				'date' 					=> $this->request->post['customer_transaction_date'],
				'payment_method' 		=> $payment_method,
				'description' 			=> $this->request->post['customer_transaction_description'],
				'amount' 				=> $this->request->post['customer_transaction_amount'],
				'customer_name' 		=> $order_info['customer'],
				'reference_prefix' 		=> $reference_prefix,
				'reference_no'			=> $reference_no,
				'transaction_account' 	=> $transaction_account
			);

			$this->model_accounting_transaction->addTransaction($transaction_data);

			if ($transaction_type_info['category_label'] == 'order') {
				$payment_phases = $this->model_sale_order->getPaymentPhases($order_id);
				foreach ($payment_phases as $payment_phase) {
					if ($payment_phase['paid_status']) {
						$order_status_id = $payment_phase['order_status_id'];
					}
				}

				if ($order_info['order_status_id'] != $order_status_id) {
					$this->model_sale_order->addOrderHistory($order_id, $order_status_id);
				}
			}

			$json['success'] = $this->language->get('text_customer_transaction_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function transactionTypeAccounts()
	{
		$json = [];

		$this->load->model('accounting/transaction_type');
		$this->load->model('accounting/account');
		$transaction_type_accounts = $this->model_accounting_transaction_type->getTransactionTypeAccounts($this->request->get['transaction_type_id']);

		$accounts_debit_id = array_column($transaction_type_accounts, 'account_debit_id');
		$accounts_credit_id = array_column($transaction_type_accounts, 'account_credit_id');

		$json['account_debit'] = array_values($this->model_accounting_account->getAccountsMenuByParentId($accounts_debit_id));
		$json['account_credit'] = array_values($this->model_accounting_account->getAccountsMenuByParentId($accounts_credit_id));

		$json['lock_debit'] = true;
		$json['lock_credit'] = true;

		if (count($accounts_debit_id) == 1) {
			$accounts_debit_count = $this->model_accounting_account->getAccountsCount(['filter_parent_id' => $accounts_debit_id[0]]);
			if ($accounts_debit_count != 1) {
				$json['lock_debit'] = false;
			}
		}

		if (count($accounts_credit_id) == 1) {
			$accounts_credit_count = $this->model_accounting_account->getAccountsCount(['filter_parent_id' => $accounts_credit_id[0]]);
			if ($accounts_credit_count != 1) {
				$json['lock_credit'] = false;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	///////////////////// Belum digunakan
	protected function getNumber($currency_string)
	{
		return preg_replace('/(?!-)[^0-9.]/', '', $currency_string);
	}
}
