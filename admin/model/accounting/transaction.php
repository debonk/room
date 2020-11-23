<?php
class ModelAccountingTransaction extends Model {
	public function addTransaction($data) {
		if (!isset($data['label'])) {
			$data['label'] = '';
		}
		
		if (!isset($data['label_id'])) {
			$data['label_id'] = 0;
		}
		
		if (!isset($data['order_id'])) {
			$data['order_id'] = 0;
		}
		
		if (!isset($data['transaction_no'])) {
			$data['transaction_no'] = 0;
		}
		
		if (!isset($data['payment_method'])) {
			$data['payment_method'] = '';
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET account_from_id = '" . (int)$data['account_from_id'] . "', account_to_id = '" . (int)$data['account_to_id'] . "', label = '" . $this->db->escape($data['label']) . "', label_id = '" . (int)$data['label_id'] . "', order_id = '" . (int)$data['order_id'] . "', date = DATE('" . $this->db->escape($data['date']) . "'), payment_method = '" . $this->db->escape($data['payment_method']) . "', description = '" . $this->db->escape($data['description']) . "', amount = '" . (float)$data['amount'] . "', customer_name = '" . $this->db->escape($data['customer_name']) . "', reference_no = '" . $this->db->escape($data['reference_no']) . "', transaction_no = '" . (int)$data['transaction_no'] . "', edit_permission = '0', date_added = NOW(), user_id = '" . $this->user->getId() . "'");
	}

	public function editTransaction($transaction_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "transaction SET account_from_id = '" . (int)$data['account_from_id'] . "', account_to_id = '" . (int)$data['account_to_id'] . "', edit_permission = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";

		$implode = array();

		// if (!isset($data['label'])) {
			// $implode[] = "label = '" . $this->db->escape($data['label']) . "'";
		// }
		
		// if (!isset($data['label_id'])) {
			// $implode[] = "label_id = '" . (int)$data['label_id'] . "'";
		// }
		
		// if (!isset($data['order_id'])) {
			// $implode[] = "order_id = '" . (int)$data['order_id'] . "'";
		// }
		
		// if (!isset($data['transaction_no'])) {
			// $implode[] = "transaction_no = '" . (int)$data['transaction_no'] . "'";
		// }
		
		// if (!isset($data['payment_method'])) {
			// $implode[] = "payment_method = '" . $this->db->escape($data['payment_method']) . "'";
		// }
		
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
	}

	public function getTransaction($transaction_id) {
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(reference_no, LPAD(transaction_no, 4, '0')) AS reference FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");

		return $query->row;
	}

	public function getTransactions($data = array()) {
		$sql = "SELECT t.*, CONCAT(t.reference_no, LPAD(t.transaction_no, 4, '0')) AS reference, a1.name AS account_from, a2.name AS account_to, o.invoice_no, o.invoice_prefix, o.firstname, o.lastname, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = t.account_from_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = t.account_to_id) LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = t.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id)";

		$implode = array();

		if (!empty($data['filter_label'])) {
			$implode[] = "t.label = '" . $this->db->escape($data['filter_label']) . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_from_id']) || $data['filter_account_from_id'] == '0') {
			$implode[] = "t.account_from_id = '" . (int)$data['filter_account_from_id'] . "'";
		}

		if (!empty($data['filter_account_to_id']) || $data['filter_account_to_id'] == '0') {
			$implode[] = "t.account_to_id = '" . (int)$data['filter_account_to_id'] . "'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference_no'])) {
			$implode[] = "CONCAT(t.reference_no, LPAD(t.transaction_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference_no']) . "%'";
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
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_from_id'])) {
			$implode[] = "t.account_from_id = '" . (int)$data['filter_account_from_id'] . "'";
		}

		if (!empty($data['filter_account_to_id'])) {
			$implode[] = "t.account_to_id = '" . (int)$data['filter_account_to_id'] . "'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference_no'])) {
			$implode[] = "CONCAT(t.reference_no, LPAD(t.transaction_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference_no']) . "%'";
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
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_from_id'])) {
			$implode[] = "t.account_from_id = '" . (int)$data['filter_account_from_id'] . "'";
		}

		if (!empty($data['filter_account_to_id'])) {
			$implode[] = "t.account_to_id = '" . (int)$data['filter_account_to_id'] . "'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "t.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (!empty($data['filter_reference_no'])) {
			$implode[] = "CONCAT(t.reference_no, LPAD(t.transaction_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference_no']) . "%'";
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
	
	public function getTransactionNoMax($reference_no) {
		$sql = "SELECT MAX(transaction_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_no = '" . $this->db->escape($reference_no) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsByOrderId($order_id, $data = array()) {
		$sql = "SELECT t.*, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id) WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND t.label = '" . $this->db->escape($data['label']) . "'";
			
			if (!empty($data['label_id'])) {
				$sql .= " AND t.label_id = '" . (int)$data['label_id'] . "'";
			}
		}

//Cek cara sort yg dibutuhkan pada account > balance
		$sort_data = array(
			't.order_id',
			't.date'
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
				$data['limit'] = 10;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsTotalByOrderId($order_id, $data = array()) {
		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND label = '" . $this->db->escape($data['label']) . "'";
			
			if (!empty($data['label_id'])) {
				$sql .= " AND label_id = '" . (int)$data['label_id'] . "'";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsCountByOrderId($order_id, $data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND label = '" . $this->db->escape($data['label']) . "'";
			
			if (!empty($data['label_id'])) {
				$sql .= " AND label_id = '" . (int)$data['label_id'] . "'";
			}
		}

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
			'vendor',
			'customer'
		);
		
		if (!isset($label) || !in_array($label, $label_data)) {
			$label = 'customer';
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
			'vendor',
			'customer'
		);
		
		if (!isset($label) || !in_array($label, $label_data)) {
			$label = 'customer';
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
			'vendor',
			'customer'
		);
		
		if (!isset($label) || !in_array($label, $label_data)) {
			$label = 'customer';
		}

		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE label = '" . $this->db->escape($label) . "'";

		if (!empty($label_id)) {
			$sql .= " AND label_id = '" . (int)$label_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsLabelSummaryByOrderId($order_id, $label) {
		$label_data = array(
			'vendor',
			'customer'
		);
		
		if (!isset($label) || !in_array($label, $label_data)) {
			$label = 'customer';
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
