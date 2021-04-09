<?php
class ControllerApiOrder extends Controller
{
	public function add()
	{
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (empty($this->request->post['user_id'])) {
			$json['error'] = $this->language->get('error_user_not_found');
		} else {
			// Customer
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Event
			if (!isset($this->session->data['event'])) {
				$json['error'] = $this->language->get('error_event');
			}

			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Payment Method
			if (!$json && !empty($this->request->post['payment_method'])) {
				if (empty($this->session->data['payment_methods'])) {
					$json['error'] = $this->language->get('error_no_payment');
				} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
					$json['error'] = $this->language->get('error_payment_method');
				}

				if (!$json) {
					$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
				}
			}

			if (!isset($this->session->data['payment_method'])) {
				$json['error'] = $this->language->get('error_payment_method');
			}

			// Shipping
			if ($this->cart->hasShipping()) { //Unused
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_shipping_address');
				}

				// Shipping Method
				if (!$json && !empty($this->request->post['shipping_method'])) {
					if (empty($this->session->data['shipping_methods'])) {
						$json['error'] = $this->language->get('error_no_shipping');
					} else {
						$shipping = explode('.', $this->request->post['shipping_method']);

						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
							$json['error'] = $this->language->get('error_shipping_method');
						}
					}

					if (!$json) {
						$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
					}
				}

				// Shipping Method
				if (!isset($this->session->data['shipping_method'])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Cart
			if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['error'] = $this->language->get('error_stock');
			}

			// Validate primary product requirements.
			$primary_products = $this->cart->getPrimaryProducts();

			if (count($primary_products) != 1) {
				$json['error'] = $this->language->get('error_primary_product');
			}

			// Validate minimum quantity requirements.
			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

