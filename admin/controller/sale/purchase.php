<?php
class ControllerSalePurchase extends Controller
{
	public function index()
	{
		$this->load->language('sale/purchase');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/purchase');

		$language_items = array(
			'text_complete_confirm',
			'text_loading',
			'text_no_results',
			'text_preview',
			'text_print',
			'text_print_confirm',
			// 'text_process',
			'text_product_list',
			'text_purchase',
			'text_vendor_excluded',
			'column_action',
			'column_adjustment',
			'column_reference',
			'column_price',
			'column_product',
			'column_quantity',
			'column_status',
			'column_subtotal',
			'column_total_quantity',
			'column_total',
			'column_vendor',
			'entry_comment',
			'entry_vendor_reference',
			'button_complete',
			'button_purchase',
			'button_purchase_order'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$this->load->model('accounting/transaction');

		$data['order_purchases'] = [];

		$order_purchases = $this->model_sale_purchase->getOrderPurchases($order_id);

		foreach ($order_purchases as $order_purchase) {
			$order_purchase_products_data = [];
			$subtotal = 0;

			$order_purchase_products = $this->model_sale_purchase->getOrderPurchaseProducts($order_purchase['order_purchase_id']);

			foreach ($order_purchase_products as $order_purchase_product) {
				$order_purchase_products_data[$order_purchase_product['product_id']] = [
					'name'			=> $order_purchase_product['name'],
					'quantity'		=> $order_purchase_product['quantity'],
					'text_detail'	=> $order_purchase_product['quantity'] . ' ' . $order_purchase_product['unit_class'] . ' x ' . $this->currency->format($order_purchase_product['price'], $this->config->get('config_currency')) . ' = ' . $this->currency->format($order_purchase_product['total'], $this->config->get('config_currency'))
				];

				$subtotal += $order_purchase_product['total'];
			}

			$filter_data = [
				'client_id'	=> $order_purchase['vendor_id'],
				'order_id'	=> $order_id
			];

			$transaction_purchase_info = $this->model_accounting_transaction->getTransactionByTransactionTypeId($this->config->get('config_vendor_purchase_initial_id'), $filter_data);

			if (!$transaction_purchase_info) {
				$order_purchase_data = [
					'adjustment'		=> $order_purchase['adjustment'],
					'comment'			=> $order_purchase['comment'],
					'vendor_reference'	=> $order_purchase['vendor_reference'],
					'completed'			=> 0
				];
	
				$this->model_sale_purchase->editOrderPurchase($order_purchase['order_purchase_id'], $order_purchase_data);
				
				$order_purchase['completed'] = 0;
			}

			if ($order_purchase['completed']) {
				$status = $this->language->get('text_complete');
			} elseif ($order_purchase['printed']) {
				$status = $this->language->get('text_printed');
			} else {
				$status = $this->language->get('text_pending');
			}
			// $order_purchase['printed'] = 0;// Debug Purpose

			$data['order_purchases'][$order_purchase['vendor_id']] = [
				'vendor_id'			=> $order_purchase['vendor_id'],
				'vendor_name'		=> $order_purchase['vendor_id'] ? $order_purchase['vendor_name'] : $this->config->get('config_name'),
				'reference'			=> $order_purchase['vendor_id'] ? $order_purchase['reference'] : $this->language->get('text_internal'),
				'order_product'		=> $order_purchase_products_data,
				'subtotal'			=> $subtotal,
				'subtotal_text'		=> $this->currency->format($subtotal, $this->config->get('config_currency')),
				'adjustment'		=> $order_purchase['adjustment'],
				'adjustment_text'	=> $this->currency->format($order_purchase['adjustment'], $this->config->get('config_currency')),
				'total'				=> $subtotal + $order_purchase['adjustment'],
				'total_text'		=> $this->currency->format(($subtotal + $order_purchase['adjustment']), $this->config->get('config_currency')),
				'vendor_reference'	=> $order_purchase['vendor_reference'],
				'comment'			=> $order_purchase['comment'],
				'completed'			=> $order_purchase['completed'],
				'printed'			=> $order_purchase['printed'],
				'status'			=> $status,
				'purchase_href'		=> $this->url->link('sale/purchase/purchaseOrder', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&vendor_id=' . $order_purchase['vendor_id'], true),
			];
		}

		$data['products'] = array();

		$order_vendors = $this->model_sale_order->getOrderVendors($order_id);
		$order_vendors_id = array_column($order_vendors, 'vendor_id');
		array_push($order_vendors_id, '0');

		$products = $this->model_sale_order->getOrderProducts($order_id);

		foreach ($products as $product) {
			$option_data = [];

			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value'],
						'type'  => $option['type']
					);
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$option_data[] = [
							'name'  => $option['name'],
							'value' => $upload_info['name'],
							'type'  => $option['type'],
							'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
						];
					}
				}
			}

