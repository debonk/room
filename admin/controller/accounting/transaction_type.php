<?php
class ControllerAccountingTransactionType extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction_type->addTransactionType($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_transaction_type->editTransactionType($this->request->get['transaction_type_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('accounting/transaction_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/transaction_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $transaction_type_id) {
				$this->model_accounting_transaction_type->deleteTransactionType($transaction_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
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
			'href' => $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/transaction_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/transaction_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['transaction_types'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$transaction_type_count = $this->model_accounting_transaction_type->getTransactionTypesCount();

		$results = $this->model_accounting_transaction_type->getTransactionTypes($filter_data);

		foreach ($results as $result) {
			$data['transaction_types'][] = array(
				'transaction_type_id'   => $result['transaction_type_id'],
				'name'                  => $result['name'],
				'client_label'			=> $result['client_label'],
				'category_label'		=> $result['category_label'],
				'sort_order'            => $result['sort_order'],
				'edit'                  => $this->url->link('accounting/transaction_type/edit', 'token=' . $this->session->data['token'] . '&transaction_type_id=' . $result['transaction_type_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_client_label'] = $this->language->get('column_client_label');
		$data['column_category_label'] = $this->language->get('column_category_label');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_client_label'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=client_label' . $url, true);
		$data['sort_category_label'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=category_label' . $url, true);
		$data['sort_name'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_type_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_type_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($transaction_type_count - $this->config->get('config_limit_admin'))) ? $transaction_type_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $transaction_type_count, ceil($transaction_type_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_type_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['transaction_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_client_label',
			'entry_category_label',
			'entry_name',
			'entry_sort_order',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$error_items = array(
			'warning',
			'client_label',
			'category_label',
			'name'
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}

		$url = '';

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
			'href' => $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['transaction_type_id'])) {
			$data['action'] = $this->url->link('accounting/transaction_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/transaction_type/edit', 'token=' . $this->session->data['token'] . '&transaction_type_id=' . $this->request->get['transaction_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('accounting/transaction_type', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['transaction_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($this->request->get['transaction_type_id']);
		}

		if (isset($this->request->post['client_label'])) {
			$data['client_label'] = $this->request->post['client_label'];
		} elseif (!empty($transaction_type_info)) {
			$data['client_label'] = $transaction_type_info['client_label'];
		} else {
			$data['client_label'] = '';
		}

		if (isset($this->request->post['category_label'])) {
			$data['category_label'] = $this->request->post['category_label'];
		} elseif (!empty($transaction_type_info)) {
			$data['category_label'] = $transaction_type_info['category_label'];
		} else {
			$data['category_label'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($transaction_type_info)) {
			$data['name'] = $transaction_type_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($transaction_type_info)) {
			$data['sort_order'] = $transaction_type_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/transaction_type_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'accounting/transaction_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['client_label'])) < 2) || (utf8_strlen(trim($this->request->post['client_label'])) > 16)) {
			$this->error['client_label'] = $this->language->get('error_client_label');
		}

		if ((utf8_strlen(trim($this->request->post['category_label'])) < 2) || (utf8_strlen(trim($this->request->post['category_label'])) > 16)) {
			$this->error['category_label'] = $this->language->get('error_category_label');
		}

		if ((utf8_strlen(trim($this->request->post['name'])) < 3) || (utf8_strlen(trim($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'accounting/transaction_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('accounting/transaction');

		foreach ($this->request->post['selected'] as $transaction_type_id) {
			$transaction_count = $this->model_accounting_transaction->getTransactionsCountByTransactionTypeId($transaction_type_id);

			if ($transaction_count) {
				$this->error['warning'] = sprintf($this->language->get('error_transaction'), $transaction_count);
			}
		}

		return !$this->error;
	}
}