					break;
				}
			}

			if (!$json) {
				$order_data = array();

				// $inv_prefix = current($primary_products)['model'];
				// $inv_prefix .= '-' . date('y/m', strtotime($this->session->data['event']['event_date'])) . '-';

				// $this->load->model('localisation/ceremony');
				// $ceremony_code = $this->model_localisation_ceremony->getCeremony($this->session->data['event']['ceremony_id'])['code'];

				// $inv_prefix .= $ceremony_code . '-';

				// $inv_prefix = str_ireplace('{YEAR}',date('Y', strtotime($this->session->data['event']['event_date'])),$this->config->get('config_invoice_prefix'));

				// Store Details
				// $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				// $order_data['invoice_prefix'] = $inv_prefix;
				$order_data['invoice_prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_invoice_prefix'));
				$order_data['store_id'] = $this->config->get('config_store_id');
				$order_data['store_name'] = $this->config->get('config_name');
				$order_data['store_url'] = $this->config->get('config_url');

				// Customer Details
				$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
				$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['customer']['firstname'];
				$order_data['lastname'] = $this->session->data['customer']['lastname'];
				$order_data['id_no'] = $this->session->data['customer']['id_no'];
				$order_data['email'] = $this->session->data['customer']['email'];
				$order_data['telephone'] = $this->session->data['customer']['telephone'];
				$order_data['fax'] = $this->session->data['customer']['fax'];
				$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

				// Event Details
				$order_data['title'] = $this->session->data['event']['title'];
				$order_data['event_date'] = $this->session->data['event']['event_date'];
				$order_data['slot_id'] = $this->session->data['event']['slot_id'];
				// $order_data['ceremony_id'] = $this->session->data['event']['ceremony_id'];

				// Payment Details
				$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
				$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
				$order_data['payment_company'] = $this->session->data['payment_address']['company'];
				$order_data['payment_profession'] = $this->session->data['payment_address']['profession'];
				$order_data['payment_position'] = $this->session->data['payment_address']['position'];
				$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
				$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
				$order_data['payment_city'] = $this->session->data['payment_address']['city'];
				$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
				$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
				$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$order_data['payment_country'] = $this->session->data['payment_address']['country'];
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

				if (isset($this->session->data['payment_method']['title'])) {
					$order_data['payment_method'] = $this->session->data['payment_method']['title'];
				} else {
					$order_data['payment_method'] = '';
				}

				if (isset($this->session->data['payment_method']['code'])) {
					$order_data['payment_code'] = $this->session->data['payment_method']['code'];
				} else {
					$order_data['payment_code'] = '';
				}

				// Shipping Details
				if ($this->cart->hasShipping()) { //Unused
					$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
					$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
					$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
					$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
					$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
					$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
					$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
					$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
					$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
					$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
					$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
					$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
					$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

					if (isset($this->session->data['shipping_method']['title'])) {
						$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
					} else {
						$order_data['shipping_method'] = '';
					}

					if (isset($this->session->data['shipping_method']['code'])) {
						$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
					} else {
						$order_data['shipping_code'] = '';
					}
				} else {
					$order_data['shipping_firstname'] = '';
					$order_data['shipping_lastname'] = '';
					$order_data['shipping_company'] = '';
					$order_data['shipping_address_1'] = '';
					$order_data['shipping_address_2'] = '';
					$order_data['shipping_city'] = '';
					$order_data['shipping_postcode'] = '';
					$order_data['shipping_zone'] = '';
					$order_data['shipping_zone_id'] = '';
					$order_data['shipping_country'] = '';
					$order_data['shipping_country_id'] = '';
					$order_data['shipping_address_format'] = '';
					$order_data['shipping_custom_field'] = array();
					$order_data['shipping_method'] = '';
					$order_data['shipping_code'] = '';
				}

				// Products
				$order_data['products'] = array();

				foreach ($products as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'type'                    => $option['type']
						);
					}

					$order_data['products'][] = array(
						'product_id'   => $product['product_id'],
						'name'         => $product['name'],
						'model'        => $product['model'],
						'option'       => $option_data,
						'download'     => $product['download'],
						'quantity'     => $product['quantity'],
						'unit_class'   => $product['unit_class'],
						'category_id'  => $product['category_id'],
						'category'     => $product['category'],
						'subtract'     => $product['subtract'],
						'price'        => $product['price'],
						'total'        => $product['total'],
						'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'       => $product['reward'],
						'slot_prefix'  => $product['slot_prefix'],
						'primary_type' => $product['primary_type']
					);
				}

				// Gift Voucher - Unused
				$order_data['vouchers'] = array();

				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$order_data['vouchers'][] = array(
							'description'      => $voucher['description'],
							'code'             => token(10),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $voucher['amount']
						);
					}
				}

				// Order Totals
				$this->load->model('extension/extension');

				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;

				// Because __call can not keep var references so we put them into an array.
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($total_data['totals'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data['totals']);

				$order_data = array_merge($order_data, $total_data);

				if (isset($this->request->post['comment'])) {
					$order_data['comment'] = $this->request->post['comment'];
				} else {
					$order_data['comment'] = '';
				}

				if (isset($this->request->post['affiliate_id'])) {
					$subtotal = $this->cart->getSubTotal();

					// Affiliate
					$this->load->model('affiliate/affiliate');

					$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

					if ($affiliate_info) {
						$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
						$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
					}

					// Marketing
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';
				}

				$order_data['language_id'] = $this->config->get('config_language_id');
				$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
				$order_data['currency_code'] = $this->session->data['currency'];
				$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
				$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$order_data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$order_data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$order_data['accept_language'] = '';
				}

				$order_data['user_id'] = $this->request->post['user_id'];

				$this->load->model('checkout/order');

				$json['order_id'] = $this->model_checkout_order->addOrder($order_data);

				// Set the order history
				$this->model_checkout_order->addOrderHistory($json['order_id'], $this->config->get('config_order_status_id'), '', 0, 0, $this->request->post['user_id']);

				if (isset($this->request->post['order_status_id']) && $this->request->post['order_status_id'] != $this->config->get('config_order_status_id')) {
					$this->model_checkout_order->addOrderHistory($json['order_id'], $this->request->post['order_status_id'], '', 0, 0, $this->request->post['user_id']);
				}

				$this->cart->clear();
				unset($this->session->data['event']);
				unset($this->session->data['customer']);
				unset($this->session->data['payment_address']);

				$json['success'] = $this->language->get('text_success');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit()
	{
		$this->load->language('api/order');

		$json = array();

		switch ($json) {
			case false:
				if (!isset($this->session->data['api_id'])) {
					$json['error'] = $this->language->get('error_permission');

					break;
				}

				if (empty($this->request->post['user_id'])) {
					$json['error'] = $this->language->get('error_user_not_found');

					break;
				}

				$this->load->model('checkout/order');
				$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

				$order_info = $this->model_checkout_order->getOrder($order_id);

				if (!$order_info) {
					$json['error'] = $this->language->get('error_not_found');

					break;
				}

				# lock edit order if current order status is complete
				if ($this->config->get('config_lock_complete_order') && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
					$json['error'] = $this->language->get('error_status_complete');

					break;
				}

				// Customer
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');

					break;
				}

				// Event
				if (!isset($this->session->data['event'])) {
					$json['error'] = $this->language->get('error_event');

					break;
				}

				// Payment Address
				if (!isset($this->session->data['payment_address'])) {
					$json['error'] = $this->language->get('error_payment_address');

					break;
				}

				// Payment Method
				if (!empty($this->request->post['payment_method'])) {
					if (empty($this->session->data['payment_methods'])) {
						$json['error'] = $this->language->get('error_no_payment');

						break;
					} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
						$json['error'] = $this->language->get('error_payment_method');

						break;
					}

					$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
				}

				if (!isset($this->session->data['payment_method'])) {
					$json['error'] = $this->language->get('error_payment_method');

					break;
				}

				// Cart
				if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_stock');

					break;
				}

				// Validate primary product requirements.
				$primary_products = $this->cart->getPrimaryProducts();

				if (count($primary_products) != 1) {
					$json['error'] = $this->language->get('error_primary_product');

					break;
				}

				// Validate minimum quantity requirements.
				$products = $this->cart->getProducts();

				foreach ($products as $product) {
					$product_total = 0;

					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}

					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				# If new status is complete then check for requirement
				if (in_array($this->request->post['order_status_id'], $this->config->get('config_complete_status'))) {
					$this->load->model('localisation/order_status');
					$this->load->model('accounting/transaction');

					$order_status_info = $this->model_localisation_order_status->getOrderStatus($this->request->post['order_status_id']);
					$transaction_type_info = $this->model_accounting_transaction->getTransactionType($order_status_info['transaction_type_id']);

					if ($transaction_type_info && $transaction_type_info['transaction_label'] == 'complete') {
						if ($transaction_type_info['transaction_label'] == 'complete') {
							$filter_category_label = ['order', 'purchase', 'deposit'];
	
							foreach ($filter_category_label as $category_label) {
								$filter_summary_data = [
									'category_label'	=> $category_label,
									'group'				=> 'client_id'
								];
			
								$transactions_total = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_summary_data);
	
								if (!empty($transactions_total)) {
									$json['error'] = sprintf($this->language->get('error_amount_balance'), $this->language->get('text_category_' . $category_label));
	
									break;
								}
							}
						}
					}
				}

			default:
				break;
		}

		if (!$json) {
			$order_data = array();

			$order_data['invoice_prefix'] = str_ireplace('{YEAR}', date('Y', strtotime($this->session->data['event']['event_date'])), $this->config->get('config_invoice_prefix'));

			if ($order_data['invoice_prefix'] == $order_info['invoice_prefix']) {
				$order_data['invoice_no'] = $order_info['invoice_no'];
			} else {
				$order_data['invoice_no'] = 0;
			}

			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$order_data['store_url'] = $this->config->get('config_url');

			// Customer Details
			$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
			$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['customer']['firstname'];
			$order_data['lastname'] = $this->session->data['customer']['lastname'];
			$order_data['id_no'] = $this->session->data['customer']['id_no'];
			$order_data['email'] = $this->session->data['customer']['email'];
			$order_data['telephone'] = $this->session->data['customer']['telephone'];
			$order_data['fax'] = $this->session->data['customer']['fax'];
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

			// Event Details
			$order_data['title'] = $this->session->data['event']['title'];
			$order_data['event_date'] = $this->session->data['event']['event_date'];
			$order_data['slot_id'] = $this->session->data['event']['slot_id'];
			// $order_data['ceremony_id'] = $this->session->data['event']['ceremony_id'];

			// Payment Details
			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = $this->session->data['payment_address']['company'];
			$order_data['payment_profession'] = $this->session->data['payment_address']['profession'];
			$order_data['payment_position'] = $this->session->data['payment_address']['position'];
			$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = $this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = $this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			// Shipping Details - Unused
			// if ($this->cart->hasShipping()) {
			// 	$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
			// 	$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
			// 	$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
			// 	$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
			// 	$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
			// 	$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
			// 	$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
			// 	$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
			// 	$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
			// 	$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
			// 	$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
			// 	$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
			// 	$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

			// 	if (isset($this->session->data['shipping_method']['title'])) {
			// 		$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
			// 	} else {
			// 		$order_data['shipping_method'] = '';
			// 	}

			// 	if (isset($this->session->data['shipping_method']['code'])) {
			// 		$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
			// 	} else {
			// 		$order_data['shipping_code'] = '';
			// 	}
			// } else {
			$order_data['shipping_firstname'] = '';
			$order_data['shipping_lastname'] = '';
			$order_data['shipping_company'] = '';
			$order_data['shipping_address_1'] = '';
			$order_data['shipping_address_2'] = '';
			$order_data['shipping_city'] = '';
			$order_data['shipping_postcode'] = '';
			$order_data['shipping_zone'] = '';
			$order_data['shipping_zone_id'] = '';
			$order_data['shipping_country'] = '';
			$order_data['shipping_country_id'] = '';
			$order_data['shipping_address_format'] = '';
			$order_data['shipping_custom_field'] = array();
			$order_data['shipping_method'] = '';
			$order_data['shipping_code'] = '';
			// }

			// Products
			$order_data['products'] = array();

			foreach ($products as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id'   => $product['product_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $option_data,
					'download'     => $product['download'],
					'quantity'     => $product['quantity'],
					'unit_class'   => $product['unit_class'],
					'category_id'  => $product['category_id'],
					'category'     => $product['category'],
					'subtract'     => $product['subtract'],
					'price'        => $product['price'],
					'total'        => $product['total'],
					'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'       => $product['reward'],
					'slot_prefix'  => $product['slot_prefix'],
					'primary_type' => $product['primary_type']
				);
			}

			// Gift Voucher - Unused
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			// Order Totals
			$this->load->model('extension/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array. 
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($total_data['totals'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data['totals']);

			$order_data = array_merge($order_data, $total_data);

			if (isset($this->request->post['comment'])) {
				$order_data['comment'] = $this->request->post['comment'];
			} else {
				$order_data['comment'] = '';
			}

			if (isset($this->request->post['affiliate_id'])) {
				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('affiliate/affiliate');

				$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			$this->model_checkout_order->editOrder($order_id, $order_data);

			# Set the order history
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), '', 0, 0, $this->request->post['user_id']);

			if (isset($this->request->post['order_status_id']) && $this->request->post['order_status_id'] != $this->config->get('config_order_status_id')) {
				$payment_phases = $this->model_checkout_order->getPaymentPhases($order_id);
				foreach ($payment_phases as $payment_phase) {
					if ($payment_phase['paid_status']) {
						$order_status_id = $payment_phase['order_status_id'];
					}
				}

				if ($this->config->get('config_order_status_id') != $order_status_id) {
					$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', 0, 0, $this->request->post['user_id']);
				}
			}

			$this->cart->clear();
			unset($this->session->data['event']);
			unset($this->session->data['customer']);
			unset($this->session->data['payment_address']);

			$json['success'] = $this->language->get('text_success');

			$json['order_id'] = $order_id;
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete()
	{
		$this->load->language('api/order');

		$json = array();

		$delete_protection_status = true; #Tidak diijinkan menghapus order

		if ($delete_protection_status || !isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');
			$this->load->model('accounting/transaction');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);
			$transactions = $this->model_accounting_transaction->getTransactionsByOrderId($order_id);

			if (!$order_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($this->config->get('config_lock_complete_order') && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) { # lock status update if current order status is complete
				$json['error'] = $this->language->get('error_status_complete');
			} elseif ($transactions) {
				$json['error'] = $this->language->get('error_transaction');
			} else {
				$this->model_checkout_order->deleteOrder($order_id);

				$json['success'] = $this->language->get('text_success');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history()
	{
		$this->load->language('api/order');

		$json = [];

		switch ($json) {
			case false:
				if (!isset($this->session->data['api_id'])) {
					$json['error'] = $this->language->get('error_permission');

					break;
				}

				$this->load->model('user/user');
				$user_group_id = $this->model_user_user->getUserGroupId($this->request->post['user_id']);

				if (empty($user_group_id)) {
					$json['error'] = $this->language->get('error_user_not_found');

					break;
				}

				$this->load->model('localisation/order_status');
				$order_status_info = $this->model_localisation_order_status->getOrderStatus($this->request->post['order_status_id']);

				if (empty($order_status_info['user_group_modify']) || empty($user_group_id) || !in_array($user_group_id, json_decode($order_status_info['user_group_modify'], true))) {
					$json['error'] = $this->language->get('error_user_permission');

					break;
				}

				# Add keys for missing post vars
				$keys = array(
					'order_status_id',
					'notify',
					'override',
					'comment',
					'user_id'
				);

				foreach ($keys as $key) {
					if (!isset($this->request->post[$key])) {
						$this->request->post[$key] = '';
					}
				}

				$this->load->model('checkout/order');

				$order_id = isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;

				$order_info = $this->model_checkout_order->getOrder($order_id);

				if (!$order_info) {
					$json['error'] = $this->language->get('error_not_found');

					break;
				}

				# lock status update if current order status is complete
				if ($this->config->get('config_lock_complete_order') && in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
					$json['error'] = $this->language->get('error_status_complete');

					break;
				}

				$this->load->model('accounting/transaction');
				$transaction_type_info = $this->model_accounting_transaction->getTransactionType($order_status_info['transaction_type_id']);

				if (!$transaction_type_info) {
					break; //Tidak ada transaksi utk dijalankan
				}

				$transaction_info = $this->model_accounting_transaction->getTransactionByTransactionTypeId($order_status_info['transaction_type_id'], ['order_id' => $order_id]);

				if ($transaction_info) {
					$json['error'] = $this->language->get('error_transaction_exist');

					break;
				}

				# If current order status is not complete but new status is complete then check for transaction
				if (!in_array($order_info['order_status_id'], $this->config->get('config_complete_status')) && in_array($this->request->post['order_status_id'], $this->config->get('config_complete_status'))) {
					if ($transaction_type_info['transaction_label'] == 'complete') {
						$filter_category_label = ['order', 'purchase', 'deposit'];

						foreach ($filter_category_label as $category_label) {
							$filter_summary_data = [
								'category_label'	=> $category_label,
								'group'				=> 'client_id'
							];
		
							$transactions_total = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_summary_data);

							if (!empty($transactions_total)) {
								$json['error'] = sprintf($this->language->get('error_amount_balance'), $this->language->get('text_category_' . $category_label));

								break;
							}
						}
					}
				}

			default:
				break;
		}

		if (!$json) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override'], $this->request->post['user_id']);

			$json['success'] = $this->language->get('text_success');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function expired()
	{
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!isset($this->request->post['user_id'])) {
				$this->request->post['user_id'] = 0;
			}

			$this->load->model('user/user');

			if (!$this->model_user_user->checkUser($this->request->post['user_id'])) {
				$json['error'] = $this->language->get('error_user_not_found');
			} else {
				if (isset($this->request->get['order_id'])) {
					$order_id = $this->request->get['order_id'];
				} else {
					$order_id = 0;
				}

				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);

				if (!$order_info) {
					$json['error'] = $this->language->get('error_not_found');
				}
			}
		}

		if (!$json) {
			if (in_array($order_info['order_status_id'], $this->config->get('config_processing_status'))) {
				$payment_phases = $this->model_checkout_order->getPaymentPhases($order_id);

				$expired = false;

				foreach ($payment_phases as $payment_phase) {
					if ($payment_phase['limit_status'] == 'expired' && !$expired) {
						$expired = $payment_phase['auto_expired'];
					}
				}

				if (!$expired) {
					$json['error'] = $this->language->get('error_action');
				}
			} else {
				$json['error'] = $this->language->get('error_status');
			}
		}

		if (!$json) {
			$this->request->post['order_status_id'] = $this->config->get('config_expired_status_id');

			// Add keys for missing post vars
			$keys = array(
				'date',
				'amount',
				'notify',
				'override',
				'comment',
				'payment_method'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			$this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override'], $this->request->post['user_id']);
			// $this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override'], $this->request->post['date'], $this->request->post['amount'], $this->request->post['user_id'], $this->request->post['payment_method']);

			$json['success'] = $this->language->get('text_success');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	//used for order info from external domain
	public function info()
	{
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				$json['order'] = $order_info;

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/* 	public function test() {
		$order_id = 18;
		$order_status_id = 1;
		$comment = 'Komentar';
		$notify = true;
		$override = false;
	
		$this->load->model('checkout/order');
		$data =	$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify);
		
		print_r(nl2br($data));
		print_r( '<br>');
		// $this->response->setOutput($this->load->view('mail/order', $data));
	}
 */
}
