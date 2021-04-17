<?php
class ControllerReportSci extends Controller
{
	private $filter_items = [
		'date_start',
		'date_end'
	];

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
		$this->load->language('report/sci');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/account');
		$this->load->model('report/transaction');

		$language_items = array(
			'heading_title',
			'text_expense',
			'text_list',
			'text_net_profit',
			'text_revenue',
			'text_total',
			'entry_date_start',
			'entry_date_end',
			'button_filter'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['date_start'])) {
			$filter['date_start'] = date('Y-m-d', strtotime(date('Y') . '-01-01'));
		}

		if (is_null($filter['date_end'])) {
			$filter['date_end'] = date('Y-m-d');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/sci', 'token=' . $this->session->data['token'], true)
		);

		$data['accounts'] = [];
		$total = [
			'revenue'	=> 0,
			'expense'	=> 0
		];
		$data['text_type'] = [];

		$filter_data = [
			'component'	=> ['revenue', 'expense'],
			'filter'	=> $filter,
			'sort'		=> 'account_id'
		];

		$results = $this->model_accounting_account->getAccounts($filter_data);

		$account_components =  $this->model_accounting_account->getAccountComponents();

		foreach ($results as $result) {
			foreach ($account_components as $key => $types) {
				if (in_array($result['type'], $types)) {
					$component = $key;
				}
			}
			
			if (!isset($text_type[$result['type']])) {
				$data['text_type'][$result['type']] = $this->language->get('text_' . $result['type']);
			}
			
			$child_count = $this->model_accounting_account->getAccountsCount(['filter_parent_id' => $result['account_id']]);

			if ($child_count == 1) {
				$transaction_total = $this->model_report_transaction->getTransactionsTotalByAccountId($result['account_id'], $filter_data);

				if ($component == 'expense') {
					$balance = $transaction_total['debit'] - $transaction_total['credit'];
				} else {
					$balance = $transaction_total['credit'] - $transaction_total['debit'];
				}

				$total[$component] += $balance;

				if ($balance) {
					$data['accounts'][$component][$result['type']][] = [
						'account_id'	=> $result['account_id'],
						'name'			=> $result['name'],
						'type'			=> $this->language->get('text_' . $result['type']),
						'balance'      	=> $this->currency->format($balance, $this->config->get('config_currency')),
						'href'			=> $this->url->link('report/account_transaction', 'token=' . $this->session->data['token'] . '&filter_account_id=' . $result['account_id'] . $this->urlFilter(), true)
					];
				}
			}
		}

		$data['total_revenue'] = $this->currency->format($total['revenue'], $this->config->get('config_currency'));
		$data['total_expense'] = $this->currency->format($total['expense'], $this->config->get('config_currency'));
		$data['net_profit'] = $this->currency->format($total['revenue'] - $total['expense'], $this->config->get('config_currency'));
		$data['date_start'] = date($this->language->get('date_format_long'), strtotime($filter['date_start']));
		$data['date_end'] = date($this->language->get('date_format_long'), strtotime($filter['date_end']));

		$data['token'] = $this->session->data['token'];

		$data['filter'] = $filter;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sci', $data));
	}
}
