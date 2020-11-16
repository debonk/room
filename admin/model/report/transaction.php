<?php
class ModelReportTransaction extends Model {
/* 	public function getTransaction($transaction_id) {
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(reference_no, LPAD(transaction_no, 4, '0')) AS reference FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");

		return $query->row;
	}
 */
	public function getTransactions($data = array()) {
		$sql = "SELECT t.*, CONCAT(t.reference_no, LPAD(t.transaction_no, 4, '0')) AS reference, a1.name AS account_from, a2.name AS account_to FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = t.account_from_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = t.account_to_id)";

		$implode = array();

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_id'])) {
			$implode[] = "(t.account_from_id = '" . (int)$data['filter_account_id'] . "' OR t.account_to_id = '" . (int)$data['filter_account_id'] . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY t.date, t.transaction_id ASC";
		
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction t";

		$implode = array();

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_id'])) {
			$implode[] = "(t.account_from_id = '" . (int)$data['filter_account_id'] . "' OR t.account_to_id = '" . (int)$data['filter_account_id'] . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

/* 	public function getTransactionsTotal($data = array()) {
		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction t";

		$implode = array();

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_id'])) {
			$implode[] = "(t.account_from_id = '" . (int)$data['filter_account_id'] . "' OR t.account_to_id = '" . (int)$data['filter_account_id'] . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
 */
	public function getTransactionsSubTotal($data = array()) {
		$sql = "SELECT account_to_id, amount FROM " . DB_PREFIX . "transaction t";

		$implode = array();

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_id'])) {
			$implode[] = "(t.account_from_id = '" . (int)$data['filter_account_id'] . "' OR t.account_to_id = '" . (int)$data['filter_account_id'] . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY t.date, t.transaction_id ASC";
		
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
		
		$total = 0;
		
		foreach ($query->rows as $transaction) {
			if ($transaction['account_to_id'] == (int)$data['filter_account_id']) {
				$total += $transaction['amount'];
			} else {
				$total -= $transaction['amount'];
			}
		}

		return $total;
	}

	public function getTransactionsTotalPrevious($data = array()) {
		if (!empty($data['filter_date_start'])) {
			$debit_query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE DATE(date) < '" . $this->db->escape($data['filter_date_start']) . "' AND account_to_id = '" . (int)$data['filter_account_id'] . "'");
			$credit_query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE DATE(date) < '" . $this->db->escape($data['filter_date_start']) . "' AND account_from_id = '" . (int)$data['filter_account_id'] . "'");

			$total = $debit_query->row['total'] - $credit_query->row['total'];
			
			if (isset($data['start']) && $data['start'] > 0) {
				$data['limit'] = $data['start'];

				$data['start'] = 0;

				$subtotal = $this->getTransactionsSubTotal($data);
				
				$total += $subtotal;
			}

		} else {
			$total = 0;
		}
		
		return $total;
	}
	
/* 	public function getTransactionsByOrderId($order_id, $data = array()) {
		$sql = "SELECT t.*, u.username FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = t.user_id) WHERE order_id = '" . (int)$order_id . "'";

		if (!empty($data['label'])) {
			$sql .= " AND t.label = '" . $this->db->escape($data['label']) . "'";
			
			if (!empty($data['label_id'])) {
				$sql .= " AND t.label_id = '" . (int)$data['label_id'] . "'";
			}
		}

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
 */
}
