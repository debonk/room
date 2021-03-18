<?php
class ControllerReportSaleDocument extends Controller
{
	public function index()
	{
		$this->load->language('report/sale_document');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/sale');

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_no_results',
			'text_not_printed',
			'text_confirm',
			'column_order_id',
			'column_type',
			'column_reference',
			'column_date',
			'column_customer',
			'column_username',
			'column_action',
			'entry_reference',
			'entry_order_id',
			'button_filter',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['filter_reference'])) {
			$filter_reference = $this->request->get['filter_reference'];
		} else {
			$filter_reference = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_id DESC, reference';
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

		$url = '';

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
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
			'href' => $this->url->link('report/sale_document', 'token=' . $this->session->data['token'], true)
		);

		$data['documents'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter_reference'	 => $filter_reference,
			'filter_order_id'	 	 => $filter_order_id,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $limit,
			'limit'                  => $limit
		);

		$transaction_count = $this->model_report_sale->getDocumentsCount($filter_data);

		$results = $this->model_report_sale->getDocuments($filter_data);

		foreach ($results as $result) {
			switch ($result['type']) {
				case 'customer-order':
					$document_type = $this->language->get('text_customer_agreement');
					break;

				case 'customer-transaction':
					$document_type = $this->language->get('text_customer_receipt');
					break;

				case 'vendor-admission':
					$document_type = $this->language->get('text_vendor_admission');
					break;

				case 'vendor-agreement':
					$document_type = $this->language->get('text_vendor_agreement');
					break;

				case 'vendor-purchase':
					$document_type = $this->language->get('text_vendor_purchase');
					break;

				case 'vendor-transaction':
					$document_type = $this->language->get('text_vendor_receipt');
					break;

				default:
					$document_type = '';
			}

			if ($this->user->hasPermission('modify', 'report/sale_document')) {
				$modify = '';
				$text_printed = $this->language->get('text_set_unprint');
				$text_not_printed = $this->language->get('text_set_print');
			} else {
				$modify = 'disabled';
				$text_printed = $this->language->get('text_printed');
				$text_not_printed = $this->language->get('text_not_printed');
			}

			$data['documents'][] = array(
				'type'      		=> $result['type'],
				'code'      		=> $result['type'] . '/' . $result['type_id'],
				'document_type'		=> $document_type,
				'order_id'      	=> '#' . $result['order_id'],
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'reference'  		=> $result['reference'],
				'customer'			=> $result['customer'],
				'username'      	=> $result['username'],
				'url'				=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
				'printed'       	=> $result['printed'],
				'modify'			=> $modify,
				'text_printed'		=> $text_printed,
				'text_not_printed'	=> $text_not_printed
			);
		}

		$url = '';

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_id'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=order_id' . $url, true);
		$data['sort_type'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=type' . $url, true);
		$data['sort_date'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);
		$data['sort_reference'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=reference' . $url, true);
		$data['sort_customer'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=printed' . $url, true);
		$data['sort_username'] = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . '&sort=username' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
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
		$pagination->url = $this->url->link('report/sale_document', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_count - $limit)) ? $transaction_count : ((($page - 1) * $limit) + $limit), $transaction_count, ceil($transaction_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter_reference'] = $filter_reference;
		$data['filter_order_id'] = $filter_order_id;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_document', $data));
	}

	public function togglePrintStatus()
	{
		$this->load->language('report/sale_document');

		$json = array();

		if (!$this->user->hasPermission('modify', 'report/sale_document')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['document_code'])) {
				$document_code = $this->request->get['document_code'];
			} else {
				$document_code = '';
			}

			$document_code = explode('/', $document_code);

			switch (true) {
				case ($document_code[0] == 'customer-order'):
					$this->load->model('sale/order');

					$order_info = $this->model_sale_order->getOrder($document_code[1]);

					if ($order_info) {
						if ($order_info['printed']) {
							$printed_status = 0;
						} else {
							$printed_status = 1;
						}

						$this->model_sale_order->editOrderPrintStatus($document_code[1], $printed_status);
					} else {
						$json['error'] = $this->language->get('error_not_found');
					}

					break;

				case ($document_code[0] == 'customer-transaction' || $document_code[0] == 'vendor-transaction'):
					$this->load->model('accounting/transaction');

					$transaction_info = $this->model_accounting_transaction->getTransaction($document_code[1]);

					if ($transaction_info) {
						if ($transaction_info['printed']) {
							$printed_status = 0;
						} else {
							$printed_status = 1;
						}

						$this->model_accounting_transaction->editTransactionPrintStatus($document_code[1], $printed_status);
					} else {
						$json['error'] = $this->language->get('error_not_found');
					}

					break;

				case ($document_code[0] == 'vendor-admission' || $document_code[0] == 'vendor-agreement' || $document_code[0] == 'vendor-purchase'):
					$this->load->model('sale/document');

					$order_document_info = $this->model_sale_document->getOrderDocument($document_code[1]);

					if ($order_document_info) {
						if ($order_document_info['printed']) {
							$printed_status = 0;
						} else {
							$printed_status = 1;
						}

						$this->model_sale_document->editDocumentPrintStatus($document_code[1], $printed_status);
					} else {
						$json['error'] = $this->language->get('error_not_found');
					}

					break;

				default:
					$json['error'] = $this->language->get('error_not_found');
					$printed_status = 0;
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
			$json['printed'] = $printed_status;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
