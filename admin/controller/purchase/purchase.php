<?php
class ControllerPurchasePurchase extends Controller
{
	private $error = array();
	private $filter_items = array(
		'date_start',
		'date_end',
		'supplier_name',
		'invoice',
		'order_id',
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
		$this->load->language('purchase/purchase');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('purchase/purchase');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase');
		$this->load->model('localisation/local_date');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		$this->model_purchase_purchase->addPurchase($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['purchase'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('purchase/purchase');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase');
		$this->load->model('localisation/local_date');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_purchase->editPurchase($this->request->get['purchase_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['purchase'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('purchase/purchase');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $purchase_id) {
				$this->model_purchase_purchase->deletePurchase($purchase_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['purchase'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_confirm',
			'text_total',
			'text_no_results',
			'text_success',
			'text_select',
			'text_none',
			'column_date_added',
			'column_description',
			'column_invoice',
			'column_supplier_name',
			'column_total',
			'column_username',
			'column_action',
			'entry_date_start',
			'entry_date_end',
			'entry_invoice',
			'entry_order_id',
			'entry_supplier_name',
			'entry_username',
			'button_filter',
			'button_add',
			'button_edit',
			// 'button_edit_lock',
			// 'button_edit_unlock',
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
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = '';
			}
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_added';
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

		if (isset($this->request->get['purchase'])) {
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
			'href' => $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('purchase/purchase/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('purchase/purchase/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['purchases'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter'	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$purchase_count = $this->model_purchase_purchase->getPurchasesCount($filter_data);
		$purchase_total = $this->model_purchase_purchase->getPurchasesTotal($filter_data);

		$results = $this->model_purchase_purchase->getPurchases($filter_data);

		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$invoice = '#' . $result['order_id'] . ': ' . $result['invoice'];
				$order_url = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true);
			} else {
				$invoice = $result['invoice'];
				$order_url = '';
			}

			$purchase_product = $this->model_purchase_purchase->getPurchaseProducts($result['purchase_id']);
			$description = implode(', ', array_column($purchase_product, 'name'));

			$data['purchases'][] = array(
				'purchase_id' 	=> $result['purchase_id'],
				'date'	 		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'supplier_name'	=> $result['supplier_name'],
				'invoice'  		=> $invoice,
				'description'  	=> strlen($description) > 80 ? substr($description, 0, 78) . '..' : $description,
				'total'      	=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'order_url'     => $order_url,
				'edit'          => $this->url->link('purchase/purchase/edit', 'token=' . $this->session->data['token'] . '&purchase_id=' . $result['purchase_id'] . $url, true),
				'completed'    	=> $result['completed'],
				'username'      => $result['username']
				// 'unlock'		=> $result['edit_permission'],
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

		$data['sort_date_added'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, true);
		$data['sort_supplier_name'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=p.supplier_name' . $url, true);
		$data['sort_invoice'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=invoice' . $url, true);
		$data['sort_telephone'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=p.telephone' . $url, true);
		$data['sort_order'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=p.order_id' . $url, true);
		$data['sort_username'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $purchase_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($purchase_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($purchase_count - $limit)) ? $purchase_count : ((($page - 1) * $limit) + $limit), $purchase_count, ceil($purchase_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['total'] = $this->currency->format($purchase_total, $this->config->get('config_currency'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/purchase_list', $data));
	}

	public function getForm()
	{
		$data['text_form'] = !isset($this->request->get['purchase_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_loading',
			'text_no_results',
			'text_adjustment',
			'text_product',
			'text_product_list',
			'text_select',
			'text_subtotal',
			'text_total',
			'entry_comment',
			'entry_contact_person',
			'entry_supplier_name',
			'entry_date',
			'entry_invoice',
			'entry_price',
			'entry_product',
			'entry_quantity',
			'entry_telephone',
			'entry_total',
			'entry_unit_class',
			'column_action',
			'column_price',
			'column_product',
			'column_quantity',
			'column_total',
			'column_unit_class',
			'button_cancel',
			'button_product_add',
			'button_remove',
			'button_save',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = array(
			'warning',
			'supplier_name'
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['purchase'])) {
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
			'href' => $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['purchase_id'])) {
			$data['action'] = $this->url->link('purchase/purchase/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('purchase/purchase/edit', 'token=' . $this->session->data['token'] . '&purchase_id=' . $this->request->get['purchase_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('purchase/purchase', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['purchase_id'])) {
			$purchase_info = $this->model_purchase_purchase->getPurchase($this->request->get['purchase_id']);
		}

		if (!empty($purchase_info)) {
			$data['invoice'] = $purchase_info['invoice_prefix'] . str_pad($purchase_info['invoice_no'], 4, 0, STR_PAD_LEFT);
		} else {
			$data['invoice'] = '-';
		}

		if (!empty($purchase_info)) {
			$local_date_added = $this->model_localisation_local_date->getInFormatDate($purchase_info['date_added']);
		} else {
			$local_date_added = $this->model_localisation_local_date->getInFormatDate();
		}
		$data['date'] = $local_date_added['day'] . ', ' . $local_date_added['long_date'];

		if (isset($this->request->post['supplier_id'])) {
			$data['supplier_id'] = $this->request->post['supplier_id'];
		} elseif (!empty($purchase_info)) {
			$data['supplier_id'] = $purchase_info['supplier_id'];
		} else {
			$data['supplier_id'] = 0;
		}

		if (isset($this->request->post['supplier_name'])) {
			$data['supplier_name'] = $this->request->post['supplier_name'];
		} elseif (!empty($purchase_info)) {
			$data['supplier_name'] = $purchase_info['supplier_name'];
		} else {
			$data['supplier_name'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($purchase_info)) {
			$data['telephone'] = $purchase_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['contact_person'])) {
			$data['contact_person'] = $this->request->post['contact_person'];
		} elseif (!empty($purchase_info)) {
			$data['contact_person'] = $purchase_info['contact_person'];
		} else {
			$data['contact_person'] = '';
		}

		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($purchase_info)) {
			$data['order_id'] = $purchase_info['order_id'];
		} else {
			$data['order_id'] = 0;
		}

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($purchase_info)) {
			$data['comment'] = $purchase_info['comment'];
		} else {
			$data['comment'] = '';
		}

		if (isset($this->request->post['adjustment'])) {
			$data['adjustment'] = $this->request->post['adjustment'];
		} elseif (!empty($purchase_info)) {
			$data['adjustment'] = $purchase_info['adjustment'];
		} else {
			$data['adjustment'] = 0;
		}

		if (isset($this->request->post['product'])) {
			$purchase_products = $this->request->post['product'];
		} elseif (!empty($purchase_info)) {
			$purchase_products = $this->model_purchase_purchase->getPurchaseProducts($this->request->get['purchase_id']);
		} else {
			$purchase_products = array();
		}

		$data['purchase_products'] = [];

		foreach ($purchase_products as $purchase_product) {
			$option_data = array();
			$attribute_data = array();
			$fixed = false;

			if (!empty($data['order_id'])) {
				$this->load->model('sale/order');

				$order_product = $this->model_sale_order->getOrderProduct($data['order_id'], $purchase_product['product_id']);

				if ($order_product) {
					$options = $this->model_sale_order->getOrderOptions($data['order_id'], $order_product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $option['value'],
								'type'  => $option['type']
							);
						} else {
							$this->load->model('tool/upload');
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$option_data[] = array(
									'name'  => $option['name'],
									'value' => $upload_info['name'],
									'type'  => $option['type'],
									'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
								);
							}
						}
					}

					$attributes = $this->model_sale_order->getOrderAttributes($data['order_id'], $order_product['order_product_id']);

					foreach ($attributes as $attribute) {
						$attribute_data[] = array(
							'name'	=> $attribute['attribute'],
							'value'	=> $attribute['text']
						);
					}
					
					$fixed = true;
				}
			}

			if (!empty($data['product_id']) && !$fixed) {
				$this->load->model('catalog/product');

				$attributes = $this->model_catalog_product->getProductAttributes($purchase_product['product_id']);

				foreach ($attributes as $attribute) {
					$attribute_data[] = array(
						'name'	=> $attribute['product_attribute_description'][$this->config->get('config_language_id')]['attribute'],
						'value'	=> $attribute['product_attribute_description'][$this->config->get('config_language_id')]['text']
					);
				}
			}

			$data['purchase_products'][] = array(
				'product_id' 	=> $purchase_product['product_id'],
				'name'    	 	=> $purchase_product['name'],
				'option'   		=> $option_data,
				'attribute'  	=> $attribute_data,
				'quantity'		=> $purchase_product['quantity'],
				'unit_class'	=> $purchase_product['unit_class'],
				'price'	   		=> $purchase_product['price'],
				'fixed'			=> $fixed
			);
		}
		// var_dump($purchase_products);
		// var_dump($data['purchase_products']);
		// die('---breakpoint---');

		$data['purchase_products_total'] = json_encode(array_column($purchase_products, 'total'));
		$data['purchase_product_idx'] = ($data['purchase_products'] ? max(array_keys($purchase_products)) + 1 : 0);


		// if (!empty($purchase_info)) {
		// $data['supplier_id'] = $purchase_info['supplier_id'];
		// $data['supplier'] = $purchase_info['supplier'];
		// $data['telephone'] = $purchase_info['telephone'];
		// $data['contact_person'] = $purchase_info['contact_person'];
		// $data['order_id'] = $purchase_info['order_id'];
		// $data['total'] = $purchase_info['total'];
		// $data['telephone'] = $purchase_info['telephone'];


		// Products
		// $data['purchase_products'] = array();

		// $products = $this->model_purchase_purchase->getPurchaseProducts($this->request->get['purchase_id']);

		// foreach ($products as $product) {
		// 	$data['purchase_products'][] = array(
		// 		'product_id'   => $product['product_id'],
		// 		'name'         => $product['name'],
		// 		'model'        => $product['model'],
		// 		'option'       => $this->model_purchase_purchase->getPurchaseOptions($this->request->get['purchase_id'], $product['purchase_product_id']),
		// 		'quantity'     => $product['quantity'],
		// 		'price'        => $product['price'],
		// 		'total'        => $product['total'],
		// 		'reward'       => $product['reward'],
		// 		'primary_type' => $product['primary_type'],
		// 		'category'     => $product['category'],
		// 	);
		// }


		// } else {
		// 	$data['address'] = '';
		// 	$data['email'] = '';
		// 	$data['order_id'] = 0;
		// 	$data['total'] = 0;

		// 	$data['purchase_products'] = array();

		// }

		$this->load->model('localisation/unit_class');
		$data['unit_classes'] = $this->model_localisation_unit_class->getUnitClasses();

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/purchase_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'purchase/purchase')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['supplier_name']) < 3) || (utf8_strlen($this->request->post['supplier_name']) > 32)) {
			$this->error['supplier_name'] = $this->language->get('error_supplier_name');
		}

		if (!isset($this->request->post['product'])) {
			$this->error['warning'] = $this->language->get('error_product');
		} else {
			foreach ($this->request->post['product'] as $product) {
				if ($product['quantity'] <= 0 || $product['unit_class'] == '' || (utf8_strlen($product['name']) < 3)) {
					$this->error['warning'] = $this->language->get('error_product');
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
		if (!$this->user->hasPermission('modify', 'purchase/purchase')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocompleteSupplier() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('purchase/supplier');

			$filter_data = array(
				'filter_supplier_name' 	=> $this->request->get['filter_name'],
				'start'       			=> 0,
				'limit'       			=> 0
			);

			$results = $this->model_purchase_supplier->getSuppliers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'supplier_id' 	=> $result['supplier_id'],
					'supplier_name' => strip_tags(html_entity_decode($result['supplier_name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['supplier_name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	// End
	public function createInvoiceNo()
	{
		$this->load->language('purchase/purchase');

		$json = array();

		if (!$this->user->hasPermission('modify', 'purchase/purchase')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['purchase_id'])) {
				$purchase_id = $this->request->get['purchase_id'];
			} else {
				$purchase_id = 0;
			}

			$this->load->model('purchase/purchase');

			$invoice_no = $this->model_purchase_purchase->createInvoiceNo($purchase_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addPurchaseVendor()
	{
		$this->load->language('purchase/purchase');

		$json = array();

		// if (!$this->user->hasPermission('modify', 'purchase/purchase')) { //Sementara dobel ijin sebelum membuat mini purchase oleh marketing
		if (!$this->user->hasPermission('modify', 'purchase/purchase') || !$this->user->hasPermission('modify', 'catalog/vendor')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (isset($this->request->get['purchase_id'])) {
				$purchase_id = $this->request->get['purchase_id'];
			} else {
				$purchase_id = 0;
			}

			$this->load->model('purchase/purchase');

			$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);
			$purchase_vendors = $this->model_purchase_purchase->getPurchaseVendors($purchase_id);

			if ($purchase_info) {
				if (!$purchase_info['invoice_no']) {
					$this->model_purchase_purchase->createInvoiceNo($purchase_id);
				}

				if (!in_array($this->request->post['vendor_id'], array_column($purchase_vendors, 'vendor_id'))) {
					$this->model_purchase_purchase->addPurchaseVendor($purchase_id, $this->request->post['vendor_id']);

					$this->load->model('catalog/vendor');

					$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['vendor_id']);

					$json['title'] = $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'];
					$json['agreement_href'] = $this->url->link('purchase/purchase/vendorAgreement', 'token=' . $this->session->data['token'] . '&purchase_id=' . $purchase_id . '&vendor_id=' . $vendor_info['vendor_id'], true);
				}
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function deletePurchaseVendor()
	{
		$this->load->language('purchase/purchase');

		$json = array();

		// if (!$this->user->hasPermission('modify', 'purchase/purchase')) { //Sementara dobel ijin sebelum membuat mini purchase oleh marketing
		if (!$this->user->hasPermission('modify', 'purchase/purchase') || !$this->user->hasPermission('modify', 'catalog/vendor')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (isset($this->request->get['purchase_id'])) {
				$purchase_id = $this->request->get['purchase_id'];
			} else {
				$purchase_id = 0;
			}

			$this->load->model('purchase/purchase');
			$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);

			$this->load->model('accounting/transaction');

			$filter_data = array(
				'label'		=> 'vendor',
				'label_id'	=> $this->request->post['vendor_id']
			);

			$transaction_total = $this->model_accounting_transaction->getTransactionsTotalByPurchaseId($purchase_id, $filter_data);

			if ($purchase_info && ($transaction_total == 0)) {
				$this->model_purchase_purchase->deletePurchaseVendor($purchase_id, $this->request->post['vendor_id']);

				$this->load->model('catalog/vendor');

				$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['vendor_id']);

				$json['title'] 	= $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'];
			} else {
				$json['error'] = sprintf($this->language->get('error_vendor_transaction'), $this->currency->format($transaction_total, $purchase_info['currency_code'], $purchase_info['currency_value']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function vendorTransaction()
	{
		$this->load->language('purchase/purchase');

		$language_items = array(
			'text_no_results',
			'text_vendor',
			'text_vendor_transaction',
			'text_vendor_transaction_add',
			'text_loading',
			'text_select',
			'column_telephone',
			'column_email',
			'column_vendor_total',
			'column_date',
			'column_date_added',
			'column_vendor',
			'column_payment_method',
			'column_description',
			'column_amount',
			'column_username',
			'entry_vendor',
			'entry_date',
			'entry_payment_method',
			'entry_description',
			'entry_amount',
			'help_amount',
			'button_receipt',
			'button_transaction_add',
			'button_vendor_remove',
			'button_admission'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['purchase_id'])) {
			$purchase_id = $this->request->get['purchase_id'];
		} else {
			$purchase_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$this->load->model('purchase/purchase');
		$this->load->model('accounting/transaction');
		$this->load->model('catalog/vendor');

		$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);

		$data['purchase_vendors_summary'] = array();

		$vendors_summary = $this->model_accounting_transaction->getTransactionsLabelSummaryByPurchaseId($purchase_id, 'vendor');

		foreach ($vendors_summary as $vendor_summary) {
			$vendor_info = $this->model_catalog_vendor->getVendor($vendor_summary['label_id']);

			$data['purchase_vendors_summary'][] = array(
				'title' 	=> $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'],
				'telephone'	=> $vendor_info['telephone'],
				'email'		=> $vendor_info['email'],
				'href'		=> $this->url->link('catalog/vendor/edit', 'token=' . $this->session->data['token'] . '&vendor_id=' . $vendor_info['vendor_id'], true),
				'total'		=> $this->currency->format($vendor_summary['total'], $purchase_info['currency_code'], $purchase_info['currency_value'])
			);
		}

		$data['vendor_transactions'] = array();

		$filter_data = array(
			'label'		=> 'vendor',
			'sort'		=> 't.date',
			'purchase'		=> 'DESC',
			'start'		=> ($page - 1) * $limit,
			'limit'		=> $limit
		);

		$results = $this->model_accounting_transaction->getTransactionsByPurchaseId($purchase_id, $filter_data);

		foreach ($results as $result) {
			$data['vendor_transactions'][] = array(
				'date'				=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'customer_name'		=> $result['customer_name'],
				'payment_method'	=> $result['payment_method'],
				'description'		=> $result['description'],
				'amount'			=> $this->currency->format($result['amount'], $purchase_info['currency_code'], $purchase_info['currency_value']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'username'			=> $result['username'],
				'receipt'	 		=> $this->url->link('purchase/purchase/receipt', 'token=' . $this->session->data['token'] . '&transaction_id=' . (int)$result['transaction_id'], true),
				'print'				=> $result['printed'] ? 'preview' : 'print'
			);
		}

		$vendor_transaction_count = $this->model_accounting_transaction->getTransactionsCountByPurchaseId($purchase_id, $filter_data);

		$pagination = new Pagination();
		$pagination->total = $vendor_transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('purchase/purchase/vendorTransaction', 'token=' . $this->session->data['token'] . '&purchase_id=' . $this->request->get['purchase_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($vendor_transaction_count - $limit)) ? $vendor_transaction_count : ((($page - 1) * $limit) + $limit), $vendor_transaction_count, ceil($vendor_transaction_count / $limit));

		// Vendors
		$data['purchase_vendors'] = array();
		$data['payment_accounts'] = array();

		$purchase_vendors = $this->model_purchase_purchase->getPurchaseVendors($purchase_id);

		if ($purchase_vendors) {
			foreach ($purchase_vendors as $purchase_vendor) {
				$data['purchase_vendors'][] = array(
					'vendor_id' => $purchase_vendor['vendor_id'],
					'title' 	=> $purchase_vendor['vendor_name'] . ' - ' . $purchase_vendor['vendor_type']
				);
			}

			// Payment Methods
			$this->load->model('extension/extension');

			$payment_accounts = $this->model_extension_extension->getInstalled('payment');

			foreach ($payment_accounts as $payment_account) {
				if ($this->config->get($payment_account . '_status')) {
					$this->load->language('payment/' . $payment_account);

					$data['payment_accounts'][$payment_account] = $this->language->get('heading_title');
				}
			}
		}

		$data['token'] = $this->session->data['token'];
		$data['purchase_id'] = $purchase_id;

		$this->response->setOutput($this->load->view('purchase/purchase_vendor_transaction', $data));
	}

	public function transaction()
	{
		$this->load->language('purchase/purchase');

		$json = array();

		// if (!$this->user->hasPermission('modify', 'purchase/purchase')) { //Sementara dobel ijin sebelum membuat mini purchase oleh marketing
		if (!$this->user->hasPermission('modify', 'purchase/purchase') || !$this->user->hasPermission('modify', 'catalog/vendor')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (empty($this->request->post['transaction_vendor_id'])) {
				$json['error_transaction_vendor'] = $this->language->get('error_transaction_vendor');
			}

			if (empty($this->request->post['transaction_payment_code'])) {
				$json['error_transaction_payment_code'] = $this->language->get('error_transaction_payment_code');
			}

			if (empty($this->request->post['transaction_date'])) {
				$json['error_transaction_date'] = $this->language->get('error_transaction_date');
			}

			if ((utf8_strlen($this->request->post['transaction_description']) < 5) || (utf8_strlen($this->request->post['transaction_description']) > 256)) {
				$json['error_transaction_description'] = $this->language->get('error_transaction_description');
			}

			if (empty((float)$this->request->post['transaction_amount'])) {
				$json['error_transaction_amount'] = $this->language->get('error_transaction_amount');
			}

			if ($json) {
				$json['error'] = $this->language->get('error_warning');
			}
		}

		if (!$json) {
			if (isset($this->request->get['purchase_id'])) {
				$purchase_id = $this->request->get['purchase_id'];
			} else {
				$purchase_id = 0;
			}

			$this->load->model('purchase/purchase');

			$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);
			$purchase_vendors = $this->model_purchase_purchase->getPurchaseVendors($purchase_id);

			if ($purchase_info) {
				if (!in_array($this->request->post['transaction_vendor_id'], array_column($purchase_vendors, 'vendor_id'))) {
					$json['error_transaction_vendor'] = $this->language->get('error_vendor_not_found');
				}

				$this->load->model('accounting/account');

				$asset_id = $this->config->get($this->request->post['transaction_payment_code'] . '_asset_id');

				$asset_info = $this->model_accounting_account->getAccount($asset_id);

				if (empty($asset_info)) {
					$asset_id = $this->config->get('config_asset_account_id');

					$asset_replacement_info = $this->model_accounting_account->getAccount($asset_id);

					if (empty($asset_replacement_info)) {
						$json['error'] = $this->language->get('error_asset_not_found');
					}
				}
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		if (!$json) {
			$this->load->model('catalog/vendor');
			$this->load->model('accounting/transaction');

			$vendor_info = $this->model_catalog_vendor->getVendor($this->request->post['transaction_vendor_id']);

			$reference_no = str_ireplace('{YEAR}', date('Y', strtotime($this->request->post['transaction_date'])), $this->config->get('config_receipt_vendor_prefix'));

			$transaction_no_max = $this->model_accounting_transaction->getTransactionNoMax($reference_no);

			if ($transaction_no_max) {
				$transaction_no = $transaction_no_max + 1;
			} else {
				$transaction_no = $this->config->get('config_reference_start') + 1;
			}

			$this->load->language('payment/' . $this->request->post['transaction_payment_code']);

			$payment_method = $this->language->get('heading_title');

			$transaction_data = array(
				'purchase_id'			=> $purchase_id,
				'account_from_id'	=> $this->config->get('config_vendor_deposit_account_id'),
				'account_to_id'		=> $asset_id,
				'label'				=> 'vendor',
				'label_id'			=> $this->request->post['transaction_vendor_id'],
				'date' 				=> $this->request->post['transaction_date'],
				'payment_method'	=> $payment_method,
				'description' 		=> $this->request->post['transaction_description'],
				'amount' 			=> $this->request->post['transaction_amount'],
				'customer_name' 	=> $vendor_info['vendor_name'],
				'reference_no'		=> $reference_no,
				'transaction_no' 	=> $transaction_no
			);

			$this->model_accounting_transaction->addTransaction($transaction_data);

			$transaction_total = $this->model_accounting_transaction->getTransactionsTotalByPurchaseId($purchase_id, $transaction_data);

			$json['success'] = $this->language->get('text_transaction_added');

			if ($this->config->get('config_complete_status_required') && !in_array($purchase_info['purchase_status_id'], $this->config->get('config_complete_status'))) {
				$paid_off_status = false;
			} else {
				$paid_off_status = true;
			}

			if ($paid_off_status && $transaction_total >= $this->config->get('config_deposit')) {
				$json['admission_href'] = $this->url->link('purchase/purchase/admission', 'token=' . $this->session->data['token'] . '&purchase_id=' . $purchase_id . '&vendor_id=' . $this->request->post['transaction_vendor_id'], true);
			} else {
				$json['admission_href'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function agreement()
	{
		$this->load->model('purchase/purchase');

		if (isset($this->request->get['purchase_id'])) {
			$purchase_id = $this->request->get['purchase_id'];
		} else {
			$purchase_id = 0;
		}

		if (isset($this->request->get['print']) && $this->request->get['print'] == 1) {
			$print = 1;
		} else {
			$print = 0;
		}

		$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);

		if ($purchase_info) {
			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');

			$this->load->language('purchase/purchase');
			$this->load->language('purchase/purchase_print');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$language_items = array(
				'title_agreement',
				'text_mark',
				'text_invoice_no',
				'text_customer',
				'text_id_no',
				'text_customer_group',
				'text_company',
				'text_address',
				'text_profession',
				'text_position',
				'text_telephone',
				'text_day_date',
				'text_slot',
				'text_ceremony',
				'text_category',
				'text_product_name',
				'text_quantity',
				'text_amount',
				'text_info_tambahan',
				'text_layanan_tambahan',
				'text_purchase_vendor',
				'text_total',
				'text_telah_bayar',
				'text_snk',
				'text_transfer_ke',
				'text_belum_ppn',
				'text_ubah_tanggal',
				'text_pembatalan_acara',
				'text_pembatalan_1',
				'text_pembatalan_2',
				'text_pembatalan_3',
				'text_pihak_penyewa',
				'text_dst',
				'text_comment'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$data['letter_head'] = HTTP_CATALOG . 'image/catalog/letter_head.png';

			if ($purchase_info['invoice_no']) {
				$data['invoice_no'] = $purchase_info['invoice_prefix'] . str_pad($purchase_info['invoice_no'], 4, 0, STR_PAD_LEFT);
			} else {
				$data['invoice_no'] = $this->model_purchase_purchase->createInvoiceNo($purchase_id);
			}

			$store_info = $this->model_setting_setting->getSetting('config', $purchase_info['store_id']);

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

			$data['store_url'] = ltrim(rtrim($purchase_info['store_url'], '/'), 'http://');

			$date_added_in = $this->model_localisation_local_date->getInFormatDate($purchase_info['date_added']);

			$data['text_pada_hari'] = sprintf($this->language->get('text_pada_hari'), $date_added_in['day'], $date_added_in['long_date']);

			$address_format = array(
				$purchase_info['payment_address_1'] . ($purchase_info['payment_address_2'] ? ', ' . $purchase_info['payment_address_2'] : ''),
				$purchase_info['payment_city'],
				$purchase_info['payment_zone'],
				$purchase_info['payment_country'],
				$purchase_info['payment_postcode']
			);

			$address_format = implode(', ', $address_format);

			// Custom Fields
			$custom_fields = array();

			$this->load->model('customer/custom_field');

			foreach ($purchase_info['custom_field'] as $custom_field_id => $custom_field_value) {
				$custom_field_info = $this->model_customer_custom_field->getCustomField($custom_field_id);

				if ($custom_field_info) {
					$custom_fields[] = array(
						'name'			=> $custom_field_info['name'],
						'value'			=> $custom_field_value
					);
				}
			}

			foreach ($purchase_info['payment_custom_field'] as $custom_field_id => $custom_field_value) {
				$custom_field_info = $this->model_customer_custom_field->getCustomField($custom_field_id);

				if ($custom_field_info) {
					$custom_fields[] = array(
						'name'			=> $custom_field_info['name'],
						'value'			=> $custom_field_value
					);
				}
			}
			// END Custom Fields

			// Customer Data
			$data['customer'] = $purchase_info['firstname'] . ' ' . $purchase_info['lastname'];
			$data['id_no'] = $purchase_info['id_no'];
			$data['customer_group'] = $purchase_info['customer_group'];
			$data['company'] = $purchase_info['payment_company'];
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($address_format)));
			$data['profession'] = $purchase_info['payment_profession'];
			$data['position'] = $purchase_info['payment_position'];
			$data['telephone'] = $purchase_info['telephone'];
			$data['custom_fields'] = $custom_fields;

			$data['text_sewa_tempat'] = sprintf($this->language->get('text_sewa_tempat'), $data['store_name']);

			// Event Data
			$event_date_in = $this->model_localisation_local_date->getInFormatDate($purchase_info['event_date']);

			$data['event_date'] = $event_date_in['day'] . '/' . $event_date_in['long_date'];
			$data['slot'] = $purchase_info['slot'];
			$data['ceremony'] = $purchase_info['ceremony'];

			// Product Data
			$data['products'] = array();

			$products = $this->model_purchase_purchase->getPurchaseProducts($purchase_id);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_purchase_purchase->getPurchaseOptions($purchase_id, $product['purchase_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$this->load->model('tool/upload');
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

				$attributes = $this->model_purchase_purchase->getPurchaseAttributes($purchase_id, $product['purchase_product_id']);

				foreach ($attributes as $attribute) {
					$attribute_data[$attribute['attribute_group']][] = array(
						'name'	=> $attribute['attribute'],
						'value'	=> $attribute['text']
					);
				}

				$data['products'][$product['primary_type']][] = array(
					'category'	=> $product['category'],
					'name'    	=> $product['name'],
					'quantity'	=> $product['quantity'] . '&nbsp;' . $product['unit_class'],
					'option'  	=> $option_data,
					'attribute'	=> $attribute_data,
					'total'		=> $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $purchase_info['currency_code'], $purchase_info['currency_value'])
				);
			}

			// Vendors
			$data['purchase_vendors'] = array();

			$purchase_vendors = $this->model_purchase_purchase->getPurchaseVendors($purchase_id);

			foreach ($purchase_vendors as $purchase_vendor) {
				$data['purchase_vendors'][] = array(
					'type' => $purchase_vendor['vendor_type'],
					'name' => $purchase_vendor['vendor_name']
				);
			}

			// Total
			$data['totals'] = array();

			$totals = $this->model_purchase_purchase->getPurchaseTotals($purchase_id);

			foreach ($totals as $total) {
				$data['totals'][$total['code']] = array(
					'title' => $total['title'],
					'value' => $total['value'],
					'text'  => $this->currency->format($total['value'], $purchase_info['currency_code'], $purchase_info['currency_value'])
				);
			}

			if (isset($data['totals']['sub_total']['value']) && isset($data['totals']['total']['value']) && $data['totals']['sub_total']['value'] == $data['totals']['total']['value']) {
				unset($data['totals']['sub_total']);
			}

			// Transaction
			$this->load->model('accounting/transaction');

			$data['transactions'] = array();

			$filter_data = array(
				'label'		=> 'customer'
			);

			$transactions = $this->model_accounting_transaction->getTransactionsDescriptionSummaryByPurchaseId($purchase_id, $filter_data);

			$transactions_total = 0;

			foreach ($transactions as $transaction) {
				$date_in = $this->model_localisation_local_date->getInFormatDate($transaction['date']);

				$transactions_total += $transaction['amount'];

				$data['transactions'][] = array(
					'title'	=> $transaction['description'],
					'text'	=> $this->currency->format($transaction['amount'], $purchase_info['currency_code'], $purchase_info['currency_value']) . ' (' . $date_in['long_date'] . ')'
				);
			}

			$data['text_transactions'] = array();

			$down_payment = round($purchase_info['total'] * $this->config->get('config_down_payment_amount') / 100000, 0) * 1000;

			if ($down_payment > $transactions_total) {
				$data['text_transactions']['text_uang_muka'] = sprintf($this->language->get('text_uang_muka'), $this->currency->format($down_payment - $transactions_total, $purchase_info['currency_code'], $purchase_info['currency_value']));
				$data['text_transactions']['text_pelunasan'] = sprintf($this->language->get('text_pelunasan'), $this->currency->format($purchase_info['total'] - $down_payment, $purchase_info['currency_code'], $purchase_info['currency_value']));
			} elseif ($purchase_info['total'] > $transactions_total) {
				$data['text_transactions']['text_pelunasan'] = sprintf($this->language->get('text_pelunasan'), $this->currency->format($purchase_info['total'] - $transactions_total, $purchase_info['currency_code'], $purchase_info['currency_value']));
			}

			$no_rekening = $this->config->get($purchase_info['payment_code'] . '_bank' . $purchase_info['language_id']);
			$data['no_rekening'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($no_rekening)));

			$data['text_tukar_bukti'] = sprintf($this->language->get('text_tukar_bukti'), $data['store_owner']);
			$data['text_demikian'] = sprintf($this->language->get('text_demikian'), $data['store_owner']);

			// User Info
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUser($this->user->getId());

			$data['text_manajemen'] = $user_info['user_group'];
			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';

			$data['comment'] = nl2br($purchase_info['comment']);

			if ($purchase_info['printed'] || !$print || !$this->user->hasPermission('modify', 'purchase/purchase')) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_purchase_purchase->editPurchasePrintStatus($purchase_id, 1);
			}
		} else {
			return false;
		}

		$this->response->setOutput($this->load->view('purchase/purchase_agreement', $data));
	}

	public function receipt()
	{
		$this->load->model('purchase/purchase');
		$this->load->model('accounting/transaction');

		if (isset($this->request->get['transaction_id'])) {
			$transaction_id = $this->request->get['transaction_id'];
		} else {
			$transaction_id = '';
		}

		$transaction_info = $this->model_accounting_transaction->getTransaction($transaction_id);

		if ($transaction_info) {
			$purchase_id = $transaction_info['purchase_id'];
			$purchase_info = $this->model_purchase_purchase->getPurchase($purchase_id);

			$this->load->model('setting/setting');
			$this->load->model('localisation/local_date');

			$this->load->language('purchase/purchase');
			$this->load->language('purchase/purchase_print');

			if ($this->request->server['HTTPS']) {
				$data['base'] = HTTPS_SERVER;
			} else {
				$data['base'] = HTTP_SERVER;
			}

			$data['direction'] = $this->language->get('direction');
			$data['lang'] = $this->language->get('code');

			$language_items = array(
				'title_receipt',
				'text_mark',
				'text_invoice_no',
				'text_attn',
				'text_address',
				'text_sejumlah'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$store_info = $this->model_setting_setting->getSetting('config', $purchase_info['store_id']);

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

			$data['store_url'] = ltrim(rtrim($purchase_info['store_url'], '/'), 'http://');

			if ($transaction_info['amount'] < 0) {
				$income = false;

				$data['text_subject'] = $this->language->get('text_from');
			} else {
				$income = true;

				$data['text_subject'] = $this->language->get('text_to');
			}

			// SISTEM PENOMORAN DARI INVOICE
			// if ($purchase_info['invoice_no']) {
			// $data['invoice_no'] = $purchase_info['invoice_prefix'];
			// } else {
			// $data['invoice_no'] = $this->model_purchase_purchase->createInvoiceNo($purchase_id);
			// }
			// $data['invoice_no'] .= '-R' . str_pad($transaction_id,4,0,STR_PAD_LEFT);

			$data['invoice_no'] = $transaction_info['reference_no'] . str_pad($transaction_info['transaction_no'], 4, 0, STR_PAD_LEFT);

			// Event Data
			$event_date_in = $this->model_localisation_local_date->getInFormatDate($purchase_info['event_date']);

			// Product Data
			$primary_product = $this->model_purchase_purchase->getPurchasePrimaryProduct($purchase_id);

			// Transaction Info
			$transaction_date_in = $this->model_localisation_local_date->getInFormatDate($transaction_info['date']);
			$transaction_title	= $transaction_info['description'];

			$data['date']	= $transaction_date_in['long_date'];
			$data['amount']	= $this->currency->format(abs($transaction_info['amount']), $purchase_info['currency_code'], $purchase_info['currency_value']);
			$data['terbilang'] = sprintf($this->language->get('text_terbilang'), $this->model_localisation_local_date->getInWord($transaction_info['amount']));

			switch ($transaction_info['label']) {
				case 'customer':
					$data['name'] = $purchase_info['firstname'] . ' ' . $purchase_info['lastname'] . ($purchase_info['payment_company'] ? ' - ' . $purchase_info['payment_company'] : '');

					$address_format = sprintf($this->language->get('text_address_format'), $purchase_info['payment_address_1'] . ($purchase_info['payment_address_2'] ? ', ' . $purchase_info['payment_address_2'] : ''), $purchase_info['payment_city'], $purchase_info['payment_postcode'], $purchase_info['payment_zone'], $purchase_info['payment_country']);

					$data['text_hal'] = sprintf($this->language->get('text_atas_persewaan'), $transaction_title, $primary_product['name'], $data['store_name'], $purchase_info['ceremony'], $event_date_in['long_date']);
					$data['text_pihak'] = $this->language->get('text_pihak_penyewa');
					$data['tanda_tangan'] = '( ' . $purchase_info['firstname'] . ' ' . $purchase_info['lastname'] . ' )';
					$data['company'] = $purchase_info['payment_company'] ? $purchase_info['payment_company'] : '';

					break;

				case 'vendor':
					$this->load->model('catalog/vendor');

					$vendor_info = $this->model_catalog_vendor->getVendor($transaction_info['label_id']);

					$data['name'] = $vendor_info['vendor_name'] . ' - ' . $vendor_info['vendor_type'] . ($vendor_info['contact_person'] ? ' (' . $vendor_info['contact_person'] . ')' : '');

					$address_format = $vendor_info['address'];

					$data['text_hal'] = sprintf($this->language->get('text_atas_pelaksanaan'), $transaction_title, $purchase_info['ceremony'], $purchase_info['firstname'] . ' ' . $purchase_info['lastname'], $primary_product['name'], $data['store_name'], $event_date_in['long_date']);
					$data['text_pihak'] = $this->language->get('text_pihak_vendor');
					$data['tanda_tangan'] = $this->language->get('text_tanda_tangan');
					$data['company'] = $vendor_info['vendor_name'];

					break;

				default:
					$data['name'] = '';
					$address_format = '';
					$data['text_hal'] = '';
					$data['text_pihak'] = '';
					$data['tanda_tangan'] = '';
					$data['company'] = '';
			}

			// Customer Data
			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim($address_format)));

			// Note
			$this->load->model('user/user');

			$data['notes'] = array();

			if ($transaction_info['user_id'] != $this->user->getId()) {
				$transaction_user_info = $this->model_user_user->getUser($transaction_info['user_id']);
				$data['notes'][] = sprintf($this->language->get('text_diterima'), $transaction_user_info['firstname'] . ' ' . $transaction_user_info['lastname'], $transaction_user_info['user_group']);
			}

			if (!$income) {
				$data['notes'][] = sprintf($this->language->get('text_disimpan'), $data['store_owner']);
			} else {
				$data['text_pihak'] = '';
				$data['tanda_tangan'] = '';
				$data['company'] = '';
			}

			// User Info
			$user_info = $this->model_user_user->getUser($this->user->getId());

			$data['manajemen'] = '( ' . $user_info['firstname'] . ' ' . $user_info['lastname'] . ' )';
			$data['text_manajemen'] = $user_info['user_group'];

			if ($transaction_info['printed'] || !$this->user->hasPermission('modify', 'purchase/purchase')) {
				$data['preview'] = 1;
				$data['letter_content'] = 'letter-content';
			} else {
				$data['preview'] = 0;
				$data['letter_content'] = '';

				$this->model_accounting_transaction->editTransactionPrintStatus($transaction_id, 1);
			}
		} else {
			return false;
		}

		$this->response->setOutput($this->load->view('purchase/purchase_receipt', $data));
	}

	public function productSelect()
	{
		$json = array();
		$categories = array();

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$products = $this->model_catalog_product->getProductsByPrimaryType($this->request->get['primary_type']);

		foreach ($products as $product) {
			$product_categories = $this->model_catalog_product->getProductCategories($product['product_id']);

			foreach ($product_categories as $category_id) {
				$json['products'][$category_id][] = $product;
			}

			$categories = array_merge($categories, $product_categories);
		}

		$categories = array_unique($categories);

		foreach ($categories as $category_id) {
			$json['categories'][] = $this->model_catalog_category->getCategory($category_id);
		}

		array_multisort(array_column($json['categories'], 'sort_purchase'), SORT_ASC, $json['categories']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function product()
	{
		$json = array();

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

		if ($product_info) {
			$this->load->model('catalog/option');

			$option_data = array();

			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);

			foreach ($product_options as $product_option) {
				$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

				if ($option_info) {
					$product_option_value_data = array();

					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

						if ($option_value_info) {
							$product_option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $option_value_info['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);
						}
					}

					$option_data[] = array(
						'product_option_id'    => $product_option['product_option_id'],
						'product_option_value' => $product_option_value_data,
						'option_id'            => $product_option['option_id'],
						'name'                 => $option_info['name'],
						'type'                 => $option_info['type'],
						'value'                => $product_option['value'],
						'required'             => $product_option['required']
					);
				}
			}

			$json = array(
				'product_id'    => $product_info['product_id'],
				'name'          => $product_info['name'],
				'price'        	=> $this->currency->format($product_info['price'], $this->session->data['currency']),
				'option'        => $option_data
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
