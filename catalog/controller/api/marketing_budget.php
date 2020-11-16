<?php
class ControllerApiMarketingBudget extends Controller {
	public function index() {
		$this->load->language('api/marketing_budget');

		// Delete past marketing_budget in case there is an error
		unset($this->session->data['marketing_budget']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (empty($this->request->post['marketing_budget'])) {
				$json['error'] = $this->language->get('error_amount');
			}
			
			$max_budget = $this->config->get('marketing_budget_max_budget');

			if ($max_budget && $this->request->post['marketing_budget'] > $max_budget) {
				$json['error'] = sprintf($this->language->get('error_max_budget'), $this->currency->format($max_budget, $this->session->data['currency']));
			}
			
			if (!$json) {
				$this->session->data['marketing_budget'] = abs($this->request->post['marketing_budget']);

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
}
