<?php
class ControllerSaleVendor extends Controller
{
	public function index()
	{
		$this->load->language('sale/vendor');

		$language_items = array(
			'text_no_results',
			'text_print_confirm',
			'text_vendor',
			'text_vendor_transaction',
			'text_vendor_transaction_add',
			'text_loading',
			'text_select',
			'column_action',
			'column_amount',
			'column_asset',
			'column_debit',
			'column_balance',
			'column_date_added',
			'column_date',
			'column_description',
			'column_credit',
			'column_reference',
			'column_transaction_type',
			'column_username',
			'column_vendor',
			'entry_asset',
			'entry_vendor',
			'entry_date',
			'entry_description',
			'entry_amount',
			'entry_transaction_type',
			'help_amount',
			'button_admission',
			'button_agreement',
			'button_receipt',
			'button_transaction_add',
			'button_vendor_remove',
			'button_print',
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
		$this->load->model('sale/document');
		$this->load->model('accounting/transaction');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($this->config->get('config_complete_status_required') && !in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
			$order_admission_status = false;
		} else {
			$order_admission_status = true;
		}

		$data['vendor_transaction_summary'] = [];

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

			$summary_data = [
				'label'		=> 'vendor',
				'label_id'	=> $order_vendor['vendor_id'],
				'group'		=> 'tt.category_label'
			];

			$transaction_summary_data = [];
			
			$transactions_summary = $this->model_accounting_transaction->getTransactionsSummary($order_id, $summary_data);
			foreach ($transactions_summary as $key => $transaction_summary) {
				# Maintain Version 1
				if (empty($transaction_summary['category_label'])) {
					$transaction_summary['category_label'] = 'deposit';
					$transaction_summary['account_type'] = 'D';
				}
				# End Maintain

				$transactions_summary[$transaction_summary['category_label']][$transaction_summary['account_type']] = $transaction_summary;
				unset($transactions_summary[$key]);
			}

			$admission_status = $order_admission_status;

			foreach ($transactions_summary as $key => $transaction_summary) {
				$debit = isset($transaction_summary['D']) ? $transaction_summary['D']['total'] : 0;
				$credit = isset($transaction_summary['C']) ? $transaction_summary['C']['total'] : 0;

				$transaction_summary_data[$key] = [
					'transaction_type' 	=> $key ? $this->language->get('text_category_' . $key) : '',
					'debit'				=> $this->currency->format($debit, $order_info['currency_code'], $order_info['currency_value']),
					'credit'			=> $this->currency->format($credit, $order_info['currency_code'], $order_info['currency_value']),
					'balance'			=> $this->currency->format($debit - $credit, $order_info['currency_code'], $order_info['currency_value']),
					'balance_value'		=> $debit - $credit
				];
			}

			# Cek vendor udah bayar deposit
			if ($order_admission_status) {
				$order_vendor_info = $this->model_sale_order->getOrderVendor($order_id, $order_vendor['vendor_id']);

				if ($order_vendor_info['deposit']) {
					if (!isset($transaction_summary_data['deposit']) || $order_vendor_info['deposit'] < $transaction_summary_data['deposit']['balance_value']) {
						$admission_status = false;
					}
				}
			}

			$order_document_data = [];

			$document_filter_data = [
				'filter_order_id'		=> $order_id,
				'filter_client_type'	=> 'vendor',
				'filter_client_id'		=> $order_vendor['order_vendor_id']
			];

			$order_documents = $this->model_sale_document->getOrderDocuments($document_filter_data);
			foreach ($order_documents as $order_document) {
				$order_document_data[$order_document['document_type']] = $order_document;
			}

			$documents_data = [
				'agreement'	=> [],
				'admission'	=> []
			];

			foreach (array_keys($documents_data) as $type) {
				if ($type == 'agreement') {
					$status = true;
				} elseif ($type == 'admission') {
					$status = $admission_status;
				}

				$documents_data[$type] = [
					'href'			=> $this->url->link('sale/vendor/' . $type, 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&vendor_id=' . $order_vendor['vendor_id'], true),
					'printed'		=> isset($order_document_data[$type]) ? $order_document_data[$type]['printed'] : 0,
					'button_text'	=> $this->language->get('button_' . $type),
					'status'		=> $status
				];
			}

			$data['vendor_transaction_summary'][$order_vendor['vendor_id']] = [
				'title' 	=> $order_vendor['vendor_name'] . ' - ' . $order_vendor['vendor_type'],
				'href'		=> $this->url->link('catalog/vendor/edit', 'token=' . $this->session->data['token'] . '&vendor_id=' . $order_vendor['vendor_id'], true),
				'document'	=> $documents_data,
				'summary'	=> $transaction_summary_data
			];
		}

		$data['vendor_transactions'] = array();

		$filter_data = array(
			'filter_order_id'	=> $order_id,
			'filter_label'		=> 'vendor',
			'sort'				=> 't.date',
			'order'				=> 'DESC',
			'start'				=> ($page - 1) * $limit,
			'limit'				=> $limit
		);

		$results = $this->model_accounting_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			$code = explode('-', $result['reference']);

			# Maintain Version 1 
			if (!isset($code[1])) {
				$code[1] = '';
			}

			if (empty($result['transaction_type'])) {
				$result['transaction_type'] = $result['description'];
			}
			# End Maintain

			switch ($code[1]) {
				case 'KW':
					$href = $this->url->link('sale/order/receipt', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'], true);

					break;

				case 'PO':
					$href = $this->url->link('sale/purchase/purchaseOrderDocument', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . '&vendor_id=' . $result['label_id'], true);

					break;

				default:
					$href = '#';

					break;
			}

			$data['vendor_transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'				=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'vendor_name'		=> $result['customer_name'],
				'reference'			=> $result['reference'],
				'asset'				=> $result['payment_method'],
				'description'		=> $result['description'],
				'amount'			=> $this->currency->format(($result['account_type'] == 'C' ? -1 : 1) * $result['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'			=> $result['username'],
				'receipt'	 		=> $href,
				'print'				=> $result['printed']
			);
		}

		$vendor_transaction_count = $this->model_accounting_transaction->getTransactionsCount($filter_data);

		$pagination = new Pagination();
		$pagination->total = $vendor_transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('sale/vendor', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($vendor_transaction_count - $limit)) ? $vendor_transaction_count : ((($page - 1) * $limit) + $limit), $vendor_transaction_count, ceil($vendor_transaction_count / $limit));

		// Vendors
		$data['order_vendors'] = array();
		$data['payment_accounts'] = array();

		if ($order_vendors) {
			foreach ($order_vendors as $order_vendor) {
				$data['order_vendors'][] = array(
					'vendor_id' => $order_vendor['vendor_id'],
					'title' 	=> $order_vendor['vendor_name'] . ' - ' . $order_vendor['vendor_type']
				);
			}

			# Transaction Types
			$this->load->model('accounting/transaction_type');

			$data['transaction_types'] = $this->model_accounting_transaction_type->getTransactionTypesByLabel('vendor');

			# Accounts
			$this->load->model('accounting/account');

			$data['assets'] = $this->model_accounting_account->getAccountsMenuByComponent([''], ['current_asset']);
		}

		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $order_id;

		$this->response->setOutput($this->load->view('sale/vendor', $data));
	}

	public function transaction()
	{
		$this->load->language('sale/vendor');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/vendor')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (empty($this->request->post['vendor_transaction_vendor_id'])) {
				$json['error_vendor_transaction']['vendor'] = $this->language->get('error_transaction_vendor');
			}

			if (empty($this->request->post['vendor_transaction_date'])) {
				$json['error_vendor_transaction']['date'] = $this->language->get('error_transaction_date');
			}

			if (empty($this->request->post['vendor_transaction_type_id'])) {
				$json['error_vendor_transaction']['type'] = $this->language->get('error_transaction_type');
			}

			if (empty($this->request->post['vendor_transaction_asset_id'])) {
				$json['error_vendor_transaction']['asset'] = $this->language->get('error_transaction_asset');
			}

			if (utf8_strlen($this->request->post['vendor_transaction_description']) > 256) {
				$json['error_vendor_transaction']['description'] = $this->language->get('error_transaction_description');
			}

			if (empty((float)$this->request->post['vendor_transaction_amount']) || (float)$this->request->post['vendor_transaction_amount'] <= 0) {
				$json['error_vendor_transaction']['amount'] = $this->language->get('error_transaction_amount');
			}

			if ($json) {
				$json['error']['warning'] = $this->language->get('error_warning');
			}
		}

		if (!$json) {
			$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);
			$order_vendors = $this->model_sale_order->getOrderVendors($order_id);

			if ($order_info) {
				if (!in_array($this->request->post['vendor_transaction_vendor_id'], array_column($order_vendors, 'vendor_id'))) {
					$json['error_transaction_vendor'] = $this->language->get('error_order_vendor');
				}
			} else {
				$json['error'] = $this->language->get('error_order');
			}
		}

