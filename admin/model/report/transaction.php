<?php
class ModelReportTransaction extends Model
{
	public function getTransactions($data = [])
	{
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, tt.name AS transaction_type, SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['filter']['account_id'] . "'";
			// $implode[] = "ta.account_id LIKE '" . (int)$data['filter']['account_id'] . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_id ORDER BY t.date DESC, t.transaction_id DESC";
		// $sql .= " GROUP BY t.transaction_id ORDER BY t.date, t.transaction_id ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		// var_dump($sql);die('---breakpoint---');

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTransactionsCount($data = [])
	{
		$sql = "SELECT COUNT(DISTINCT t.transaction_id) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_account ta ON (ta.transaction_id = t.transaction_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['filter']['account_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionsTotal($data = [])
	{
		$sql = "SELECT SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "transaction t ON (t.transaction_id = ta.transaction_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['filter']['account_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTransactionAccounts($transaction_id)
	{
		$query = $this->db->query("SELECT ta.*, a.name AS account FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "account a ON (a.account_id = ta.account_id) WHERE transaction_id = '" . (int)$transaction_id . "'");

		return $query->rows;
	}

	public function getBalanceEnd($data = [])
	{
		unset($data['filter']['date_start']);

		$transaction_end = $this->getTransactionsTotal($data);

		$balance_end = $transaction_end['debit'] - $transaction_end['credit'];

		if (isset($data['start']) && $data['start'] > 0) {
			$data['limit'] = $data['start'];

			$data['start'] = 0;

			$subtotal = $this->getTransactionsSubTotal($data);

			$balance_end -= $subtotal;
		}

		return $balance_end;
	}

	public function getTransactionsSubTotal($data = array())
	{
		$sql = "SELECT SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "transaction t ON (t.transaction_id = ta.transaction_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(t.date) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(t.date) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['account_id'])) {
			$implode[] = "ta.account_id = '" . (int)$data['filter']['account_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY t.transaction_id ORDER BY t.date DESC, t.transaction_id DESC";

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
			$total += $transaction['debit'] - $transaction['credit'];
		}

		return $total;
	}

// Delete
	public function getTransactionsTotalPreviousDel($data = [])
	{
		if (!empty($data['filter']['date_start'])) {
			$sql = "SELECT SUM(ta.debit) AS debit, SUM(ta.credit) AS credit FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "transaction t ON (t.transaction_id = ta.transaction_id) WHERE t.date < '" . $this->db->escape($data['filter']['date_start']) . "'";

			$sql .= " AND ta.account_id = '" . (int)$data['filter']['account_id'] . "'";

			$sql .= " GROUP BY t.transaction_label";

			$query = $this->db->query($sql);

			if ($query->row) {
				$total = $query->row['debit'] - $query->row['credit'];
			} else {
				$total = 0;
			}

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
}
