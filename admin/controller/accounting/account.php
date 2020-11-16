<?php
class ControllerAccountingAccount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('accounting/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/account');

		$this->getList();
	}

	public function add() {
		$this->load->language('accounting/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/account');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_account->addAccount($this->request->post);

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

			$this->response->redirect($this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('accounting/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/account');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_account->editAccount($this->request->get['account_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('accounting/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/account');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $account_id) {
				$this->model_accounting_account->deleteAccount($account_id);
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

			$this->response->redirect($this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true));
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
			'column_account_id',
			'column_name',
			'column_description',
			'column_type',
			'column_parent',
			'column_status',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'account_id';
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
			'href' => $this->url->link('accounting/account', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('accounting/account/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('accounting/account/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['accounts'] = array();
		$data['type_groups'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'sort' 		=> $sort,
			'order'		=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$account_count = $this->model_accounting_account->getAccountsCount($filter_data);

		$results = $this->model_accounting_account->getAccounts($filter_data);

		foreach ($results as $result) {
			$parent_info = $this->model_accounting_account->getAccount($result['parent_id']);
			
			$data['accounts'][] = array(
				'account_id'        => $result['account_id'],
				'name'              => $result['name'],
				'description'       => $result['description'],
				'type'              => $result['type'],
				'text_type' 		=> $this->language->get('text_' . $result['type']),
				'parent'         	=> $parent_info ? $parent_info['name'] : $this->language->get('text_none'),
				'status'            => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				// 'retained_earnings' => $result['retained_earnings'],
				'edit'          	=> $this->url->link('accounting/account/edit', 'token=' . $this->session->data['token'] . '&account_id=' . $result['account_id'] . $url, true)
			);
		}

		$type_groups = array(
			'asset' 		=> ['current_asset', 'fixed_asset', 'non_current_asset', 'prepayment'],
			'equity' 		=> ['equity'],
			'expense' 		=> ['depreciation', 'direct_cost', 'expense', 'overhead'],
			'liability' 	=> ['current_liability', 'liability', 'non_current_liability'],
			'revenue' 		=> ['sale', 'revenue', 'other_income']
		);
		foreach ($type_groups as $key => $type_group) {
			$data['type_groups'][] = array(
				'code'	=> $key,
				'text'	=> $this->language->get('text_' . $key),
				'list'	=> $type_group
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_account_id'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=account_id' . $url, true);
		$data['sort_name'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_description'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=description' . $url, true);
		$data['sort_type'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=type' . $url, true);
		$data['sort_parent_id'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=parent_id' . $url, true);
		$data['sort_status'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $account_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($account_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($account_count - $limit)) ? $account_count : ((($page - 1) * $limit) + $limit), $account_count, ceil($account_count / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/account_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['account_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'text_none',
			'text_asset',
			'text_current_asset',
			'text_fixed_asset',
			'text_non_current_asset',
			'text_prepayment',
			'text_equity',
			'text_expense',
			'text_depreciation',
			'text_direct_cost',
			'text_overhead',
			'text_liability',
			'text_current_liability',
			'text_non_current_liability',
			'text_sale',
			'text_revenue',
			'text_other_income',
			'entry_account_id',
			'entry_name',
			'entry_description',
			'entry_type',
			'entry_parent',
			'entry_status',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$errors = array(
			'warning',
			'account_id',
			'name',
			'parent'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
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
			'href' => $this->url->link('accounting/account', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['account_id'])) {
			$data['action'] = $this->url->link('accounting/account/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('accounting/account/edit', 'token=' . $this->session->data['token'] . '&account_id=' . $this->request->get['account_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['account_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$account_info = $this->model_accounting_account->getAccount($this->request->get['account_id']);
		}

		if (isset($this->request->post['account_id'])) {
			$data['account_id'] = $this->request->post['account_id'];
		} elseif (!empty($account_info)) {
			$data['account_id'] = $account_info['account_id'];
		} else {
			$data['account_id'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($account_info)) {
			$data['name'] = $account_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($account_info)) {
			$data['description'] = $account_info['description'];
		} else {
			$data['description'] = '';
		}

		// $type_groups = array(
			// 'asset' 		=> ['current_asset', 'fixed_asset', 'non_current_asset', 'prepayment'],
			// 'equity' 		=> ['equity'],
			// 'expense' 		=> ['depreciation', 'direct_cost', 'expense', 'overhead'],
			// 'liabilities' 	=> ['current_liability', 'liability', 'non_current_liability'],
			// 'revenue' 		=> ['sale', 'revenue', 'other_income']
		// );
		// foreach ($type_groups as $key => $type_group) {
			// $data['type_groups'][] = array(
				// 'code'	=> $key,
				// 'text'	=> $this->language->get('text_' . $key),
				// 'list'	=> $type_group
			// );
		// }
		
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (!empty($account_info)) {
			$data['type'] = $account_info['type'];
		} else {
			$data['type'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($account_info)) {
			$data['parent_id'] = $account_info['parent_id'];
		} else {
			$data['parent_id'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($account_info)) {
			$data['status'] = $account_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('accounting/account_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['account_id'])) {
			$this->error['account_id'] = $this->language->get('error_account_id');
        }

		if (!isset($this->request->get['account_id']) || isset($this->request->get['account_id']) && $this->request->get['account_id'] != $this->request->post['account_id']) {
            if ($this->model_accounting_account->getAccount($this->request->post['account_id'])) {
                $this->error['account_id'] = $this->language->get('error_account_id');
            }
        }

		if (!isset($this->request->get['account_id']) || isset($this->request->get['account_id']) && $this->request->get['account_id'] != $this->request->post['account_id']) {
            if ($this->model_accounting_account->getAccount($this->request->post['account_id'])) {
                $this->error['account_id'] = $this->language->get('error_account_id');
            } elseif ($this->request->get['account_id']) {
				$this->load->model('accounting/transaction');

				$transaction_count = $this->model_accounting_transaction->getTransactionsCountByAccountId($this->request->get['account_id']);

				if ($transaction_count) {
					$this->error['account_id'] = sprintf($this->language->get('error_transaction'), $transaction_count);
				}
			}
				
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (isset($this->request->get['account_id']) && $this->request->post['parent_id'] == $this->request->get['account_id']) {
            $this->error['parent'] = $this->language->get('error_parent');
        }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'accounting/account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('accounting/transaction');

		foreach ($this->request->post['selected'] as $account_id) {
			$account_info = $this->model_accounting_account->getAccount($account_id);
			if ($account_info && $account_info['retained_earnings']) {
				$this->error['warning'] = $this->language->get('error_retained_earnings');
			}
			
			$transaction_count = $this->model_accounting_transaction->getTransactionsCountByAccountId($account_id);

			if ($transaction_count) {
				$this->error['warning'] = sprintf($this->language->get('error_transaction'), $transaction_count);
			}
		}

		return !$this->error;
	}
	
    public function accounts() {
        $json = array();

        $this->load->model('accounting/account');

        $filter_data = array(
            'filter_type' => $this->request->get['type'],
			'sort'		  => 'account_id'
        );

        $results = $this->model_accounting_account->getAccounts($filter_data);
		
		foreach ($results as $result) {
			$json['accounts'][] = array(
				'account_id'        => $result['account_id'],
				'name'              => $result['account_id'] . ' - ' . $result['name']
			);
		}
		
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
