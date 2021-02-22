<?php
class ModelAccountingTransactionType extends Model
{
	public function addTransactionType($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', name = '" . $this->db->escape($data['name']) . "', account_type = '" . $this->db->escape($data['account_type']) . "', manual_select = '" . (int)$data['manual_select'] . "', account_debit_id = '" . (int)$data['account_debit_id'] . "', account_credit_id = '" . (int)$data['account_credit_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editTransactionType($transaction_type_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', name = '" . $this->db->escape($data['name']) . "', account_type = '" . $this->db->escape($data['account_type']) . "', manual_select = '" . (int)$data['manual_select'] . "', account_debit_id = '" . (int)$data['account_debit_id'] . "', account_credit_id = '" . (int)$data['account_credit_id'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
	}

	public function deleteTransactionType($transaction_type_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
	}

	public function getTransactionType($transaction_type_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->row;
	}

	public function getTransactionTypes($data = array())
	{
		$sql = "SELECT tt.*, a1.name AS account_debit, a2.name AS account_credit FROM " . DB_PREFIX . "transaction_type tt LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = tt.account_debit_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = tt.account_credit_id)";

		$sort_data = array(
			'client_label',
			'category_label',
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
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
	}

	public function getTransactionTypesCount()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction_type");

		return $query->row['total'];
	}

	public function getTransactionTypesMenu($data = [])
	{
		$sql = "SELECT transaction_type_id, name FROM " . DB_PREFIX . "transaction_type";

		$implode = array();

		if (isset($data['client_label'])) {
			$implode[] = "client_label = '" . $this->db->escape($data['client_label']) . "'";
		}

		if (isset($data['category_label'])) {
			$implode[] = "category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		if (isset($data['manual_select']) && !is_null($data['manual_select'])) {
			$implode[] = "manual_select = '" . $this->db->escape($data['manual_select']) . "'";
		} else {
			$implode[] = "manual_select = 1";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionTypesByLabel($client_label, $category_label = '')
	{
		if (!empty($client_label) || !empty($category_label)) {
			$sql = "SELECT * FROM " . DB_PREFIX . "transaction_type";

			$implode = array();

			if (!empty($client_label)) {
				$implode[] = "client_label = '" . $this->db->escape($client_label) . "'";
			}

			if (!empty($category_label)) {
				$implode[] = "category_label = '" . $this->db->escape($category_label) . "'";
			}

			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}

			$sql .= " ORDER BY client_label, sort_order ASC";

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			return;
		}
	}

	public function geTransactionTypeLabels()
	{
		$sql = "SELECT DISTINCT CONCAT(client_label, '-', category_label) AS label FROM " . DB_PREFIX . "transaction_type ORDER BY label ASC";

		$query = $this->db->query($sql);

		$label_data = [];

		foreach ($query->rows as $label) {
			$label_data[] = $label['label'];
		}

		return $label_data;
	}

	public function getClientsLabel()
	{
		$client_label_data = [];

		$client_data = [
			'customer',
			'vendor',
			'supplier'
		];

		foreach ($client_data as $client) {
			$client_label_data[] = [
				'value'	=> $client,
				'text'	=> ucfirst($client)
			];
		}

		return $client_label_data;
	}

	public function getCategoriesLabel()
	{
		$category_label_data = [];

		$category_data = [
			'order',
			'deposit',
			'purchase'
		];

		foreach ($category_data as $category) {
			$category_label_data[] = [
				'value'	=> $category,
				'text'	=> ucfirst($category)
			];
		}

		return $category_label_data;
	}
}
