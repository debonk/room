<?php
class ModelAccountingTransaction extends Model {
	public function getTransactionsTotalByOrderId($order_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
}
