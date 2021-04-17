<?php
class ModelAccountingAccount extends Model
{
	private $components = [
		'asset' 		=> ['current_asset', 'fixed_asset', 'non_current_asset', 'prepayment'],
		'equity' 		=> ['equity'],
		'expense' 		=> ['depreciation', 'direct_cost', 'expense', 'overhead'],
		'liability' 	=> ['current_liability', 'liability', 'non_current_liability'],
		'revenue' 		=> ['sale', 'revenue', 'other_income']
	];

	public function addAccount($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "account SET account_id = '" . (int)$data['account_id'] . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', parent_id = '" . (int)$data['parent_id'] . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('account');
	}

	public function editAccount($account_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "account SET account_id = '" . (int)$data['account_id'] . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', parent_id = '" . (int)$data['parent_id'] . "', status = '" . (int)$data['status'] . "' WHERE account_id = '" . (int)$account_id . "'");

		$this->cache->delete('account');
	}

	public function deleteAccount($account_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "account WHERE account_id = '" . (int)$account_id . "'");

		$this->cache->delete('account');
	}

	public function getAccount($account_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "account WHERE account_id = '" . (int)$account_id . "'");

		$account_data = $query->row;

		foreach ($this->components as $key => $component) {
			if (in_array($account_data['type'], $component)) {
				$account_data['component'] = $key;

				break;
			};
		}

		return $account_data;
	}

	public function getAccounts($data = array())
	{
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "account";

			$implode = array();

			if (isset($data['filter_parent_id'])) {
				$implode[] = "account_id LIKE '" . (int)$data['filter_parent_id'] . "%'"; // base menggunakan account_id agar akun itu sendiri termasuk dlm selection
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "status = '" . (int)$data['filter_status'] . "'";
			}

			if (!empty($data['component']) || !empty($data['filter_type'])) {
				$types_data = array();

				if (!empty($data['component'])) {
					foreach ($data['component'] as $component) {
						if (in_array($component, array_keys($this->components))) {
							$types_data = array_merge($types_data, $this->components[$component]);
						}
					}
				}

				if (!empty($data['filter_type'])) {
					$types_data = array_merge($types_data, $data['filter_type']);
				}

				$types = "'" . implode("', '", array_unique($types_data)) . "'";

				$implode[] = "type IN (" . $types . ")";
			}

			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}

			$sort_data = array(
				'account_id',
				'name',
				'description',
				'type',
				'parent_id',
				'status'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'account_id') {
					$sql .= " ORDER BY RPAD(account_id, 15, '0')";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY name";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) && isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$account_data = $this->cache->get('account');

			if (!$account_data) {
				$account_data = array();

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "account WHERE parent_id = '0' ORDER BY RPAD(account_id, 15, '0')");

				foreach ($query->rows as $result) {
					$account_data[] = array(
						'account_id'        => $result['account_id'],
						'name'              => $result['name'],
						'description'       => $result['description'],
						'type'              => $result['type'],
						'parent_id'         => $result['parent_id'],
						'status'            => $result['status'],
						'retained_earnings' => $result['retained_earnings']
					);
				}

				$this->cache->set('account', $account_data);
			}

			return $account_data;
		}
	}

	public function getAccountsCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "account";

		$implode = array();

		if (isset($data['filter_parent_id'])) {
			$implode[] = "account_id LIKE '" . (int)$data['filter_parent_id'] . "%'"; // base menggunakan account_id agar akun itu sendiri termasuk dlm selection
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['component']) || !empty($data['filter_type'])) {
			$types_data = array();

			if (!empty($data['component'])) {
				foreach ($data['component'] as $component) {
					if (in_array($component, array_keys($this->components))) {
						$types_data = array_merge($types_data, $this->components[$component]);
					}
				}
			}

			if (!empty($data['filter_type'])) {
				$types_data = array_merge($types_data, $data['filter_type']);
			}

			$types = "'" . implode("', '", array_unique($types_data)) . "'";

			$implode[] = "type IN (" . $types . ")";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getAccountsMenuByComponent($component = [], $type = [])
	{
		$accounts_data = array();
		$childs_data = array();

		$filter_data = array(
			'component' 		=> $component,
			'filter_type' 		=> $type,
			'filter_status'		=> 1,
			'sort' 				=> 'account_id'
		);

		$accounts = $this->getAccounts($filter_data);

		foreach ($accounts as $account) {
			$child_account_count = $this->getAccountsCount(['filter_parent_id' => $account['account_id']]) - 1;

			if ($child_account_count) {
				$accounts_data[$account['account_id']] = array(
					'account_id'	=> $account['account_id'],
					'text'			=> $account['account_id'] . ' - ' . $account['name'],
					'child'			=> array()
				);
			} else {
				$childs_data[$account['parent_id']][] = array(
					'account_id'	=> $account['account_id'],
					'text'			=> $account['account_id'] . ' - ' . $account['name']
				);
			}
		}

		foreach ($childs_data as $key => $child_data) {
			$accounts_data[$key]['child'] = $child_data;
		}

		return $accounts_data;
	}

	public function getAccountsMenuByParentId($parent_ids = [])
	{
		$accounts_data = array();
		$childs_data = array();

		sort($parent_ids, SORT_NATURAL);
		
		foreach ($parent_ids as $parent_id) {
			$filter_data = array(
				'filter_parent_id'	=> $parent_id,
				'filter_status'		=> 1,
				'sort' 				=> 'account_id'
			);

			$accounts = $this->getAccounts($filter_data);

			if (count($accounts) == 1) {
				$accounts[] = $this->getAccount($accounts[0]['parent_id']);
			}

			foreach ($accounts as $account) {
				$child_account_count = $this->getAccountsCount(['filter_parent_id' => $account['account_id']]) - 1; // kurang 1 agar parent tidak dihitung

				if ($child_account_count) {
					$accounts_data[$account['account_id']] = array(
						'account_id'	=> $account['account_id'],
						'text'			=> $account['account_id'] . ' - ' . $account['name'],
						'child'			=> array()
					);
				} else {
					$childs_data[$account['parent_id']][] = array(
						'account_id'	=> $account['account_id'],
						'text'			=> $account['account_id'] . ' - ' . $account['name']
					);
				}
			}
		}

		foreach ($childs_data as $key => $child_data) {
			$accounts_data[$key]['child'] = $child_data;
		}

		return $accounts_data;
	}

	public function getMainComponents()
	{
		return array_keys($this->components);
	}

	public function getAccountComponents()
	{
		return $this->components;
	}
}
