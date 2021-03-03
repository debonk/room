<?php
class ModelAccountingTransaction extends Model {
	private $client_label_data = ['system', 'customer', 'vendor', 'supplier'];
	private $category_label_data = ['order', 'deposit', 'purchase'];
	private $transaction_label_data = ['initial', 'discount', 'payment', 'refund', 'complete'];
		
	public function addTransaction($data) {
		$field_data = [
			'category_label',
			'transaction_label',
			'client_id',
			'order_id',
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
			
			$data['reference_prefix'] = array_search($data['client_label'], $label_data) . date('ym');
			$data['reference_no'] = $this->getLastReferenceNo($data['reference_prefix']) + 1;
        }

		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', transaction_label = '" . $this->db->escape($data['transaction_label']) . "', client_id = '" . (int)$data['client_id'] . "', order_id = '" . (int)$data['order_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), description = '" . $this->db->escape($data['description']) . "', customer_name = '" . $this->db->escape($data['customer_name']) . "', reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "', reference_no = '" . (int)$data['reference_no'] . "', printed = '0', edit_permission = '0', date_added = NOW(), user_id = '" . $this->user->getId() . "'");

		$transaction_id = $this->db->getLastId();

		foreach ($data['account_data'] as $account_id => $value) {
			if ($value > 0) {
				$debit = $value;
				$credit = 0;
			} elseif ($value < 0) {
				$debit = 0;
				$credit = -$value;
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$account_id . "', debit = '" . (float)$debit . "', credit = '" . (float)$credit . "'");
		}
	}

