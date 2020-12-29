<?php
class ModelAccountingTransaction extends Model { //utk transaksi akuntansi
	public function getTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "' ORDER BY date ASC");

		return $query->rows;
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

	public function getTransactionNoMax($reference_no) {
		$sql = "SELECT MAX(transaction_no) AS total FROM `" . DB_PREFIX . "transaction` WHERE reference_no = '" . $this->db->escape($reference_no) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
