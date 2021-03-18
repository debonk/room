<?php
class ModelAccountingTransaction extends Model
{
	private $client_data = ['system', 'customer', 'vendor', 'supplier', 'finance'];
	private $category_data = ['order', 'deposit', 'purchase', 'expense', 'asset'];
	private $transaction_data = ['initial', 'discount', 'cashin', 'cashout', 'complete'];

	public function addTransaction($data)
	{
		$this->load->model('accounting/transaction_type');

		$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($data['transaction_type_id']);

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

		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET client_label = '" . $this->db->escape($transaction_type_info['client_label']) . "', category_label = '" . $this->db->escape($transaction_type_info['category_label']) . "', transaction_label = '" . $this->db->escape($transaction_type_info['transaction_label']) . "', client_id = '" . (int)$data['client_id'] . "', order_id = '" . (int)$data['order_id'] . "', transaction_type_id = '" . (int)$data['transaction_type_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), description = '" . $this->db->escape($data['description']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', amount = '" . (float)$data['amount'] . "', customer_name = '" . $this->db->escape($data['customer_name']) . "', reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "', reference_no = '" . (int)$data['reference_no'] . "', printed = '0', transaction_tax_id = '" . (int)$data['transaction_tax_id'] . "', edit_permission = '0', date_added = NOW(), user_id = '" . $this->user->getId() . "'");

		$transaction_id = $this->db->getLastId();

		if (isset($data['transaction_account'])) {
			foreach ($data['transaction_account'] as $transaction_account) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$transaction_account['account_id'] . "', debit = '" . (float)$transaction_account['debit'] . "', credit = '" . (float)$transaction_account['credit'] . "'");
			}
		}
	}

	public function editTransaction($transaction_id, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . "transaction SET edit_permission = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";

		if (isset($data['transaction_type_id'])) {
			$this->load->model('accounting/transaction_type');

			$transaction_type_info = $this->model_accounting_transaction_type->getTransactionType($data['transaction_type_id']);

			$sql .= ", client_label = '" . $this->db->escape($transaction_type_info['client_label']) . "', category_label = '" . $this->db->escape($transaction_type_info['category_label']) . "', transaction_label = '" . $this->db->escape($transaction_type_info['transaction_label']) . "', transaction_type_id = '" . (int)$data['transaction_type_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), description = '" . $this->db->escape($data['description']) . "', amount = '" . (float)$data['amount'] . "', customer_name = '" . $this->db->escape($data['customer_name']) . "'";
		}

		$sql .= " WHERE transaction_id = '" . (int)$transaction_id . "'";

		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");

		if (isset($data['transaction_account'])) {
			$transaction_accounts = [];

			# Combine data with same account_id
			foreach ($data['transaction_account'] as $value) {
				if (!isset($transaction_accounts[$value['account_id']])) {
					$transaction_accounts[$value['account_id']] = [
						'debit'		=> (float)$value['debit'],
						'credit'	=> (float)$value['credit']
					];
				} else {
					$transaction_accounts[$value['account_id']]['debit'] += (float)$value['debit'];
					$transaction_accounts[$value['account_id']]['credit'] += (float)$value['credit'];
				}
			}
			
			foreach ($transaction_accounts as $account_id => $transaction_account) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$account_id . "', debit = '" . (float)$transaction_account['debit'] . "', credit = '" . (float)$transaction_account['credit'] . "'");
			}
		}
	}

	public function deleteTransaction($transaction_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function getTransaction($transaction_id)
	{
		$query = $this->db->query("SELECT DISTINCT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.transaction_id = '" . (int)$transaction_id . "'");

		return $query->row;
	}

	public function getTransactions($data = array())
	{
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type, o.invoice_no, o.invoice_prefix, o.firstname, o.lastname, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		if (!empty($data['filter']['account_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id)";
		}

		$implode = array();

		if (isset($data['filter']['client_label']) && in_array($data['filter']['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['filter']['client_label']) . "'";

			if (isset($data['filter']['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['filter']['client_id']) . "'";
			}
		}

		if (isset($data['filter']['category_label']) && in_array($data['filter']['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['filter']['category_label']) . "'";
		}

		if (isset($data['filter']['transaction_label']) && in_array($data['filter']['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['filter']['transaction_label']) . "'";
		}

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['transaction_type_id'])) {
			$implode[] = "t.transaction_type_id = '" . (int)$data['filter']['transaction_type_id'] . "'";
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (!empty($data['filter']['reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['reference']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter']['customer_name']) . "%'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			if ($data['filter']['account_id'] === '-') {
				$implode[] = "ta.account_id IS NULL";
			} else {
				$implode[] = "ta.account_id LIKE '" . (int)$data['filter']['account_id'] . "%'";
			}
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_id";

		$sort_data = array(
			't.order_id',
			't.date',
			't.description',
			'reference',
			't.customer_name',
			'transaction_type',
			'total',
			'u.username',
			't.date DESC, t.transaction_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY t.date";
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

	public function getTransactionsCount($data = array())
	{
		$sql = "SELECT COUNT(t.transaction_id) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		if (!empty($data['filter']['account_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id)";
		}

		$implode = array();

		if (isset($data['filter']['client_label']) && in_array($data['filter']['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['filter']['client_label']) . "'";

			if (isset($data['filter']['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['filter']['client_id']) . "'";
			}
		}

		if (isset($data['filter']['category_label']) && in_array($data['filter']['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['filter']['category_label']) . "'";
		}

		if (isset($data['filter']['transaction_label']) && in_array($data['filter']['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['filter']['transaction_label']) . "'";
		}

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['transaction_type_id'])) {
			$implode[] = "t.transaction_type_id = '" . (int)$data['filter']['transaction_type_id'] . "'";
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (!empty($data['filter']['reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['reference']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter']['customer_name']) . "%'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id LIKE '" . (int)$data['filter']['account_id'] . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsTotal($data = array())
	{
		$sql = "SELECT SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		if (!empty($data['filter']['account_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id)";
		}

		$implode = array();

		if (isset($data['filter']['client_label']) && in_array($data['filter']['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['filter']['client_label']) . "'";

			if (isset($data['filter']['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['filter']['client_id']) . "'";
			}
		}

		if (isset($data['filter']['category_label']) && in_array($data['filter']['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['filter']['category_label']) . "'";
		}

		if (isset($data['filter']['transaction_label']) && in_array($data['filter']['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['filter']['transaction_label']) . "'";
		}

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['transaction_type_id'])) {
			$implode[] = "t.transaction_type_id = '" . (int)$data['filter']['transaction_type_id'] . "'";
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (!empty($data['filter']['reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['reference']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter']['customer_name']) . "%'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id LIKE '" . (int)$data['filter']['account_id'] . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionAccounts($transaction_id)
	{
		$query = $this->db->query("SELECT ta.*, a.name AS account FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "account a ON (a.account_id = ta.account_id) WHERE transaction_id = '" . (int)$transaction_id . "'");

		return $query->rows;
	}

	public function getTransactionsSubTotal($data = array())
	{
		$sql = "SELECT SUM(amount) AS total FROM (SELECT amount FROM " . DB_PREFIX . "transaction WHERE 1";

		if (!empty($data['filter_payment_method'])) {
			$sql .= " AND payment_method = '" . $this->db->escape($data['filter_payment_method']) . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " ORDER BY date ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'] . ") AS subquery";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsTotalPrevious($data = array())
	{
		if (!empty($data['filter_date_start'])) {
			$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE DATE(date) < '" . $this->db->escape($data['filter_date_start']) . "'";

			if (!empty($data['filter_payment_method'])) {
				$sql .= " AND payment_method = '" . $this->db->escape($data['filter_payment_method']) . "'";
			}

			$query = $this->db->query($sql);

			$total = $query->row['total'];
		} else {
			$total = 0;
		}

		if (isset($data['start']) && $data['start'] > 0) {
			$data['limit'] = $data['start'];

			$data['start'] = 0;

			$subtotal = $this->getTransactionsSubTotal($data);

			$total += $subtotal;
		}

		return $total;
	}

	public function getLastReferenceNo($reference_prefix)
	{
		$sql = "SELECT MAX(reference_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_prefix = '" . $this->db->escape($reference_prefix) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	//Cek apakah masih digunakan
	public function getTransactionsByOrderId($order_id, $data = array())
	{
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id) WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND t.label = '" . $this->db->escape($data['label']) . "'";

			if (!empty($data['label_id'])) {
				$sql .= " AND t.label_id = '" . (int)$data['label_id'] . "'";
			}
		}

		//Cek cara sort yg dibutuhkan pada account > balance
		$sort_data = array(
			't.order_id',
			't.date',
			't.date DESC, t.transaction_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY t.date DESC, t.transaction_id";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	// public function getTransactionsTotalByOrderId($order_id, $data = array()) {
	// 	$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

	// 	if (!empty($data['label'])) {
	// 		$sql .= " AND label = '" . $this->db->escape($data['label']) . "'";

	// 		if (!empty($data['label_id'])) {
	// 			$sql .= " AND label_id = '" . (int)$data['label_id'] . "'";
	// 		}
	// 	}

	// 	$query = $this->db->query($sql);

	// 	return $query->row['total'];
	// }

	// public function getTransactionsCountByOrderId($order_id, $data = array()) {
	// 	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

	// 	if (!empty($data['label'])) {
	// 		$sql .= " AND label = '" . $this->db->escape($data['label']) . "'";

	// 		if (!empty($data['label_id'])) {
	// 			$sql .= " AND label_id = '" . (int)$data['label_id'] . "'";
	// 		}
	// 	}

	// 	$query = $this->db->query($sql);

	// 	return $query->row['total'];
	// }

	public function getTransactionByTransactionTypeId($transaction_type_id)
	{
		$query = $this->db->query("SELECT DISTINCT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->row;
	}

	public function getTransactionsCountByTransactionTypeId($transaction_type_id)
	{ //Used by Transaction Type
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE transaction_type_id = '" . (int)$transaction_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsCountByVendorId($vendor_id)
	{ //Used by Vendor
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE label = 'vendor' AND label_id = '" . (int)$vendor_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsCountByAccountId($account_id)
	{ //Used by account
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE account_credit_id = '" . (int)$account_id . "' OR account_debit_id = '" . (int)$account_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsByLabel($label, $label_id = 0, $start = 0, $limit = 10)
	{ //Used by Vendor
		$label_data = array(
			'supplier',
			'vendor',
			'customer'
		);

		if (!isset($label) || !in_array($label, $label_data)) {
			$label = '';
		}

		$sql = "SELECT t.*, o.invoice_no, o.invoice_prefix, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id) WHERE t.label = '" . $this->db->escape($label) . "'";

		if (!empty($label_id)) {
			$sql .= " AND t.label_id = '" . (int)$label_id . "'";
		}

		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsCountByLabel($label, $label_id = 0)
	{ //Used by Vendor
		$label_data = array(
			'supplier',
			'vendor',
			'customer'
		);

		if (!isset($label) || !in_array($label, $label_data)) {
			$label = '';
		}

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE label = '" . $this->db->escape($label) . "'";

		if (!empty($label_id)) {
			$sql .= " AND label_id = '" . (int)$label_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsTotalByLabel($label, $label_id = 0)
	{ //Used by Vendor
		$label_data = array(
			'supplier',
			'vendor',
			'customer'
		);

		if (!isset($label) || !in_array($label, $label_data)) {
			$label = '';
		}

		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE label = '" . $this->db->escape($label) . "'";

		if (!empty($label_id)) {
			$sql .= " AND label_id = '" . (int)$label_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsSummary($order_id, $data)
	{
		$sql = "SELECT t.*, tt.*, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.order_id = '" . (int)$order_id . "'";
		// $sql = "SELECT t.*, SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		if (isset($data['client_label']) && in_array($data['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['client_label']) . "'";

			if (isset($data['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['client_id']) . "'";
			}
		}

		if (isset($data['category_label']) && in_array($data['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		if (isset($data['transaction_label']) && in_array($data['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}

		// if (isset($data['account_id'])) {
		// 	$implode[] = "ta.account_id = '" . (int)$data['account_id'] . "'";
		// }

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$group_data = array(
			't.client_label',
			't.client_id',
		);

		if (!isset($data['group']) || !in_array($data['group'], $group_data)) {
			$data['group'] = 't.category_label';
		}

		$sql .= " GROUP BY " . $data['group'] . ", t.transaction_label";
		$sql .= " ORDER BY " . $data['group'] . " ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsTotalSummary($order_id, $data)
	{
		$sql = "SELECT t.transaction_label, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t WHERE t.order_id = '" . (int)$order_id . "'";
		// $sql = "SELECT t.transaction_label, ta.account_id, SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		if (isset($data['client_label']) && in_array($data['client_label'], $this->client_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['client_label']) . "'";

			if (isset($data['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['client_id']) . "'";
			}
		}

		if (isset($data['category_label']) && in_array($data['category_label'], $this->category_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		if (isset($data['transaction_label']) && in_array($data['transaction_label'], $this->transaction_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}

		// if (isset($data['account_id'])) {
		// 	$implode[] = "ta.account_id = '" . (int)$data['account_id'] . "'";
		// }

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_label";

		$query = $this->db->query($sql);

		$total_data = [
			'cashin'	=> 0,
			'cashout'	=> 0
		];

		foreach ($query->rows as $value) {
			$total_data[$value['transaction_label']] = $value['total'];
		}

		return $total_data['cashin'] - $total_data['cashout'];
	}

	//Akan diganti ke getTransactionsSummary($order_id, $label)
	public function getTransactionsLabelSummaryByOrderId($order_id, $label)
	{
		$label_data = array(
			'supplier',
			'vendor',
			'customer'
		);

		if (!isset($label) || !in_array($label, $label_data)) {
			$label = '';
		}

		$sql = "SELECT *, SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

		$sql .= " AND label = '" . $label . "'";

		$sql .= " GROUP BY label_id";

		$sql .= " ORDER BY label_id ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsDescriptionSummaryByOrderId($order_id, $data = array())
	{ //Used by Order > Agreement
		$sql = "SELECT description, MAX(date) AS date, SUM(amount) AS amount FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND label = '" . $this->db->escape($data['label']) . "'";

			if (!empty($data['label_id'])) {
				$sql .= " AND label_id = '" . (int)$data['label_id'] . "'";
			}
		}

		$sql .= " GROUP BY description ORDER BY date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function editTransactionPrintStatus($transaction_id, $printed_status)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "transaction` SET printed = '" . (int)$printed_status . "' WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function setTransactionPrinted($transaction_id)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "transaction` SET printed = '1' WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function editEditPermission($transaction_id, $edit_permission)
	{
		$sql = "UPDATE " . DB_PREFIX . "transaction SET edit_permission = '" . (int)$edit_permission . "' WHERE transaction_id = '" . (int)$transaction_id . "'";

		$query = $this->db->query($sql);
	}
}