			$attribute_data = [];

			$attributes = $this->model_sale_order->getOrderAttributes($this->request->get['order_id'], $product['order_product_id']);

			foreach ($attributes as $attribute) {
				$attribute_data[$attribute['attribute_group']][] = [
					'name'	=> $attribute['attribute'],
					'value'	=> $attribute['text']
				];
			}

			$purchase_data = [];
			$remaining_quantity = $product['quantity'];

			$product_vendors = $this->model_catalog_product->getProductVendors($product['product_id']);

			$product_vendors[] = [
				'product_id' 		=> $product['product_id'],
				'vendor_id' 		=> 0,
				'purchase_price' 	=> 0,
				'vendor_name' 		=> $this->config->get('config_name'),
				'vendor_type' 		=> ''
			];

			foreach ($product_vendors as $product_vendor) {
				$locked = 0;
				$excluded = 0;

				if (isset($data['order_purchases'][$product_vendor['vendor_id']])) {
					$locked = $data['order_purchases'][$product_vendor['vendor_id']]['completed'];

					if (isset($data['order_purchases'][$product_vendor['vendor_id']]['order_product'][$product['product_id']])) {
						$quantity = $data['order_purchases'][$product_vendor['vendor_id']]['order_product'][$product['product_id']]['quantity'];
					} else {
						$quantity = 0;
					}
				} else {
					$locked = $excluded = !in_array($product_vendor['vendor_id'], $order_vendors_id) ? 1 : 0;
					$quantity = !$excluded ? $remaining_quantity : 0;
				}

				$remaining_quantity -= $quantity;

				$purchase_data[] = [
					'vendor_id'				=> $product_vendor['vendor_id'],
					'vendor_name'			=> $product_vendor['vendor_name'] . ' - ' . $product_vendor['vendor_type'],
					'purchase_price'		=> $product_vendor['purchase_price'],
					'purchase_price_text'	=> $this->currency->format($product_vendor['purchase_price'], $this->config->get('config_currency')),
					'quantity'				=> $quantity,
					'total'					=> abs($quantity) * $product_vendor['purchase_price'],
					'locked'				=> $locked,
					'excluded'				=> $excluded
				];
			}

