<?php
class ModelAccountingTransaction extends Model { //utk transaksi akuntansi
	public function getTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "' ORDER BY date ASC");

		return $query->rows;
	}

	public function getTransactionsTotalSummary($order_id, $data) {
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
			'deposit'
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

		if (!empty($data['account_from_id'])) {
			$implode[] = "account_from_id = '" . (int)$data['account_from_id'] . "'";
		}

		if (!empty($data['account_to_id'])) {
			$implode[] = "account_to_id = '" . (int)$data['account_to_id'] . "'";
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