		if (!$json) {
			$this->load->model('catalog/vendor');
			$this->load->model('accounting/account');
			$this->load->model('accounting/transaction_type');
			$this->load->model('accounting/transaction');

			$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['vendor_transaction_vendor_id']);

			$reference_prefix = str_ireplace('{YEAR}', date('Y', strtotime($this->request->post['vendor_transaction_date'])), $this->config->get('config_receipt_vendor_prefix'));

			$last_reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix);

			if ($last_reference_no) {
				$reference_no = $last_reference_no + 1;
			} else {
				$reference_no = $this->config->get('config_reference_start') + 1;
			}

			$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->post['vendor_transaction_type_id']);

			$account_debit_id = empty($transaction_type_info['account_debit_id']) ? $this->request->post['vendor_transaction_asset_id'] : ($transaction_type_info['account_debit_id']);
			$account_credit_id = empty($transaction_type_info['account_credit_id']) ? $this->request->post['vendor_transaction_asset_id'] : ($transaction_type_info['account_credit_id']);

			$asset_info = $this->model_accounting_account->getAccount($this->request->post['vendor_transaction_asset_id']);

			$transaction_data = array(
				'order_id'				=> $order_id,
				'account_to_id'			=> $account_debit_id,
				'account_from_id'		=> $account_credit_id,
				'label'					=> 'vendor',
				'label_id'				=> $this->request->post['vendor_transaction_vendor_id'],
				'transaction_type_id' 	=> $this->request->post['vendor_transaction_type_id'],
				'date' 					=> $this->request->post['vendor_transaction_date'],
				'payment_method'		=> $asset_info['name'],
				'description' 			=> $this->request->post['vendor_transaction_description'],
				'amount' 				=> $this->request->post['vendor_transaction_amount'],
				'customer_name' 		=> $vendor_info['vendor_name'],
				'reference_prefix' 		=> $reference_prefix,
				'reference_no'			=> $reference_no
			);

			$this->model_accounting_transaction->addTransaction($transaction_data);

			$json['success'] = $this->language->get('text_vendor_transaction_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function agreement()
	{
		$this->load->model('sale/order');

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

		$vendor_id = isset($this->request->get['vendor_id']) ? $this->request->get['vendor_id'] : 0;

		$order_info = $this->model_sale_order->getOrder($order_id);
		$order_vendor_info = $this->model_sale_order->getOrderVendor($order_id, $vendor_id);

		if ($order_vendor_info) {
			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');
			$this->load->model('sale/document');

			$this->load->language('sale/vendor');
			$this->load->language('sale/document');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$data['letter_head'] = HTTP_CATALOG . 'image/catalog/letter_head.png';

			$language_items = array(
				'title_vendor_agreement',
				'text_mark',
				'text_reference',
				'text_customer',
				'text_vendor_name',
				'text_address',
				'text_telephone',
				'text_garis',
				'text_mohon_surat',
				'text_uang_dikembalikan',
				'text_kerusakan',
				'text_lebih_lanjut',
				'text_hormat_kami',
				'text_menyetujui',
				'text_tanda_tangan'
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

			$filter_data = [
				'filter_order_id'		=> $order_id,
				'filter_client_type'	=> 'vendor',
				'filter_document_type'	=> 'agreement',
				'filter_client_id'		=> $order_vendor_info['order_vendor_id']
			];

			$order_document_info = $this->model_sale_document->getOrderDocuments($filter_data);

			if (!$order_document_info) {
				$document_data = [
					'order_id'			=> $order_id,
					'client_type'		=> 'vendor',
					'document_type'		=> 'agreement',
					'client_id'			=> $order_vendor_info['order_vendor_id']
				];

				$order_document_id = $this->model_sale_document->addOrderDocumentByType($document_data);

				$order_document_info = $this->model_sale_document->getOrderDocument($order_document_id);
			} else {
				$order_document_info = $order_document_info[0];
			}

			$data['reference'] = $order_document_info['reference'];

			$document_date_in = $this->model_localisation_local_date->getInFormatDate($order_document_info['date']);
			$data['text_place_date'] = sprintf($this->language->get('text_place_date'), $document_date_in['long_date']);

			$data['vendor_name'] = $order_vendor_info['vendor_name'];
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($order_vendor_info['address'])));
			$data['telephone'] = $order_vendor_info['telephone'];

			$data['text_ketentuan'] = sprintf($this->language->get('text_ketentuan'), $data['store_name']);
			$data['text_apabila_anda'] = sprintf($this->language->get('text_apabila_anda'), $data['store_telephone'], $data['store_email']);

			if ($order_vendor_info['deposit']) {
				$deposit = $this->currency->format($order_vendor_info['deposit'], $order_info['currency_code'], $order_info['currency_value']);
				$deposit_in_word = $this->model_localisation_local_date->getInWord($order_vendor_info['deposit']);

				$data['text_uang_jaminan'] = sprintf($this->language->get('text_uang_jaminan'), $deposit, $deposit_in_word);

				$no_rekening = $this->config->get($order_info['payment_code'] . '_bank' . $order_info['language_id']);
				$data['no_rekening'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($no_rekening)));

				$data['text_silahkan_transfer'] = sprintf($this->language->get('text_silahkan_transfer'), $data['store_name']);

				$data['pay_deposit'] = 1;
			} else {
				$data['pay_deposit'] = 0;
			}

			$data['text_vendor_setuju'] = sprintf($this->language->get('text_vendor_setuju'), $data['store_name']);

			// User Info
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($order_vendor_info['user_id']);

			$data['text_manajemen'] = $user_info['user_group'];
			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';

			$print = isset($this->request->get['print']) && $this->request->get['print'] == 1;
			$preview = $order_document_info['printed'] || !$print || !$this->user->hasPermission('modify', 'sale/vendor');
			// $preview = 0;// Development Purpose

			if ($preview) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_sale_document->editDocumentPrintStatus($order_document_info['order_document_id'], 1);
			}

			$this->response->setOutput($this->load->view('sale/vendor_agreement', $data));
		} else {
			return false;
		}
	}

	public function admission()
	{
		$this->load->model('sale/order');
		$this->load->model('accounting/transaction');

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

		$vendor_id = isset($this->request->get['vendor_id']) ? $this->request->get['vendor_id'] : 0;

		$order_info = $this->model_sale_order->getOrder($order_id);
		$order_vendor_info = $this->model_sale_order->getOrderVendor($order_id, $vendor_id);

		if ($this->config->get('config_complete_status_required') && !in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
			$admission_status = false;
		} else {
			$admission_status = true;
		}

		if ($order_vendor_info && $order_vendor_info['deposit']) {
			$summary_data = [
				'label'				=> 'vendor',
				'label_id'			=> $order_vendor_info['vendor_id'],
				'category_label'	=> 'deposit',
				'group'				=> 'tt.category_label'
			];

			$transaction_total = $this->model_accounting_transaction->getTransactionsTotalSummary($order_id, $summary_data);

			if ($order_vendor_info['deposit'] > $transaction_total) {
				$admission_status = false;
			}
		}
		// $admission_status = 1;//Develompent Purpose

		if ($admission_status) {
			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');
			$this->load->model('sale/document');

			$this->load->language('sale/vendor');
			$this->load->language('sale/document');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$data['letter_head'] = HTTP_CATALOG . 'image/catalog/letter_head.png';

			$language_items = array(
				'title_admission',
				'text_mark',
				'text_reference',
				'text_day_date',
				'text_slot',
				'text_venue',
				'text_package',
				'text_dengan_ini',
				'text_vendor_name',
				'text_vendor_type',
				'text_address',
				'text_telephone',
				'text_contact_person',
				'text_persiapan',
				'text_time',
				'text_catatan',
				'text_lampirkan',
				'text_demikian_2',
				'text_manajemen'
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

			$filter_data = [
				'filter_order_id'		=> $order_id,
				'filter_client_type'	=> 'vendor',
				'filter_document_type'	=> 'admission',
				'filter_client_id'		=> $order_vendor_info['order_vendor_id']
			];

			$order_document_info = $this->model_sale_document->getOrderDocuments($filter_data);

			if (!$order_document_info) {
				$document_data = [
					'order_id'			=> $order_id,
					'client_type'		=> 'vendor',
					'document_type'		=> 'admission',
					'client_id'			=> $order_vendor_info['order_vendor_id']
				];

				$order_document_id = $this->model_sale_document->addOrderDocumentByType($document_data);

				$order_document_info = $this->model_sale_document->getOrderDocument($order_document_id);
			} else {
				$order_document_info = $order_document_info[0];
			}

			$data['reference'] = $order_document_info['reference'];

			if (!$order_info['title']) {
				$order_info['title'] = sprintf($this->language->get('text_atas_nama'), $order_info['firstname'] . ' ' . $order_info['lastname']);
			}

			$data['text_sehubungan'] = sprintf($this->language->get('text_sehubungan'), $order_info['title'], $data['store_name']);

			// Event Data
			$event_date_in = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);
			$data['slot'] = $order_info['slot'];

			$data['event_date'] = $event_date_in['day'] . '/' . $event_date_in['long_date'];

			// Product Data
			$primary_product = $this->model_sale_order->getOrderPrimaryProduct($order_id);

			$data['package'] = $primary_product['name'];

			$data['venue'] = '-';

			$attributes = $this->model_sale_order->getOrderAttributes($order_id, $primary_product['order_product_id']);

			foreach ($attributes as $attribute) {
				if ($attribute['attribute'] == 'Venue') {
					$data['venue'] = $attribute['text'];
				}
			}

			$data['vendor_name'] = $order_vendor_info['vendor_name'];
			$data['vendor_type'] = $order_vendor_info['vendor_type'];
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($order_vendor_info['address'])));
			$data['telephone'] = $order_vendor_info['telephone'];
			$data['contact_person'] = $order_vendor_info['contact_person'];

			$preparation_date_in = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);
			$data['preparation_date'] = $preparation_date_in['day'] . '/' . $preparation_date_in['long_date'];

			$data['preparation_time'] = $order_info['slot'];

			// User Info
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->user->getId());

			$data['text_manajemen'] = $user_info['user_group'];
			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';

			$print = isset($this->request->get['print']) && $this->request->get['print'] == 1;
			$preview = $order_document_info['printed'] || !$print || !$this->user->hasPermission('modify', 'sale/vendor');
			// $preview = 0;// Development Purpose

			if ($preview) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_sale_document->editDocumentPrintStatus($order_document_info['order_document_id'], 1);
			}

			$this->response->setOutput($this->load->view('sale/vendor_admission', $data));
		} else {
			return false;
		}
	}
}