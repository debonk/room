<?php
class ControllerPaymentBca extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/bca');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bca', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_select'] = $this->language->get('text_select');

		$data['entry_bank'] = $this->language->get('entry_bank');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_asset'] = $this->language->get('entry_asset');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_bank' . $language['language_id']] = '';
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/bca', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('payment/bca', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

		// $this->load->model('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['bca_bank' . $language['language_id']])) {
				$data['bca_bank' . $language['language_id']] = $this->request->post['bca_bank' . $language['language_id']];
			} else {
				$data['bca_bank' . $language['language_id']] = $this->config->get('bca_bank' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		if (isset($this->request->post['bca_total'])) {
			$data['bca_total'] = $this->request->post['bca_total'];
		} else {
			$data['bca_total'] = $this->config->get('bca_total');
		}

		if (isset($this->request->post['bca_order_status_id'])) {
			$data['bca_order_status_id'] = $this->request->post['bca_order_status_id'];
		} else {
			$data['bca_order_status_id'] = $this->config->get('bca_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bca_geo_zone_id'])) {
			$data['bca_geo_zone_id'] = $this->request->post['bca_geo_zone_id'];
		} else {
			$data['bca_geo_zone_id'] = $this->config->get('bca_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['bca_asset_id'])) {
			$data['bca_asset_id'] = $this->request->post['bca_asset_id'];
		} else {
			$data['bca_asset_id'] = $this->config->get('bca_asset_id');
		}

		$this->load->model('accounting/account');

		$data['asset_accounts'] = $this->model_accounting_account->getAccountsMenuByComponent($component = 'asset');

		if (isset($this->request->post['bca_status'])) {
			$data['bca_status'] = $this->request->post['bca_status'];
		} else {
			$data['bca_status'] = $this->config->get('bca_status');
		}

		if (isset($this->request->post['bca_sort_order'])) {
			$data['bca_sort_order'] = $this->request->post['bca_sort_order'];
		} else {
			$data['bca_sort_order'] = $this->config->get('bca_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/bca', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bca')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['bca_bank' . $language['language_id']])) {
				$this->error['bank' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}

		return !$this->error;
	}
}