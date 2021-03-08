<?php
class ControllerReportTransactionBalance extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('report/transaction_balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$language_items = array(
			'heading_title',
			'text_list',
			'entry_date_start',
			'entry_date_end',
			'entry_account',
			'button_filter'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			// $filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start = date('Y-m-d', strtotime(date('Y-m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}

		if (isset($this->request->get['filter_account_id'])) {
			$filter_account_id = $this->request->get['filter_account_id'];
		} else {
			$filter_account_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_id'])) {
			$url .= '&filter_account_id=' . $this->request->get['filter_account_id'];
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
			'href' => $this->url->link('report/transaction_balance', 'token=' . $this->session->data['token'], true)
		);

		$data['token'] = $this->session->data['token'];

		$this->load->model('accounting/account');
		$data['accounts'] = $this->model_accounting_account->getAccountsMenuByComponent(['asset']);
		
		$data['filter_account_id'] = $filter_account_id;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/transaction_balance', $data));
	}
	
	public function report() {
		$this->load->language('report/transaction_balance');

		$this->load->model('report/transaction');

		$language_items = array(
			'text_balance_start',
			'text_total',
			'text_no_results',
			'column_date',
			'column_account',
			'column_description',
			'column_reference_no',
			'column_transaction_type',
			'column_customer_name',
			'column_debit',
			'column_credit',
			'column_balance'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y-m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}

		if (isset($this->request->get['filter_account_id'])) {
			$filter_account_id = $this->request->get['filter_account_id'];
		} else {
			$filter_account_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_id'])) {
			$url .= '&filter_account_id=' . $this->request->get['filter_account_id'];
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
			'href' => $this->url->link('report/transaction_balance', 'token=' . $this->session->data['token'], true)
		);

		$data['transactions'] = array();
		$limit = 20;

		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_account_id'	=> $filter_account_id,
			'start'            	=> ($page - 1) * $limit,
			'limit'            	=> $limit
		);

		$transaction_count = $this->model_report_transaction->getTransactionsCount($filter_data);

		$balance_start = $this->model_report_transaction->getTransactionsTotalPrevious($filter_data);

		$balance = $balance_start;

		$total_debit = 0;
		$total_credit = 0;

		$results = $this->model_report_transaction->getTransactions($filter_data);
		
		foreach ($results as $result) {
			# Maintain Versi 1
			if (empty($result['transaction_type'])) {
				$result['transaction_type'] = $result['description'];
			}

			//if ($result['label'] == 'expense' || $result['label'] == 'liability') {
			//	$result['account_type'] = 'C';
			//}

			//if (empty($result['account_type'])) {
			//	$result['account_type'] = 'D';
			//}
			# End Maintain

			//$result['amount'] *= $result['account_type'] == 'D' ? 1 : -1;

			if (!empty($result['order_id'])) {
				$reference_no = '#' . $result['order_id'] . ($result['reference_no'] ? ': ' . $result['reference'] : '');
			} else {
				$reference_no = $result['reference'];
			}
			
			if ($result['account_to_id'] == $filter_account_id) {
				$account = $result['account_from'] ? $result['account_from'] : $this->language->get('text_none');

				if ($result['amount'] > 0) {
					$debit = $result['amount'];
					$credit = 0;
					$balance += $debit;
					
					$total_debit += $debit;
				} else {
					$debit = 0;
					$credit = -$result['amount'];
					$balance -= $credit;
					
					$total_credit += $credit;
				}
			} else {
				$account = $result['account_to'] ? $result['account_to'] : $this->language->get('text_none');

				if ($result['amount'] > 0) {
					$debit = 0;
					$credit = $result['amount'];
					$balance -= $credit;
					
					$total_credit += $credit;
				} else {
					$debit = -$result['amount'];
					$credit = 0;
					$balance += $debit;
					
					$total_debit += $debit;
				}
			}

			$data['transactions'][] = array(
				'transaction_id'	=> $result['transaction_id'],
				'date'	 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'transaction_type'	=> $result['transaction_type'],
				'account'			=> $account,
				'description'		=> $result['description'],
				'reference_no' 		=> $reference_no,
				'customer_name'		=> $result['customer_name'],
				'debit'      		=> $this->currency->format($debit, $this->config->get('config_currency')),
				'credit'      		=> $this->currency->format($credit, $this->config->get('config_currency')),
				'balance'      		=> $this->currency->format($balance, $this->config->get('config_currency')),
				'href'         		=> $this->url->link('accounting/transaction/edit', 'token=' . $this->session->data['token'] . '&transaction_id=' . $result['transaction_id'] . $url, true),
			);
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_account_id'])) {
			$url .= '&filter_account_id=' . $this->request->get['filter_account_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/transaction_balance/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_count - $limit)) ? $transaction_count : ((($page - 1) * $limit) + $limit), $transaction_count, ceil($transaction_count / $limit));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['balance_start'] = $this->currency->format($balance_start, $this->config->get('config_currency'));
		$data['total_debit'] = $this->currency->format($total_debit, $this->config->get('config_currency'));
		$data['total_credit'] = $this->currency->format($total_credit, $this->config->get('config_currency'));
		
		$this->response->setOutput($this->load->view('report/transaction_balance_info', $data));
	}
}
