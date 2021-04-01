<?php
class ControllerReportCoa extends Controller
{
	// private $error = array();

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
		}

		if (is_null($filter['year'])) {
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
			'column_date',
			'column_account_id',
			'column_name',
			'column_type',
			'column_total'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		foreach ($this->filter_items as $filter_item) {
			$filter[$filter_item] = isset($this->request->get['filter_' . $filter_item]) ? $this->request->get['filter_' . $filter_item] : null;
		}

		if (is_null($filter['component'])) {
			$filter['component'] = 'asset';
		}

		if (is_null($filter['year'])) {
			$filter['year'] = date('Y');
		}

		// if (isset($this->request->get['page'])) {
		// 	$page = $this->request->get['page'];
		// } else {
		// 	$page = 1;
		// }

		$url = $this->urlFilter();

		// if (isset($this->request->get['page'])) {
		// 	$url .= '&page=' . $this->request->get['page'];
		// }

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
		// $limit = 20;

		$filter['date_start'] = date('Y-m-d', strtotime($filter['year'] . '-01-01'));
		$filter['date_end'] = date('Y-m-d', strtotime($filter['year'] . '-12-31'));

		$filter_data = array(
			'filter'	=> $filter
		);

		$results = $this->model_report_transaction->getTransactions($filter_data);

		var_dump($results);die('---breakpoint---');


		// $transaction_count = $this->model_report_transaction->getTransactionsCount($filter_data);
		
		// $balance_end = $this->model_report_transaction->getBalanceEnd($filter_data);
		
		// $balance = $balance_end;

		$total_debit = 0;
		$total_credit = 0;


		// $results = $this->model_accounting_account->getAccountsMenuByComponent([$filter['component']]);


		foreach ($results as $result) {
			if (!empty($result['order_id'])) {
				$reference = '#' . $result['order_id'] . ($result['reference_no'] ? ': ' . $result['reference'] : '');
			} else {
				$reference = $result['reference'];
			}

			$account_data = [];

			$transaction_accounts = $this->model_report_transaction->getTransactionAccounts($result['transaction_id']);
			foreach ($transaction_accounts as $transaction_account) {
				if ($transaction_account['account_id'] != $filter['account_id']) {
					$account_data[] = $transaction_account['account_id'] . ' - ' . $transaction_account['account'];
				}
			}

			if (empty($account_data)) {
				$account_data[] = $this->language->get('text_none');
			}

			$data['transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'reference' 		=> $reference,
				'description'		=> $result['customer_name'] . ($result['customer_name'] && $result['description'] ? ' - ' : '') . $result['description'],
				'customer_name'		=> $result['customer_name'],
				'account'			=> $account_data,
				'debit'      		=> $this->currency->format($result['debit'], $this->config->get('config_currency')),
				'credit'      		=> $this->currency->format($result['credit'], $this->config->get('config_currency')),
				'balance'      		=> $this->currency->format($balance, $this->config->get('config_currency')),
				'href'         		=> $this->url->link('accounting/transaction/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true),
			);

			$balance -= $result['debit'] - $result['credit'];

			$total_debit += $result['debit'];
			$total_credit += $result['credit'];
		}

		$url = $this->urlFilter();

		$pagination = new Pagination();
		$pagination->total = $transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/coa/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_count - $limit)) ? $transaction_count : ((($page - 1) * $limit) + $limit), $transaction_count, ceil($transaction_count / $limit));

		$data['filter'] = $filter;

		$data['balance_end'] = $this->currency->format($balance_end, $this->config->get('config_currency'));
		$data['balance_start'] = $this->currency->format($balance, $this->config->get('config_currency'));
		$data['total_debit'] = $this->currency->format($total_debit, $this->config->get('config_currency'));
		$data['total_credit'] = $this->currency->format($total_credit, $this->config->get('config_currency'));

		$this->response->setOutput($this->load->view('report/coa_info', $data));
	}
}