			$data['products'][] = array(
				'order_product_id' 	=> $product['order_product_id'],
				'product_id'       	=> $product['product_id'],
				'name'    	 	   	=> $product['name'],
				'option'   		   	=> $option_data,
				'attribute'   	   	=> $attribute_data,
				'quantity'		   	=> $product['quantity'] . ' ' . $product['unit_class'],
				'purchase'    		=> $purchase_data,
				'rowspan'    		=> count($purchase_data),
			);
		}

		$data['token'] = $this->session->data['token'];
		$data['order_id'] = $order_id;

		$this->response->setOutput($this->load->view('sale/purchase', $data));
	}

	protected function getNumber($currency_string)
	{
		return preg_replace('/(?!-)[^0-9.]/', '', $currency_string);
	}

	public function purchase()
	{
		$this->load->language('sale/purchase');

		$this->load->model('sale/purchase');

		$json = array();

		# Apply getNumber untuk quantity
		foreach ($this->request->post['purchase'] as $vendor_id => $products) {
			foreach ($products as $product_id => $product) {
				$this->request->post['purchase'][$vendor_id][$product_id]['quantity'] = $this->getNumber($product['quantity']);
			}
		}

		if (!$this->user->hasPermission('modify', 'sale/purchase')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($order_id);

			if (!$order_info) {
				$json['error'] = $this->language->get('error_order');
			}
		}

		if (!$json) {
			$payment_phases = $this->model_sale_order->getPaymentPhases($order_id);

			if (!$payment_phases['initial_payment']['paid_status']) {
				$json['error'] = $this->language->get('error_initial_payment');
			}
		}

		if (!$json) {
			$order_vendors = [];
			$order_purchase_product_total = [];

			$order_products = $this->model_sale_order->getOrderProducts($order_id);
			$order_products_id = array_column($order_products, 'product_id');

			foreach ($this->request->post['purchase'] as $vendor_id => $products) {
				$order_purchase_info[$vendor_id] = $this->model_sale_purchase->getOrderPurchase($order_id, $vendor_id);

				// Cek ulang untuk Order Purchase yg telah complete maupun udah printed
				foreach ($products as $product_id => $product) {
					if (!in_array($product_id, $order_products_id)) {
						$json['error'] = $this->language->get('error_product_excluded');

						break;
					}

					# Cek vendor terdaftar pada order
					if ($vendor_id && array_sum(array_column($products, 'quantity'))) {
						$order_vendors[$vendor_id] = $this->model_sale_order->getOrderVendor($order_id, $vendor_id);

						if ($product['quantity'] && !$order_vendors[$vendor_id]) {
							$json['error'] = $this->language->get('error_vendor_excluded');

							break;
						}
					}

					# Cek status Order Purchase telah completed
					if ($order_purchase_info[$vendor_id] && $order_purchase_info[$vendor_id]['completed']) {
						$order_purchase_product = $this->model_sale_purchase->getOrderPurchaseProduct($order_purchase_info[$vendor_id]['order_purchase_id'], $product_id);

						if ($order_purchase_product && $product['quantity'] != $order_purchase_product['quantity']) {
							$json['error'] = $this->language->get('error_order_purchase');
						}
					}

					# Cek total qty telah sesuai
					if (isset($order_purchase_product_total[$product_id])) {
						$order_purchase_product_total[$product_id] += $product['quantity'];
					} else {
						$order_purchase_product_total[$product_id] = $product['quantity'];
					}
				}
			}

			foreach ($order_products as $order_product) {
				if ($order_product['quantity'] != $order_purchase_product_total[$order_product['product_id']]) {
					$json['error'] = $this->language->get('error_product_total');

					break;
				}
			}
		}

		if (!$json) {
			$this->model_sale_purchase->deleteOrderPurchases($order_id, 0);

			# Reserved if self is threated as vendor (Graha Kartini)
			$order_vendors[0] = [];

			foreach ($order_vendors as $vendor_id => $vendor_data) {
				$order_purchase_product_data = [];

				if (!empty($order_purchase_info[$vendor_id]) && $order_purchase_info[$vendor_id]['completed']) {
					unset($this->request->post['purchase'][$vendor_id]);

					continue;
				}

				foreach ($this->request->post['purchase'][$vendor_id] as $product_id => $product) {
					if ($product['quantity']) {
						$order_product_info = $this->model_sale_order->getOrderProduct($order_id, $product_id);

						$order_purchase_product_data[] = [
							'order_product_id'	=> $order_product_info['order_product_id'],
							'product_id'		=> $product_id,
							'quantity'			=> $product['quantity'],
							'price'				=> $product['purchase_price'],
							'total'				=> $product['quantity'] * $product['purchase_price']
						];
					}
				}

				$order_purchase_data = [
					'order_vendor_id'		=> $vendor_id ? $vendor_data['order_vendor_id'] : 0,
					'order_id'				=> $order_id,
					'vendor_id'				=> $vendor_id,
					'product'				=> $order_purchase_product_data
				];

				$this->model_sale_purchase->addOrderPurchase($order_purchase_data);
			}

			$json['success'] = $this->language->get('text_purchase_updated');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function complete()
	{
		$json = array();

		$this->load->language('sale/purchase');

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		$vendor_id = isset($this->request->post['vendor_id']) ? $this->request->post['vendor_id'] : 0;

		$order_purchase_info = [];
		$transaction_type_info = [];

		switch (false) {
			case $json:
				if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->hasPermission('modify', 'sale/purchase')) {
					$json['error']['warning'] = $this->language->get('error_permission');

					break;
				}

				// if (!$vendor_id) {
				// 	$json['error'] = $this->language->get('error_development');

				// 	break;
				// }

				$this->load->model('sale/purchase');

				$order_purchase_info = $this->model_sale_purchase->getOrderPurchase($order_id, $vendor_id);

				if (!$order_purchase_info) {
					$json['error'] = $this->language->get('error_order');

					break;
				}

				if (!$order_purchase_info['printed']) {
					$json['error'] = $this->language->get('error_print');

					break;
				}

				if (!$this->request->post['vendor_reference']) {
					$json['error_vendor_reference'] = $this->language->get('error_vendor_reference');

					break;
				}

				$this->load->model('accounting/transaction_type');

				$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->config->get('config_vendor_purchase_initial_id'));

				if (empty($transaction_type_info)) {
					$json['error'] = sprintf($this->language->get('error_transaction_type'), 'vendor-purchase-initial');

					break;
				}

				$this->load->model('accounting/transaction');

				$filter_data = [
					'client_id'	=> $vendor_id,
					'order_id'	=> $order_id
				];

				$transaction_purchase_info = $this->model_accounting_transaction->getTransactionByTransactionTypeId($transaction_type_info['transaction_type_id'], $filter_data);

				if ($transaction_purchase_info) {
					$json['error'] = $this->language->get('error_transaction');

					break;
				}

			default:
				break;
		}

		if (!$json) {
			$order_purchase_data = [
				'adjustment'		=> $this->request->post['adjustment'],
				'comment'			=> $this->request->post['comment'],
				'vendor_reference'	=> $this->request->post['vendor_reference'],
				'completed'			=> 1
			];

			$this->model_sale_purchase->editOrderPurchase($order_purchase_info['order_purchase_id'], $order_purchase_data);

			$transaction_account = [];
			$account_total = [];

			$order_purchase_products = $this->model_sale_purchase->getOrderPurchaseProducts($order_purchase_info['order_purchase_id']);
			$total = array_sum(array_column($order_purchase_products, 'total'));

			$transaction_type_accounts = $this->model_accounting_transaction_type->getTransactionTypeAccounts($transaction_type_info['transaction_type_id']);

			foreach ($transaction_type_accounts as $key => $transaction_type_account) {
				$transaction_type_accounts[$transaction_type_account['account_label']] = $transaction_type_account;
				unset($transaction_type_accounts[$key]);
			}

			$account_total[$transaction_type_accounts['initial']['account_debit_id']] = $total;
			$account_total[$transaction_type_accounts['initial']['account_credit_id']] = -$total;

			if (!empty($this->request->post['adjustment'])) {
				$total += $this->request->post['adjustment'];

				# Account Data
				if (isset($account_total[$transaction_type_accounts['discount']['account_debit_id']])) {
					$account_total[$transaction_type_accounts['discount']['account_debit_id']] -= $this->request->post['adjustment'];
				} else {
					$account_total[$transaction_type_accounts['discount']['account_debit_id']] = -$this->request->post['adjustment'];
				}

				if (isset($account_total[$transaction_type_accounts['discount']['account_credit_id']])) {
					$account_total[$transaction_type_accounts['discount']['account_credit_id']] += $this->request->post['adjustment'];
				} else {
					$account_total[$transaction_type_accounts['discount']['account_credit_id']] = $this->request->post['adjustment'];
				}
			}

			foreach ($account_total as $account_id => $value) {
				if ($value > 0) {
					$transaction_account[] = [
						'account_id'		=> $account_id,
						'debit'				=> $value,
						'credit'			=> 0
					];
				} elseif ($value < 0) {
					$transaction_account[] = [
						'account_id'		=> $account_id,
						'debit'				=> 0,
						'credit'			=> -$value
					];
				}
			}

			$transaction_data = array(
				'order_id'				=> $order_id,
				'transaction_type_id'	=> $transaction_type_info['transaction_type_id'],
				'client_id'				=> $vendor_id,
				'date' 					=> $order_purchase_info['date'],
				'description' 			=> $transaction_type_info['name'],
				'amount' 				=> $total,
				'customer_name' 		=> $order_purchase_info['vendor_name'],
				'reference_prefix' 		=> $order_purchase_info['reference_prefix'],
				'reference_no'			=> $order_purchase_info['reference_no'],
				'transaction_account' 	=> $transaction_account
			);

			$this->model_accounting_transaction->addTransaction($transaction_data);

			$json['success'] = $this->language->get('text_purchase_completed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function purchaseOrder()
	{
		$json = array();

		$this->load->model('sale/purchase');

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

		$vendor_id = isset($this->request->get['vendor_id']) ? $this->request->get['vendor_id'] : 0;

		$order_purchase_info = $this->model_sale_purchase->getOrderPurchase($order_id, $vendor_id);

		if ($order_purchase_info) {
			if (!empty($this->request->post['comment'])) {
				$order_purchase_data['comment']	= $this->request->post['comment'];

				$this->model_sale_purchase->editOrderPurchase($order_purchase_info['order_purchase_id'], $order_purchase_data);
			}

			$url = '&token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&vendor_id=' . $vendor_id;

			if (isset($this->request->get['print'])) {
				$url .= '&print=1';
			}

			$json['purchase_order_url'] = $url;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function purchaseOrderDocument()
	{
		$this->load->model('sale/purchase');
		$this->load->model('sale/order');

		$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

		$vendor_id = isset($this->request->get['vendor_id']) ? $this->request->get['vendor_id'] : 0;

		$order_info = $this->model_sale_order->getOrder($order_id);
		$order_purchase_info = $this->model_sale_purchase->getOrderPurchase($order_id, $vendor_id);

		if ($order_info) {
			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');
			$this->load->model('sale/document');

			$this->load->language('sale/purchase');

			$data['base'] = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$language_items = array(
				'title_purchase',
				'text_address',
				'text_admin',
				'text_comment',
				'text_contact_person',
				'text_customer',
				'text_day_date',
				'text_event_date',
				'text_event_title',
				'text_finance',
				'text_hormat_kami',
				'text_reference_no',
				'text_mark',
				'text_no_results',
				'text_order_detail',
				'text_package',
				'text_product_detail',
				'text_sales',
				'text_slot',
				'text_tanda_tangan',
				'text_to',
				'text_total',
				'text_venue',
				'column_no',
				'column_description',
				'column_quantity',
				'column_price',
				'column_subtotal',
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			// $data['letter_head'] = HTTP_CATALOG . 'image/catalog/letter_head.png';

			if ($order_purchase_info['reference']) {
				$data['reference'] = $order_purchase_info['reference'];
			} else {
				$order_vendor_info = $this->model_sale_order->getOrderVendor($order_id, $vendor_id);

				$document_data = [
					'order_id'			=> $order_id,
					'client_type'		=> 'vendor',
					'document_type'		=> 'purchase',
					'client_id'			=> $order_vendor_info ? $order_vendor_info['order_vendor_id'] : 0,
					'update_idx'		=> $order_purchase_info['order_purchase_id']
				];

				$order_purchase_info['order_document_id'] = $this->model_sale_document->addOrderDocumentByType($document_data);

				$data['reference'] = $this->model_sale_document->getOrderDocument($order_purchase_info['order_document_id'])['reference'];
			}

			$date_in = $this->model_localisation_local_date->getInFormatDate($order_purchase_info['date_added']);
			$data['day_date'] = $date_in['day'] . '/' . $date_in['long_date'];

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

			# Vendor Data
			if ($vendor_id) {
				$this->load->model('catalog/vendor');

				$vendor_info = $this->model_catalog_vendor->getVendor($vendor_id);

				$data['name'] = $vendor_info['vendor_name'];
				$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($vendor_info['address'])));
				$data['contact_person'] = $vendor_info['contact_person'];
			} else {
				$data['name'] = $this->config->get('config_name');
				$data['address'] = '';
				$data['contact_person'] = '';
			}

			# Event Data
			if (!$order_info['title']) {
				$order_info['title'] = sprintf($this->language->get('text_atas_nama'), $order_info['firstname'] . ' ' . $order_info['lastname']);
			}
			$data['event_title'] = $order_info['title'];

			$primary_product_info = $this->model_sale_order->getOrderPrimaryProduct($order_id);
			$data['package'] = $primary_product_info['name'];

			$primary_attributes = $this->model_sale_order->getOrderAttributes($order_id, $primary_product_info['order_product_id']);
			foreach ($primary_attributes as $attribute) {
				if ($attribute['attribute'] == 'Venue') {
					$data['venue'] = $attribute['text'];
				}
			}

			$event_date_in = $this->model_localisation_local_date->getInFormatDate($order_info['event_date']);
			$data['event_date'] = $event_date_in['day'] . '/' . $event_date_in['long_date'];

			$data['customer'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$data['slot'] = $order_info['slot'];

			$this->load->model('user/user');
			$sales_info = $this->model_user_user->getUserByUsername($order_info['username']);

			$data['sales'] = $sales_info['firstname'] . ' ' . $sales_info['lastname'];

			# Purchase Product Data
			$data['purchase_products'] = array();

			$purchase_products = $this->model_sale_purchase->getOrderPurchaseProducts($order_purchase_info['order_purchase_id']);

			foreach ($purchase_products as $idx => $purchase_product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($order_id, $purchase_product['product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
					);
				}

				$attribute_data = array();

				$attributes = $this->model_sale_order->getOrderAttributes($order_id, $purchase_product['order_product_id']);

				foreach ($attributes as $attribute) {
					$attribute_data[$attribute['attribute_group']][] = array(
						'name'	=> $attribute['attribute'],
						'value'	=> $attribute['text']
					);
				}

				$data['products'][] = array(
					'no'		=> $idx + 1,
					'category'	=> $purchase_product['category'],
					'name'    	=> $purchase_product['name'],
					'option'  	=> $option_data,
					'attribute'	=> $attribute_data,
					'quantity'	=> $purchase_product['quantity'] . '&nbsp;' . $purchase_product['unit_class'],
					'price'    	=> $this->currency->format($purchase_product['price'], $this->session->data['currency']),
					'subtotal'	=> $this->currency->format($purchase_product['total'], $this->session->data['currency'])
				);
			}

			$data['total'] = $this->currency->format(array_sum(array_column($purchase_products, 'total')), $this->session->data['currency']);

			$data['comment'] = nl2br($order_purchase_info['comment']);

			$print = isset($this->request->get['print']) && $this->request->get['print'] == 1;
			$preview = $order_purchase_info['printed'] || !$print || !$this->user->hasPermission('modify', 'sale/purchase');
			// $preview = 0;// Development Purpose

			if ($preview) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_sale_document->editDocumentPrintStatus($order_purchase_info['order_document_id'], 1);
			}

			$this->response->setOutput($this->load->view('sale/purchase_order', $data));
		} else {
			return false;
		}
	}
}
