<?php
class ControllerDashboardYearly extends Controller {
	public function index() {
		$this->load->language('dashboard/yearly');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['token'] = $this->session->data['token'];

		return $this->load->view('dashboard/yearly', $data);
	}

	public function yearlyData()
	{
		$this->load->language('sale/order');

		$json = array();

		if (isset($this->request->get['filter_year'])) {
			$filter_year = $this->request->get['filter_year'];
		} else {
			$filter_year = date('Y', strtotime('today'));
		}

		$this->load->model('sale/order');

		$filter_data = array(
			'filter_order_status' => implode(',', array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))),
			'filter_year'         => $filter_year
		);

		$results = $this->model_sale_order->getOrdersEventDate($filter_data);

		$json['results'] = array_values(array_unique(array_column($results, 'event_date')));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}