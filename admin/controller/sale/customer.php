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
			'column_asset',
			'column_amount',
			'column_balance',
			'column_date',
			'column_date_added',
			'column_description',
			'column_reference',
			'column_total',
			'column_transaction_type',
			'column_username',
			'entry_amount',
			'entry_asset',
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

		$customer_transaction_summary = $this->model_accounting_transaction->getTransactionsSummary($order_id, ['client_label'	=> 'customer']);

		foreach ($customer_transaction_summary as $key => $transaction_summary) {
			$customer_transaction_summary[$transaction_summary['category_label']][$transaction_summary['transaction_label']] = $transaction_summary;
			unset($customer_transaction_summary[$key]);
		}

		if ($this->config->get('config_customer_deposit') && !isset($customer_transaction_summary['deposit'])) {
			$customer_transaction_summary['deposit'] = [];
		}

		foreach ($customer_transaction_summary as $key => $transaction_summary) {
			if ($key == 'deposit') {
				$amount = $this->config->get('config_customer_deposit');
			} elseif ($key == 'order' || !isset($customer_transaction_summary['order'])) {
				$amount = $order_info['total'];
			}

			$cashin = isset($transaction_summary['cashin']) ? $transaction_summary['cashin']['total'] : 0;
			$cashout = isset($transaction_summary['cashout']) ? $transaction_summary['cashout']['total'] : 0;
			$total = $cashin - $cashout;

			$data['customers_transaction_summary'][] = [
				'transaction_type' 	=> $this->language->get('text_category_' . $key),
				'amount'			=> $this->currency->format($amount, $order_info['currency_code'], $order_info['currency_value']),
				'total'				=> $this->currency->format($total, $order_info['currency_code'], $order_info['currency_value']),
				'balance'			=> $this->currency->format($amount - $total, $order_info['currency_code'], $order_info['currency_value'])
			];
		}

		$data['customer_transactions'] = [];

		$filter_data = [
			'filter_order_id'		=> $order_id,
			'filter_client_label'	=> 'customer',
			'sort'					=> 't.date DESC, t.transaction_id',
			'order'					=> 'DESC',
			'start'					=> ($page - 1) * $limit,
			'limit'					=> $limit
		];

		$results = $this->model_accounting_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			// $total = 0;

			// $transaction_accounts = $this->model_accounting_transaction->getTransactionAccounts($result['transaction_id']);

			// foreach ($transaction_accounts as $transaction_account) {
			// 	# Maintain Version 1 
			// 	if (empty($transaction_accounts['transaction_label'])) {
			// 		$this->load->model('accounting/transaction_type');

			// 		$transaction_type_account_info = $this->model_accounting_transaction_type->getTransactionTypeAccounts($result['transaction_type_id']);

			// 		$transaction_account['transaction_label'] = $transaction_type_account_info[0]['transaction_label'];
			// 	}
			// 	# End Maintain

			// 	if ($transaction_account['transaction_label'] == 'cashin') {
			// 		$total += $transaction_account['debit'];
			// 	} elseif ($transaction_account['transaction_label'] == 'cashout') {
			// 		$total -= $transaction_account['credit'];
			// 	}
			// }

			if ($result['transaction_label'] == 'cashin') {
				$amount = $result['amount'];
			} elseif ($result['transaction_label'] == 'cashout') {
				$amount = -$result['amount'];
			}

			$data['customer_transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'				=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'customer_name'		=> $result['customer_name'],
				'asset'				=> $result['payment_method'],
				'reference'			=> $result['reference'],
				'description'		=> $result['description'],
				'amount'			=> $this->currency->format($amount, $order_info['currency_code'], $order_info['currency_value']),
				// 'amount'			=> $this->currency->format($total, $order_info['currency_code'], $order_info['currency_value']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'			=> $result['username'],
				'receipt'	 		=> $this->url->link('sale/order/receipt', 'token=' . $this->session->data['token'] . '&transaction_id=' . (int)$result['transaction_id'], true),
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

		# Accounts
		$this->load->model('accounting/account');

		$data['assets'] = $this->model_accounting_account->getAccountsMenuByParentId([1111, 1112]);

		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $order_id;

		$this->response->setOutput($this->load->view('sale/customer', $data));
	}

	public function transaction()
	{
		$this->load->language('sale/customer');

		$json = array();

		switch (false) {
			case $json:
				if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/customer')) {
					$json['error']['warning'] = $this->language->get('error_permission');

					break;
				} else {
					if (empty($this->request->post['customer_transaction_date'])) {
						$json['error_customer_transaction']['date'] = $this->language->get('error_transaction_date');
					}

					if (empty($this->request->post['customer_transaction_type_id'])) {
						$json['error_customer_transaction']['type'] = $this->language->get('error_transaction_type');
					}

					if (empty($this->request->post['customer_transaction_asset_id'])) {
						$json['error_customer_transaction']['asset'] = $this->language->get('error_transaction_asset');
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

				$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

				$this->load->model('sale/order');
				$order_info = $this->model_sale_order->getOrder($order_id);

				if (!$order_info) {
					$json['error']['warning'] = $this->language->get('error_order');

					break;
				}

				if (!in_array($order_info['order_status_id'], $this->config->get('config_status_with_payment'))) {
					$json['error']['warning'] = $this->language->get('error_order_status');

					break;
				}

				$this->load->model('accounting/account');
				$asset_info = $this->model_accounting_account->getAccount($this->request->post['customer_transaction_asset_id']);

				if (!$asset_info) {
					$json['error']['warning'] = $this->language->get('error_asset_not_found');

					break;
				}

				$this->load->model('accounting/transaction_type');
				$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->post['customer_transaction_type_id']);

				if (!$transaction_type_info) {
					$json['error']['warning'] = $this->language->get('error_type_not_found');

					break;
				}

			default:
				break;
		}

		if (!$json) {
			$this->load->model('accounting/transaction');

			$transaction_account = [];

			$reference_prefix = str_ireplace('{YEAR}', date('Y', strtotime($this->request->post['customer_transaction_date'])), $this->config->get('config_receipt_customer_prefix'));

			$last_reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix);

			if ($last_reference_no) {
				$reference_no = $last_reference_no + 1;
			} else {
				$reference_no = $this->config->get('config_reference_start') + 1;
			}

			$transaction_type_accounts = $this->model_accounting_transaction_type->getTransactionTypeAccounts($transaction_type_info['transaction_type_id']);

			foreach ($transaction_type_accounts as $transaction_type_account) {
				$account_debit_id = empty($transaction_type_account['account_debit_id']) ? $this->request->post['customer_transaction_asset_id'] : ($transaction_type_account['account_debit_id']);
				$account_credit_id = empty($transaction_type_account['account_credit_id']) ? $this->request->post['customer_transaction_asset_id'] : ($transaction_type_account['account_credit_id']);

				$transaction_account[] = [
					'account_id'		=> $account_debit_id,
					'debit'				=> $this->request->post['customer_transaction_amount'],
					'credit'			=> 0
				];

				$transaction_account[] = [
					'account_id'		=> $account_credit_id,
					'debit'				=> 0,
					'credit'			=> $this->request->post['customer_transaction_amount']
				];
			}

			$transaction_data = array(
				'order_id'				=> $order_id,
				'transaction_type_id'	=> $this->request->post['customer_transaction_type_id'],
				'client_id'				=> $order_info['customer_id'],
				'date' 					=> $this->request->post['customer_transaction_date'],
				'description' 			=> $this->request->post['customer_transaction_description'],
				'payment_method' 		=> $asset_info['name'],
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

	///////////////////// Belum digunakan
	protected function getNumber($currency_string)
	{
		return preg_replace('/(?!-)[^0-9.]/', '', $currency_string);
	}
}
