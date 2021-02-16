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

		$summary_data = [
			'order_id'	=> $order_id,
			'label'		=> 'customer',
			'group'		=> 'tt.category_label',
			'sort'		=> 'tt.category_label'
		];

		$customer_transaction_summary = $this->model_accounting_transaction->getTransactionsSummary($summary_data);

		foreach ($customer_transaction_summary as $transaction_summary) {
			if ($transaction_summary['client_label'] . '-' . $transaction_summary['category_label'] == $this->config->get('config_customer_deposit_label')) {
				$amount = $this->config->get('config_customer_deposit');
			} elseif (empty($transaction_summary['category_label']) || $transaction_summary['category_label'] == 'order') {
				$amount = $order_info['total'];
			}

			$data['customers_transaction_summary'][] = [
				'transaction_type' 	=> $this->language->get('text_category_' . $transaction_summary['category_label']),
				'amount'			=> $this->currency->format($amount, $order_info['currency_code'], $order_info['currency_value']),
				'total'				=> $this->currency->format($transaction_summary['total'], $order_info['currency_code'], $order_info['currency_value']),
				'balance'			=> $this->currency->format($amount - $transaction_summary['total'], $order_info['currency_code'], $order_info['currency_value'])
			];
		}

		$data['customer_transactions'] = [];

		$filter_data = [
			'label'		=> 'customer',
			'sort'		=> 't.date DESC, t.transaction_id',
			'order'		=> 'DESC',
			'start'		=> ($page - 1) * $limit,
			'limit'		=> $limit
		];

		$results = $this->model_accounting_transaction->getTransactionsByOrderId($order_id, $filter_data);

		foreach ($results as $result) {
			$data['customer_transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'				=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'customer_name'		=> $result['customer_name'],
				'asset'				=> $result['payment_method'],
				'reference'			=> $result['reference'],
				'description'		=> $result['description'],
				'amount'			=> $this->currency->format($result['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'			=> $result['username'],
				'receipt'	 		=> $this->url->link('sale/order/receipt', 'token=' . $this->session->data['token'] . '&transaction_id=' . (int)$result['transaction_id'], true),
				'print'				=> $result['printed']
			);
		}

		$customer_transaction_count = $this->model_accounting_transaction->getTransactionsCountByOrderId($order_id, $filter_data);

		$pagination = new Pagination();
		$pagination->total = $customer_transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($customer_transaction_count - $limit)) ? $customer_transaction_count : ((($page - 1) * $limit) + $limit), $customer_transaction_count, ceil($customer_transaction_count / $limit));

		# Transaction Types
		$this->load->model('accounting/transaction_type');

		$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesByLabel('customer');

		# Accounts
		$this->load->model('accounting/account');

		$data['assets'] = $this->model_accounting_account->getAccountsMenuByComponent([''], ['current_asset']);

		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $order_id;

		$this->response->setOutput($this->load->view('sale/customer', $data));
	}

	public function transaction()
	{
		$this->load->language('sale/customer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/customer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
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
			}
		}

		if (!$json) {
			$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if (!$order_info) {
				$json['error'] = $this->language->get('error_order');
			}
		}

		if (!$json) {
			$this->load->model('accounting/account');
			$this->load->model('accounting/transaction_type');
			$this->load->model('accounting/transaction');

			$reference_prefix = str_ireplace('{YEAR}', date('Y', strtotime($this->request->post['customer_transaction_date'])), $this->config->get('config_receipt_customer_prefix'));

			$last_reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix);

			if ($last_reference_no) {
				$reference_no = $last_reference_no + 1;
			} else {
				$reference_no = $this->config->get('config_reference_start') + 1;
			}

			$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->post['customer_transaction_type_id']);

			$account_debit_id = empty($transaction_type_info['account_debit_id']) ? $this->request->post['customer_transaction_asset_id'] : ($transaction_type_info['account_debit_id']);
			$account_credit_id = empty($transaction_type_info['account_credit_id']) ? $this->request->post['customer_transaction_asset_id'] : ($transaction_type_info['account_credit_id']);

			$asset_info = $this->model_accounting_account->getAccount($this->request->post['customer_transaction_asset_id']);

			$transaction_data = array(
				'order_id'				=> $order_id,
				'account_to_id'			=> $account_debit_id,
				'account_from_id'		=> $account_credit_id,
				'label'					=> 'customer',
				'label_id'				=> $order_info['customer_id'],
				'transaction_type_id' 	=> $this->request->post['customer_transaction_type_id'],
				'date' 					=> $this->request->post['customer_transaction_date'],
				'payment_method'		=> $asset_info['name'],
				'description' 			=> $this->request->post['customer_transaction_description'],
				'amount' 				=> $this->request->post['customer_transaction_amount'],
				'customer_name' 		=> $order_info['customer'],
				'reference_prefix'		=> $reference_prefix,
				'reference_no' 			=> $reference_no
			);

			$this->model_accounting_transaction->addTransaction($transaction_data);

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
