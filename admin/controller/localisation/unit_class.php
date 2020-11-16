<?php
class ControllerLocalisationUnitClass extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/unit_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/unit_class');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/unit_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/unit_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_unit_class->addUnitClass($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/unit_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/unit_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_unit_class->editUnitClass($this->request->get['unit_class_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/unit_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/unit_class');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $unit_class_id) {
				$this->model_localisation_unit_class->deleteUnitClass($unit_class_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_title',
			'column_action',
			'button_add',
			'button_edit',
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
			'href' => $this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/unit_class/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/unit_class/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['unit_classes'] = array();

		$filter_data = array(
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$unit_class_count = $this->model_localisation_unit_class->getUnitClassesCount();

		$results = $this->model_localisation_unit_class->getUnitClasses($filter_data);

		foreach ($results as $result) {
			$data['unit_classes'][] = array(
				'unit_class_id'	=> $result['unit_class_id'],
				'title'    		=> $result['title'],
				'edit'     		=> $this->url->link('localisation/unit_class/edit', 'token=' . $this->session->data['token'] . '&unit_class_id=' . $result['unit_class_id'] . $url, true)
			);
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

		$data['sort_title'] = $this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true);
		
		$url = '';

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $unit_class_count;
		$pagination->page = $page;
		$pagination->limit = $filter_data['limit'];
		$pagination->url = $this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($unit_class_count) ? (($page - 1) * $filter_data['limit']) + 1 : 0, ((($page - 1) * $filter_data['limit']) > ($unit_class_count - $filter_data['limit'])) ? $unit_class_count : ((($page - 1) * $filter_data['limit']) + $filter_data['limit']), $unit_class_count, ceil($unit_class_count / $filter_data['limit']));

		$data['order'] = $order;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/unit_class_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['unit_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_title',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		$url = '';

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
			'href' => $this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['unit_class_id'])) {
			$data['action'] = $this->url->link('localisation/unit_class/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/unit_class/edit', 'token=' . $this->session->data['token'] . '&unit_class_id=' . $this->request->get['unit_class_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/unit_class', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['unit_class_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$unit_class_info = $this->model_localisation_unit_class->getUnitClass($this->request->get['unit_class_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['unit_class'])) {
			$data['unit_class'] = $this->request->post['unit_class'];
		} elseif (isset($this->request->get['unit_class_id'])) {
			$data['unit_class'] = $this->model_localisation_unit_class->getUnitClassDescriptions($this->request->get['unit_class_id']);
		} else {
			$data['unit_class'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/unit_class_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/unit_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['unit_class'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 16)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/unit_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $unit_class_id) {
			$product_total = $this->model_catalog_product->getProductsCountByUnitClassId($unit_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}
}
