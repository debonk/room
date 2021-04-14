<?php
class ControllerReportCoa extends Controller
{
	private $filter_items = [
		'component',
		'year'
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
		$this->load->language('report/coa');

		$this->document->setTitle($this->language->get('heading_title'));

		$language_items = array(
			'heading_title',
			'text_all',
			'text_list',
			'entry_component',
			'entry_year',
			'button_filter'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['component'])) {
			$filter['component'] = 'asset';
		} elseif ($filter['component'] == '*') {
			$filter['component'] = '';
		}

		if (empty($filter['year'])) {
			$filter['year'] = date('Y');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/coa', 'token=' . $this->session->data['token'], true)
		);

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/account');

		$data['components'] = [];

		$components = $this->model_accounting_account->getMainComponents();
		foreach ($components as $component) {
			$data['components'][] = [
				'value'	=> $component,
				'text'	=> $this->language->get('text_' . $component)
			];
		}

		$data['filter'] = $filter;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/coa', $data));
	}

	public function report()
	{
		$this->load->language('report/coa');

		$this->load->model('accounting/account');
		$this->load->model('report/transaction');

		$language_items = array(
			'text_total',
			'text_no_results',
			'column_account_id',
			'column_name',
			'column_type',
			'column_debit',
			'column_credit',
			'column_balance'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['component'])) {
			$filter['component'] = 'asset';
		} elseif ($filter['component'] == '*') {
			$filter['component'] = '';
		}

		if (empty($filter['year'])) {
			$filter['year'] = date('Y');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->urlFilter();

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
			'href' => $this->url->link('report/coa', 'token=' . $this->session->data['token'], true)
		);

		$data['accounts'] = array();

		$filter['date_start'] = date('Y-m-d', strtotime($filter['year'] . '-01-01'));
		$filter['date_end'] = date('Y-m-d', strtotime($filter['year'] . '-12-31'));

		$limit = $this->config->get('config_limit_admin');

		$filter_data = [
			'component'	=> $filter['component'] ? [$filter['component']] : null,
			'filter'	=> $filter,
			'sort'		=> 'account_id',
			'start'     => ($page - 1) * $limit,
			'limit'     => $limit
		];

		$result_count = $this->model_accounting_account->getAccountsCount($filter_data);
		$results = $this->model_accounting_account->getAccounts($filter_data);

		$account_components =  $this->model_accounting_account->getAccountComponents();

		foreach ($results as $result) {
			foreach ($account_components as $key => $types) {
				if (in_array($result['type'], $types)) {
					$component = $key;
				}
			}

			$child_count = $this->model_accounting_account->getAccountsCount(['filter_parent_id' => $result['account_id']]);

			if ($child_count == 1) {
				$header_status = false;

				if ($component == 'expense' || $component == 'revenue') {
					$transaction_total = $this->model_report_transaction->getTransactionsTotalByAccountId($result['account_id'], $filter_data);
				} else {
					$transaction_total = $this->model_report_transaction->getTransactionsTotalByAccountId($result['account_id']);
				}

				if ($component == 'asset' || $component == 'expense') {
					$balance = $transaction_total['debit'] - $transaction_total['credit'];
				} else {
					$balance = $transaction_total['credit'] - $transaction_total['debit'];
				}
			} else {
				$header_status = true;

				$transaction_total = [
					'debit'		=> 0,
					'credit'	=> 0
				];

				$balance = 0;
			}

			if ($result['retained_earnings']) {
				$revenue_total = $this->model_report_transaction->getTransactionsTotalByAccountComponent('revenue');
				$expense_total = $this->model_report_transaction->getTransactionsTotalByAccountComponent('expense');

				$transaction_total = [
					'debit'		=> $revenue_total['debit'] + $expense_total['debit'],
					'credit'	=> $revenue_total['credit'] + $expense_total['credit']
				];

				$balance = $transaction_total['credit'] - $transaction_total['debit'];
			}

			$data['accounts'][$component][] = array(
				'account_id'	=> $result['account_id'],
				'name'			=> $result['name'],
				'type'			=> $this->language->get('text_' . $result['type']),
				'header_status'	=> $header_status,
				'debit'      	=> $this->currency->format($transaction_total['debit'], $this->config->get('config_currency')),
				'credit'      	=> $this->currency->format($transaction_total['credit'], $this->config->get('config_currency')),
				'balance'      	=> $this->currency->format($balance, $this->config->get('config_currency')),
				// 'href'         	=> $this->url->link('accounting/transaction/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true),
			);
		}

		foreach (array_keys($data['accounts']) as $key) {
			if ($key == 'expense' || $key == 'revenue') {
				$text = $this->language->get('text_' . $key) . ' (' . $filter['year'] . ')';
			} else {
				$text = $this->language->get('text_' . $key);
			}
			$data['components'][] = [
				'code'	=> $key,
				'text'	=> strtoupper($text)
			];
		}

		$url = $this->urlFilter();

		$pagination = new Pagination();
		$pagination->total = $result_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/coa/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_count - $limit)) ? $result_count : ((($page - 1) * $limit) + $limit), $result_count, ceil($result_count / $limit));

		$data['filter'] = $filter;

		$this->response->setOutput($this->load->view('report/coa_info', $data));
	}
}
