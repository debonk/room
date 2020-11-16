<?php
class ControllerLocalisationSlot extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/slot');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/slot');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/slot');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/slot');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_slot->addSlot($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/slot');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/slot');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_slot->editSlot($this->request->get['slot_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/slot');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/slot');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $slot_id) {
				$this->model_localisation_slot->deleteSlot($slot_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true));
		}

		$this->getList();
	}

	protected function getList() {
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_records',
			'column_name',
			'column_code',
			'column_sort_order',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('localisation/slot/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('localisation/slot/delete', 'token=' . $this->session->data['token'], true);

		$data['slots'] = array();

		$slot_count = $this->model_localisation_slot->getSlotsCount();

		$results = $this->model_localisation_slot->getSlots();

		foreach ($results as $result) {
			$data['slots'][] = array(
				'slot_id' 		=> $result['slot_id'],
				'name'          => $result['name'],
				'code'          => $result['code'],
				'sort_order'    => $result['sort_order'],
				'edit'          => $this->url->link('localisation/slot/edit', 'token=' . $this->session->data['token'] . '&slot_id=' . $result['slot_id'], true)
			);
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

		$data['records'] = sprintf($this->language->get('text_records'), $slot_count);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/slot_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['slot_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_code',
			'entry_sort_order',
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

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['slot_id'])) {
			$data['action'] = $this->url->link('localisation/slot/add', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('localisation/slot/edit', 'token=' . $this->session->data['token'] . '&slot_id=' . $this->request->get['slot_id'], true);
		}

		$data['cancel'] = $this->url->link('localisation/slot', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->get['slot_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$slot_info = $this->model_localisation_slot->getSlot($this->request->get['slot_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($slot_info)) {
			$data['name'] = $slot_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($slot_info)) {
			$data['code'] = $slot_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($slot_info)) {
			$data['sort_order'] = $slot_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/slot_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/slot')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 256)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen($this->request->post['code']) > 4)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/slot')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

//Check orders by slot id
/* 		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $slot_id) {
			$order_count = $this->model_sale_order->getOrdersCountBySlotId($slot_id);

			if ($order_count) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_count);
			}
		}
 */
		return !$this->error;
	}
}