	public function addTransactionOld($data) {
		if (!isset($data['label_id'])) {
			$data['label_id'] = 0;
		}
		
		if (!isset($data['order_id'])) {
			$data['order_id'] = 0;
		}
		
        if (!isset($data['reference_prefix'])) {
			$label_data = array(
				'B'	=> 'asset',
				'L'	=> 'liability',
				'Q'	=> 'equity',
				'R'	=> 'revenue',
				'E'	=> 'expense'
			);
			
			$data['reference_prefix'] = array_search($data['label'], $label_data) . date('ym');
			$data['reference_no'] = $this->getLastReferenceNo($data['reference_prefix']) + 1;
        }

		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET label = '" . $this->db->escape($data['label']) . "', label_id = '" . (int)$data['label_id'] . "', order_id = '" . (int)$data['order_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), description = '" . $this->db->escape($data['description']) . "', customer_name = '" . $this->db->escape($data['customer_name']) . "', reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "', reference_no = '" . (int)$data['reference_no'] . "', printed = '0', edit_permission = '0', date_added = NOW(), user_id = '" . $this->user->getId() . "'");

		$transaction_id = $this->db->getLastId();

		foreach ($data['account_data'] as $account_id => $value) {
			if ($value > 0) {
				$debit = $value;
				$credit = 0;
			} elseif ($value < 0) {
				$debit = 0;
				$credit = -$value;
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$account_id . "', debit = '" . (float)$debit . "', credit = '" . (float)$credit . "'");
		}
	}

	public function editTransaction($transaction_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "transaction SET edit_permission = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";

		$implode = array();

		if (isset($data['client_label'])) {
			$implode[] = "client_label = '" . $this->db->escape($data['client_label']) . "'";
		}
		
		if (isset($data['category_label'])) {
			$implode[] = "category_label = '" . $this->db->escape($data['category_label']) . "'";
		}
		
		if (isset($data['transaction_label'])) {
			$implode[] = "transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}
		
		if (isset($data['client_id'])) {
			$implode[] = "client_id = '" . $this->db->escape($data['client_id']) . "'";
		}
		
		if (isset($data['date'])) {
			$implode[] = "date = DATE('" . $this->db->escape($data['date']) . "')";
		}
		
		if (isset($data['description'])) {
			$implode[] = "description = '" . $this->db->escape($data['description']) . "'";
		}
		
		if (isset($data['customer_name'])) {
			$implode[] = "customer_name = '" . $this->db->escape($data['customer_name']) . "'";
		}
		
		if ($implode) {
			$sql .= ", " . implode(", ", $implode);
		}

		$sql .= " WHERE transaction_id = '" . (int)$transaction_id . "'";

		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");

		foreach ($data['account_data'] as $account_id => $value) {
			if ($value > 0) {
				$debit = $value;
				$credit = 0;
			} elseif ($value < 0) {
				$debit = 0;
				$credit = -$value;
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$account_id . "', debit = '" . (float)$debit . "', credit = '" . (float)$credit . "'");
		}
	}

	public function editTransactionOld($transaction_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "transaction SET account_from_id = '" . (int)$data['account_from_id'] . "', account_to_id = '" . (int)$data['account_to_id'] . "', edit_permission = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";

		$implode = array();

		if (isset($data['date'])) {
			$implode[] = "date = DATE('" . $this->db->escape($data['date']) . "')";
		}
		
		if (isset($data['description'])) {
			$implode[] = "description = '" . $this->db->escape($data['description']) . "'";
		}
		
		if (isset($data['amount'])) {
			$implode[] = "amount = '" . (float)$data['amount'] . "'";
		}
		
		if (isset($data['customer_name'])) {
			$implode[] = "customer_name = '" . $this->db->escape($data['customer_name']) . "'";
		}
		
		if ($implode) {
			$sql .= ", " . implode(", ", $implode);
		}

		$sql .= " WHERE transaction_id = '" . (int)$transaction_id . "'";

		$this->db->query($sql);
	}

	public function deleteTransaction($transaction_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function getTransaction($transaction_id) {
		$query = $this->db->query("SELECT DISTINCT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.transaction_id = '" . (int)$transaction_id . "'");

		return $query->row;
	}

	public function getTransactions($data = array()) {
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type, o.invoice_no, o.invoice_prefix, o.firstname, o.lastname, a.name AS account, SUM(ta.debit) AS total, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) LEFT JOIN " . DB_PREFIX . "account a ON (a.account_id = ta.account_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		$implode = array();

		if (isset($data['filter_client_label']) && in_array($data['filter_client_label'], $this->client_label_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['filter_client_label']) . "'";

			if (isset($data['filter_client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['filter_client_id']) . "'";
			}
		}

		if (isset($data['filter_category_label']) && in_array($data['category_label'], $this->category_label_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['filter_category_label']) . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_account_id']) && !is_null($data['filter_account_id'])) {
			$implode[] = "t.account_id LIKE '" . (int)$data['filter_account_id'] . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter_username']) . "'";
		}

		if (!empty($data['accounts'])) {
			$accounts_data = "'" . implode("', '", $data['accounts']) . "'";
			
			$implode[] = "ta.account_id IN (" . $accounts_data . ")";
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
			'total',
			// 'account',
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

	public function getTransactionsOld($data = array()) {
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, a1.name AS account_from, a2.name AS account_to, o.invoice_no, o.invoice_prefix, o.firstname, o.lastname, tt.account_type, tt.name AS transaction_type, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = t.account_from_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = t.account_to_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		$implode = array();

		if (!empty($data['filter_label'])) {
			$implode[] = "t.label = '" . $this->db->escape($data['filter_label']) . "'";

			if (!empty($data['filter_label_id'])) {
				$implode[] = "t.label_id = '" . $this->db->escape($data['filter_label_id']) . "'";
			}
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_account_from_id']) && !is_null($data['filter_account_from_id'])) {
			$implode[] = "t.account_from_id LIKE '" . (int)$data['filter_account_from_id'] . "%'";
			// $implode[] = "t.account_from_id = '" . (int)$data['filter_account_from_id'] . "'";
		}

		if (isset($data['filter_account_to_id']) && !is_null($data['filter_account_to_id'])) {
			$implode[] = "t.account_to_id LIKE '" . (int)$data['filter_account_to_id'] . "%'";
			// $implode[] = "t.account_to_id = '" . (int)$data['filter_account_to_id'] . "'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter_username']) . "'";
		}

		if (!empty($data['accounts_to'])) {
			$accounts_to_data = "'" . implode("', '", $data['accounts_to']) . "'";
			
			$implode[] = "t.account_to_id IN (" . $accounts_to_data . ")";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			't.date',
			'account_from',
			'account_to',
			't.description',
			'reference',
			't.customer_name',
			't.amount',
			'u.username'
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

	public function getTransactionsCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a ON (a.account_id = t.account_to_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		$implode = array();

		if (!empty($data['filter_label'])) {
			$implode[] = "t.label = '" . $this->db->escape($data['filter_label']) . "'";

			if (!empty($data['filter_label_id'])) {
				$implode[] = "t.label_id = '" . $this->db->escape($data['filter_label_id']) . "'";
			}
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_account_from_id']) && !is_null($data['filter_account_from_id'])) {
			$implode[] = "t.account_from_id LIKE '" . (int)$data['filter_account_from_id'] . "%'";
		}

		if (isset($data['filter_account_to_id']) && !is_null($data['filter_account_to_id'])) {
			$implode[] = "t.account_to_id LIKE '" . (int)$data['filter_account_to_id'] . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter_username']) . "'";
		}

		if (!empty($data['accounts_to'])) {
			$accounts_to_data = "'" . implode("', '", $data['accounts_to']) . "'";
			
			$implode[] = "t.account_to_id IN (" . $accounts_to_data . ")";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsTotal($data = array()) {
		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a ON (a.account_id = t.account_to_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		$implode = array();

		if (!empty($data['filter_label'])) {
			$implode[] = "t.label = '" . $this->db->escape($data['filter_label']) . "'";

			if (!empty($data['filter_label_id'])) {
				$implode[] = "t.label_id = '" . $this->db->escape($data['filter_label_id']) . "'";
			}
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_account_from_id']) && !is_null($data['filter_account_from_id'])) {
			$implode[] = "t.account_from_id LIKE '" . (int)$data['filter_account_from_id'] . "%'";
		}

		if (isset($data['filter_account_to_id']) && !is_null($data['filter_account_to_id'])) {
			$implode[] = "t.account_to_id LIKE '" . (int)$data['filter_account_to_id'] . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = "CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "t.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer_name'])) {
			$implode[] = "t.customer_name LIKE '%" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter_username']) . "'";
		}

		if (!empty($data['accounts_to'])) {
			$accounts_to_data = "'" . implode("', '", $data['accounts_to']) . "'";
			
			$implode[] = "t.account_to_id IN (" . $accounts_to_data . ")";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsSubTotal($data = array()) {
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

	public function getTransactionsTotalPrevious($data = array()) {
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
	
	public function getLastReferenceNo($reference_prefix) {
		$sql = "SELECT MAX(reference_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_prefix = '" . $this->db->escape($reference_prefix) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

//Cek apakah masih digunakan
	public function getTransactionsByOrderId($order_id, $data = array()) {
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

	public function getTransactionsCountByTransactionTypeId($transaction_type_id) {//Used by Transaction Type
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE transaction_type_id = '" . (int)$transaction_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsCountByVendorId($vendor_id) {//Used by Vendor
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE label = 'vendor' AND label_id = '" . (int)$vendor_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsCountByAccountId($account_id) {//Used by account
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE account_from_id = '" . (int)$account_id . "' OR account_to_id = '" . (int)$account_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsByLabel($label, $label_id = 0, $start = 0, $limit = 10) {//Used by Vendor
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

	public function getTransactionsCountByLabel($label, $label_id = 0) {//Used by Vendor
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

	public function getTransactionsTotalByLabel($label, $label_id = 0) {//Used by Vendor
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

	public function getTransactionsSummary($order_id, $data) {
		$sql = "SELECT t.*, SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) WHERE t.order_id = '" . (int)$order_id . "'";
		// $sql = "SELECT t.*, tt.*, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		$client_label_data = array(
			'customer',
			'vendor',
			'supplier'
		);
		
		if (isset($data['client_label']) && in_array($data['client_label'], $client_label_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['client_label']) . "'";

			if (isset($data['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['client_id']) . "'";
			}
		}

		$category_label_data = array(
			'order',
			'deposit',
			'purchase'
		);
		
		if (isset($data['category_label']) && in_array($data['category_label'], $category_label_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		$transaction_label_data = array(
			'initial',
			'discount',
			'payment',
			'refund',
			'complete'
		);
		
		if (isset($data['transaction_label']) && in_array($data['transaction_label'], $transaction_label_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}

		if (isset($data['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['account_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$group_data = array(
			't.client_label',
			't.t.client_id',
			't.category_label',
			't.transaction_label',
		);
		
		if (!isset($data['group']) || !in_array($data['group'], $group_data)) {
			$data['group'] = 'tt.category_label';
		}

		$sql .= " GROUP BY " . $data['group'];
		$sql .= " ORDER BY " . $data['group'] . " ASC";

		// if (isset($data['sort']) && in_array($data['sort'], $group_data)) {
		// 	$sql .= " ORDER BY " . $data['group'];
		// } else {
		// 	$sql .= " ORDER BY t.label, t.label_id, tt.category_label, tt.account_type";
		// }

		// $sql .= " ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsTotalSummary($order_id, $data) {
		$sql = "SELECT t.transaction_label, SUM(ta.debit) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) WHERE t.order_id = '" . (int)$order_id . "'";
		// $sql = "SELECT t.transaction_label, ta.account_id, SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id) WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		$client_label_data = array(
			'customer',
			'vendor',
			'supplier'
		);
		
		if (isset($data['client_label']) && in_array($data['client_label'], $client_label_data)) {
			$implode[] = "t.client_label = '" . $this->db->escape($data['client_label']) . "'";

			if (isset($data['client_id'])) {
				$implode[] = "t.client_id = '" . $this->db->escape($data['client_id']) . "'";
			}
		}

		$category_label_data = array(
			'order',
			'deposit',
			'purchase'
		);
		
		if (isset($data['category_label']) && in_array($data['category_label'], $category_label_data)) {
			$implode[] = "t.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		$transaction_label_data = array(
			'initial',
			'discount',
			'payment',
			'refund',
			'complete'
		);
		
		if (isset($data['transaction_label']) && in_array($data['transaction_label'], $transaction_label_data)) {
			$implode[] = "t.transaction_label = '" . $this->db->escape($data['transaction_label']) . "'";
		}

		if (isset($data['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['account_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_label";

		$query = $this->db->query($sql);

		$total_data = [
			'payment'	=> 0,
			'refund'	=> 0
		];

		foreach ($query->rows as $value) {
			$total_data[$value['transaction_label']] = $value['total'];
		}

		return $total_data['payment'] - $total_data['refund'];
	}

	public function getTransactionsTotalSummaryOld($order_id, $data) {
		$sql = "SELECT tt.account_type, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.order_id = '" . (int)$order_id . "'";

		$implode = array();

		$label_data = array(
			'customer',
			'vendor',
			'supplier'
		);
		
		if (isset($data['label']) && in_array($data['label'], $label_data)) {
			$implode[] = "t.label = '" . $this->db->escape($data['label']) . "'";

			if (isset($data['label_id'])) {
				$implode[] = "t.label_id = '" . $this->db->escape($data['label_id']) . "'";
			}
		}

		$category_data = array(
			'order',
			'deposit',
			'purchase'
		);
		
		if (isset($data['category_label']) && in_array($data['category_label'], $category_data)) {
			$implode[] = "tt.category_label = '" . $this->db->escape($data['category_label']) . "'";
		}

		$account_type_data = array(
			'D',
			'C'
		);
		
		if (isset($data['account_type']) && in_array($data['account_type'], $account_type_data)) {
			$implode[] = "tt.account_type = '" . $this->db->escape($data['account_type']) . "'";
		}

		if (isset($data['transaction_type_id'])) {
			$implode[] = "transaction_type_id = '" . (int)$data['transaction_type_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY tt.account_type";

		$query = $this->db->query($sql);

		$debit = 0;
		$credit = 0;

		foreach ($query->rows as $value) {
			if ($value['account_type'] == 'D') {
				$debit = $value['total'];
			} elseif ($value['account_type'] == 'C') {
				$credit = $value['total'];
			}
		}

		return $debit - $credit;
	}

	//Akan diganti ke getTransactionsSummary($order_id, $label)
	public function getTransactionsLabelSummaryByOrderId($order_id, $label) {
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

	public function getTransactionsDescriptionSummaryByOrderId($order_id, $data = array()) {//Used by Order > Agreement
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

	public function editTransactionPrintStatus($transaction_id, $printed_status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "transaction` SET printed = '" . (int)$printed_status . "' WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function setTransactionPrinted($transaction_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "transaction` SET printed = '1' WHERE transaction_id = '" . (int)$transaction_id . "'");
	}

	public function editEditPermission($transaction_id, $edit_permission) {
		$sql = "UPDATE " . DB_PREFIX . "transaction SET edit_permission = '" . (int)$edit_permission . "' WHERE transaction_id = '" . (int)$transaction_id . "'";

		$query = $this->db->query($sql);
	}
}
