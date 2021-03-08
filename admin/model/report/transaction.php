<?php
class ModelReportTransaction extends Model
{
	public function getTransactions($data = array())
	{
		$sql = "SELECT t.*, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, a1.name AS account_from, a2.name AS account_to, tt.account_type, tt.name AS transaction_type FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "account a1 ON (a1.account_id = t.account_from_id) LEFT JOIN " . DB_PREFIX . "account a2 ON (a2.account_id = t.account_to_id) LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id)";

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

	public function getTransactionsCount($data = array())
	{
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

	public function getTransactionsSubTotal($data = array())
	{
		$sql = "SELECT t.account_to_id, tt.account_type, t.amount FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id)";

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
			// # Maintain Versi 1
			// if (empty($transaction['account_type'])) {
			// 	$transaction['account_type'] = 'D';
			// }
			// # End Maintain
			// $transaction['amount'] *= $transaction['account_type'] == 'D' ? 1 : -1;

			if ($transaction['account_to_id'] == $data['filter_account_id']) {
				$total += $transaction['amount'];
			} else {
				$total -= $transaction['amount'];
			}
		}

		return $total;
	}

	public function getTransactionsTotalPrevious($data = [])
	{
		if (!empty($data['filter_date_start'])) {
			$sql = "SELECT t.account_to_id, tt.account_type, SUM(t.amount) AS total FROM " . DB_PREFIX . "transaction t LEFT JOIN " . DB_PREFIX . "transaction_type tt ON (tt.transaction_type_id = t.transaction_type_id) WHERE t.date < '" . $this->db->escape($data['filter_date_start']) . "'";

			$sql .= " AND (t.account_to_id = '" . (int)$data['filter_account_id'] . "' OR t.account_from_id = '" . (int)$data['filter_account_id'] . "')";

			$sql .= " GROUP BY t.account_to_id";

			$query = $this->db->query($sql);

			$total = 0;

			foreach ($query->rows as $value) {
				// # Maintain Versi 1
				// if (empty($value['account_type'])) {
				// 	$value['account_type'] = 'D';
				// }
				// # End Maintain

				// $value['total'] *= $value['account_type'] == 'D' ? 1 : -1;

				if ($value['account_to_id'] == $data['filter_account_id']) {
					$total += $value['total'];
				} else {
					$total -= $value['total'];
				}
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
