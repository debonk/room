<?php
class ModelAccountingTransactionType extends Model
{
	private $client_data = ['system', 'customer', 'vendor', 'supplier', 'finance'];
	private $category_data = ['order', 'deposit', 'purchase', 'expense', 'asset'];
	private $transaction_data = ['initial', 'discount', 'cashin', 'cashout', 'complete'];

	public function addTransactionType($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', transaction_label = '" . $this->db->escape($data['transaction_label']) . "', name = '" . $this->db->escape($data['name']) . "', manual_select = '" . (int)$data['manual_select'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$transaction_type_id = $this->db->getLastId();

		foreach ($data['transaction_type_account'] as $transaction_type_account) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_type_account SET transaction_type_id = '" . (int)$transaction_type_id . "', transaction_label = '" . $this->db->escape($transaction_type_account['transaction_label']) . "', account_debit_id = '" . (int)$transaction_type_account['account_debit_id'] . "', account_credit_id = '" . (int)$transaction_type_account['account_credit_id'] . "'");
		}
	}

	public function editTransactionType($transaction_type_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', transaction_label = '" . $this->db->escape($data['transaction_label']) . "', name = '" . $this->db->escape($data['name']) . "', manual_select = '" . (int)$data['manual_select'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_type_account WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		foreach ($data['transaction_type_account'] as $transaction_type_account) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_type_account SET transaction_type_id = '" . (int)$transaction_type_id . "', transaction_label = '" . $this->db->escape($transaction_type_account['transaction_label']) . "', account_debit_id = '" . (int)$transaction_type_account['account_debit_id'] . "', account_credit_id = '" . (int)$transaction_type_account['account_credit_id'] . "'");
		}
	}

	public function deleteTransactionType($transaction_type_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_type_account WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
	}

	public function getTransactionType($transaction_type_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->row;
	}

	public function getTransactionTypes($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "transaction_type";

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

	public function getTransactionTypeAccounts($transaction_type_id) {
		$query = $this->db->query("SELECT tta.*, a1.name AS account_debit, a2.name AS account_credit FROM " . DB_PREFIX . "transaction_type_account tta LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = tta.account_debit_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = tta.account_credit_id) WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->rows;
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

	public function getTransactionTypeByLabel($client_label, $category_label, $transaction_label)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "transaction_type WHERE client_label = '" . $this->db->escape($client_label) . "' AND category_label = '" . $this->db->escape($category_label) . "' AND transaction_label = '" . $this->db->escape($transaction_label) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTransactionTypesByLabel($client_label, $category_label = '', $transaction_label = '')
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

			if (!empty($transaction_label)) {
				$implode[] = "transaction_label = '" . $this->db->escape($transaction_label) . "'";
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

	public function getClientsLabel()
	{
		$client_label_data = [];

		foreach ($this->client_data as $client) {
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

		foreach ($this->category_data as $category) {
			$category_label_data[] = [
				'value'	=> $category,
				'text'	=> ucfirst($category)
			];
		}

		return $category_label_data;
	}

	public function getTransactionsLabel()
	{
		$transaction_label_data = [];

		foreach ($this->transaction_data as $transaction) {
			$transaction_label_data[] = [
				'value'	=> $transaction,
				'text'	=> ucfirst($transaction)
			];
		}

		return $transaction_label_data;
	}
}
