<?php
class ModelAccountingTransaction extends Model { //utk transaksi akuntansi
	private $client_data = ['system', 'customer', 'vendor', 'supplier', 'finance'];
	private $category_data = ['order', 'deposit', 'purchase', 'expense', 'asset'];
	private $transaction_data = ['initial', 'discount', 'cashin', 'cashout', 'complete', 'canceled', 'charged'];

	public function addTransaction($data)
	{
		$transaction_type_info = $this->getTransactionType($data['transaction_type_id']);

		$field_data = [
			'client_id',
			'order_id',
			'payment_method',
			'transaction_tax_id'
		];
		foreach ($field_data as $field) {
			if (!isset($data[$field])) {
				$data[$field] = '';
			}
		}

		if (!isset($data['reference_prefix'])) {
			$label_data = array(
				'B'	=> 'asset',
				'L'	=> 'liability',
				'Q'	=> 'equity',
				'R'	=> 'revenue',
				'E'	=> 'expense'
			);

			$data['reference_prefix'] = array_search($transaction_type_info['category_label'], $label_data) . date('ym');
			$data['reference_no'] = $this->getLastReferenceNo($data['reference_prefix']) + 1;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET client_label = '" . $this->db->escape($transaction_type_info['client_label']) . "', category_label = '" . $this->db->escape($transaction_type_info['category_label']) . "', transaction_label = '" . $this->db->escape($transaction_type_info['transaction_label']) . "', client_id = '" . (int)$data['client_id'] . "', order_id = '" . (int)$data['order_id'] . "', transaction_type_id = '" . (int)$data['transaction_type_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), description = '" . $this->db->escape($data['description']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', amount = '" . (float)$data['amount'] . "', customer_name = '" . $this->db->escape($data['customer_name']) . "', reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "', reference_no = '" . (int)$data['reference_no'] . "', printed = '0', transaction_tax_id = '" . (int)$data['transaction_tax_id'] . "', edit_permission = '0', date_added = NOW(), user_id = '" . (int)$data['user_id'] . "'");

		$transaction_id = $this->db->getLastId();

		if (isset($data['transaction_account'])) {
			foreach ($data['transaction_account'] as $transaction_account) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$transaction_account['account_id'] . "', debit = '" . (float)$transaction_account['debit'] . "', credit = '" . (float)$transaction_account['credit'] . "'");
			}
		}
	}

	public function getTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "' ORDER BY date ASC");

		return $query->rows;
	}

	//Delete
	// public function getTransactionsTotalByTransactionType($transaction_type_id, $data = [])
	// {
	// 	$this->load->model('accounting/transaction');
	// 	$transaction_type_info = $this->model_accounting_transaction->getTransactionType($transaction_type_id);

	// 	$sql = "SELECT t.transaction_label, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t WHERE t.client_label = '" . $this->db->escape($transaction_type_info['client_label']) . "' AND t.category_label = '" . $this->db->escape($transaction_type_info['category_label']) . "' AND t.transaction_label = '" . $this->db->escape($transaction_type_info['transaction_label']) . "'";

	// 	$implode = array();

	// 	if (isset($data['order_id'])) {
	// 		$implode[] = "t.order_id = '" . (int)$data['order_id'] . "'";
	// 	}

	// 	if (isset($data['client_id'])) {
	// 		$implode[] = "t.client_id = '" . (int)$data['client_id'] . "'";
	// 	}

	// 	if ($implode) {
	// 		$sql .= " AND " . implode(" AND ", $implode);
	// 	}

	// 	$sql .= " GROUP BY t.transaction_label";

	// 	$query = $this->db->query($sql);

	// 	return $query->rows;

		// $total_data = [
		// 	'cashin'	=> 0,
		// 	'cashout'	=> 0
		// ];

		// foreach ($query->rows as $value) {
		// 	$total_data[$value['transaction_label']] = $value['total'];
		// }

		// return $total_data['cashin'] - $total_data['cashout'];
	// }

	public function getTransactionsTotalSummary($order_id, $data)
	{
		$sql = "SELECT t.transaction_label, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		if (isset($data['client_label']) && in_array($data['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['client_label']) . "'";

			if (isset($data['client_id'])) {
				$implode[] = "t.client_id = '" . (int)$data['client_id'] . "'";
			}
		}

		if (isset($data['category_label']) && in_array($data['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		if (isset($data['transaction_label']) && in_array($data['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_label";

		$query = $this->db->query($sql);

		$total_data = [
			'initial'	=> 0,
			'cashin'	=> 0,
			'cashout'	=> 0
		];

		foreach ($query->rows as $value) {
			$total_data[$value['transaction_label']] = $value['total'];
		}

		// return $total_data['cashin'] - $total_data['cashout'];
		return $total_data;
	}

	public function getTransactionsTotalByOrderId($order_id, $data = array()) {
		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

		$implode = array();

		if (!empty($data['filter_label'])) {
			$implode[] = "label = '" . $this->db->escape($data['filter_label']) . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_from_id'])) {
			$implode[] = "account_from_id = '" . (int)$data['filter_account_from_id'] . "'";
		}

		if (!empty($data['filter_account_to_id'])) {
			$implode[] = "account_to_id = '" . (int)$data['filter_account_to_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionByTransactionTypeId($transaction_type_id, $data = [])
	{
		$sql = "SELECT DISTINCT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.transaction_type_id = '" . (int)$transaction_type_id . "'";

		if (isset($data['client_id'])) {
			$sql .= " AND t.client_id = '" . (int)$data['client_id'] . "'";
		}
		
		if (isset($data['order_id'])) {
			$sql .= " AND t.order_id = '" . (int)$data['order_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTransactionType($transaction_type_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->row;
	}

	public function getTransactionTypeAccounts($transaction_type_id)
	{
		$query = $this->db->query("SELECT tta.*, a1.name AS account_debit, a2.name AS account_credit FROM " . DB_PREFIX . "transaction_type_account tta LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = tta.account_debit_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = tta.account_credit_id) WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->rows;
	}

	public function getLastReferenceNo($reference_prefix) {
		$sql = "SELECT MAX(reference_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_prefix = '" . $this->db->escape($reference_prefix) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionNoMax($reference_no) {
		$sql = "SELECT MAX(transaction_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_no = '" . $this->db->escape($reference_no) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
