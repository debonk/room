<?php
class ControllerApiCart extends Controller {
	public function add() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['product'])) {
				$this->cart->clear();

				foreach ($this->request->post['product'] as $product) {
					if (isset($product['option'])) {
						$option = $product['option'];
					} else {
						$option = array();
					}

					$this->cart->add($product['product_id'], $product['quantity'], $product['price'], $option, 0, 0, $product['category']);
				}

				$json['success'] = $this->language->get('text_success');

				// unset($this->session->data['shipping_method']);
				// unset($this->session->data['shipping_methods']);
				// unset($this->session->data['payment_method']);
				// unset($this->session->data['payment_methods']);
			} elseif (isset($this->request->post['product_id'])) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					if (isset($this->request->post['option'])) {
						$option = array_filter($this->request->post['option']);
					} else {
						$option = array();
					}

					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}
					
					$this->load->model('catalog/category');
					$category = $this->model_catalog_category->getCategory($this->request->post['category_id']);
					
					if (!isset($json['error']['option'])) {
						if ($product_info['primary_type']) {
							$this->cart->clear();
						}
	
						$this->cart->add($this->request->post['product_id'], $product_info['minimum'], $product_info['price'], $option, 0, $this->request->post['primary_type'], $category['name']);
						
						$products_included = $this->model_catalog_product->getProductsIncluded($this->request->post['product_id']);
						
						foreach ($products_included as $product_included) {
							$product_included_category = $this->model_catalog_category->getCategory($this->model_catalog_product->getCategories($product_included['product_id'])[0]['category_id']);
					
							$this->cart->add($product_included['product_id'], $product_included['minimum'], $product_included['price'], array(), 0, $product_included['primary_type'], $product_included_category['name']);
						}
						
						$json['success'] = $this->language->get('text_success');

						// unset($this->session->data['shipping_method']);
						// unset($this->session->data['shipping_methods']);
						// unset($this->session->data['payment_method']);
						// unset($this->session->data['payment_methods']);
					}
				} else {
					$json['error']['store'] = $this->language->get('error_store');
				}
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

	public function edit() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->cart->update($this->request->post['key'], $this->request->post['quantity']);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
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

	public function remove() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Remove
			if (isset($this->request->post['key'])) {
				$this->cart->remove($this->request->post['key']);

				unset($this->session->data['vouchers'][$this->request->post['key']]);

				$json['success'] = $this->language->get('text_success');

				// unset($this->session->data['shipping_method']);
				// unset($this->session->data['shipping_methods']);
				// unset($this->session->data['payment_method']);
				// unset($this->session->data['payment_methods']);
				unset($this->session->data['reward']);
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

	public function event() {
		$this->load->language('api/cart');

		// Delete past event in case there is an error
		unset($this->session->data['event']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'title',
				'event_date',
				'slot_id',
				'ceremony_id'
			);
			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if (empty($this->request->post['title'])) {
				$json['error']['title'] = $this->language->get('error_title');
			}

			//Validate event date, bisa ditambah validasi: tanggal event plg lambat 1 bulan sebelumnya, dst
			if (empty($this->request->post['event_date']) || trim($this->request->post['event_date']) == '0000-00-00') {
				$json['error']['event_date'] = $this->language->get('error_event_date');
			}

			if (empty($this->request->post['slot_id'])) {
				$json['error']['slot'] = $this->language->get('error_slot');
			}

			if (empty($this->request->post['ceremony_id'])) {
				$json['error']['ceremony'] = $this->language->get('error_ceremony');
			}

			$primary_products = $this->cart->getPrimaryProducts();
			
			if (count($primary_products) != 1) {
				$json['error'] = $this->language->get('error_primary_product');
			}
			
			if (!$json) {
				$processing_statuses = $this->config->get('config_processing_status');
				
				$slot_data = array();
				
				$this->load->model('checkout/order');
				
				$orders = $this->model_checkout_order->getOrdersByEventDate($this->request->post['event_date']);
				
				foreach ($orders as $order) {
					if ($order['order_id'] != $this->request->post['order_id'] && in_array($order['order_status_id'], $processing_statuses)) {
						$slot_idx = strtolower(substr($order['model'], -2, 2) . $order['slot_code']);
						$order_slot_info = $this->model_checkout_order->getSlotUsed($slot_idx);
						
						$slot_data = array_merge($slot_data, $order_slot_info);
					}
				}
				
				$primary_product = current($primary_products);

				$this->load->model('localisation/slot');
				$cart_slot = $this->model_localisation_slot->getSlot($this->request->post['slot_id'])['code'];

				$cart_slot_idx = strtolower(substr($primary_product['model'], -2, 2) . $cart_slot);
				$cart_slot_info = $this->model_checkout_order->getSlotUsed($cart_slot_idx);
				
				if ($cart_slot_info != array_diff($cart_slot_info, $slot_data)) {
					$json['error']['warning'] = $this->language->get('error_slot_used');
				}
			}
			
			if (!$json) {
				$this->session->data['event'] = array(
					'title'   	   => $this->request->post['title'],
					'event_date'   => $this->request->post['event_date'],
					'slot_id'      => $this->request->post['slot_id'],
					'ceremony_id'  => $this->request->post['ceremony_id']
				);

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

	public function products() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}
			
			// Event
			$json['event'] = array();
			
			if (isset($this->session->data['event'])) {
				$this->load->model('localisation/slot');
				$this->load->model('localisation/ceremony');
				$this->load->model('localisation/local_date');
				
				$json['event'] = array(
					'title'			=> $this->session->data['event']['title'],
					'event_date'	=> $this->model_localisation_local_date->getInFormatDate($this->session->data['event']['event_date'])['long_date'],
					'slot_id'		=> $this->session->data['event']['slot_id'],
					'slot'			=> $this->model_localisation_slot->getSlot($this->session->data['event']['slot_id'])['name'],
					'ceremony_id'	=> $this->session->data['event']['ceremony_id'],
					'ceremony'		=> $this->model_localisation_ceremony->getCeremony($this->session->data['event']['ceremony_id'])['name']
				);
			}
			
			// Products
			$json['products'] = array();

			$products = $this->cart->getProducts();
			
			$primary_products = $this->cart->getPrimaryProducts();
			
			if (count($primary_products) != 1) {
				$json['error'] = $this->language->get('error_primary_product');
			}
			
			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
				
				$attribute_data = array();

				foreach ($product['attribute'] as $attribute) {
					$attribute_data[] = $attribute['attribute'] . ': ' . $attribute['text'];
				}
				
				$json['products'][] = array(
					'cart_id'    	=> $product['cart_id'],
					'product_id' 	=> $product['product_id'],
					'name'       	=> $product['name'],
					'model'      	=> $product['model'],
					'primary_type'	=> $product['primary_type'],
					'category'		=> $product['category'],
					'option'     	=> $option_data,
					'attribute'     => $attribute_data,
					'quantity'   	=> $product['quantity'],
					'unit_class'   	=> $product['unit_class'],
					'stock'      	=> $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'shipping'   	=> $product['shipping'],
					'base_price'    => $product['price'],
					'price'      	=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
					'total'      	=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
					'reward'     	=> $product['reward']
				);
			}
			
			array_multisort(array_column($json['products'], 'primary_type'), SORT_DESC, $json['products']);			

			// Voucher
/* 			$json['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$json['vouchers'][] = array(
						'code'             => $voucher['code'],
						'description'      => $voucher['description'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'price'            => $this->currency->format($voucher['amount'], $this->session->data['currency']),			
						'amount'           => $voucher['amount']
					);
				}
			}
 */
			// Totals
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

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$json['totals'] = array();

			foreach ($totals as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
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
}
