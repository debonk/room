<?php
class ModelAccountingTransactionType extends Model
{
	public function addTransactionType($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editTransactionType($transaction_type_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "transaction_type SET client_label = '" . $this->db->escape($data['client_label']) . "', category_label = '" . $this->db->escape($data['category_label']) . "', name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
	}

	public function deleteTransactionType($transaction_type_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");
	}

	public function getTransactionType($transaction_type_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction_type WHERE transaction_type_id = '" . (int)$transaction_type_id . "'");

		return $query->row;
	}

	public function getTransactionTypes($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "transaction_type";

		$sort_data = array(
			'client_label',
			'category_label',
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getTransactionTypesCount()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction_type");

		return $query->row['total'];
	}

	public function getTransactionTypesByLabel($client_label, $category_label = '')
	{
		if (!empty($client_label) || !empty($category_label)) {
			$sql = "SELECT * FROM " . DB_PREFIX . "transaction_type";

			$implode = array();

			if (!empty($client_label)) {
				$implode[] = "client_label = '" . $this->db->escape($client_label) . "'";
			}

			if (!empty($category_label)) {
				$implode[] = "category_label = '" . $this->db->escape($category_label) . "'";
			}

			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}

			$sql .= " ORDER BY client_label, sort_order ASC";

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			return;
		}
	}

	public function geTransactionTypeLabels()
	{
		$sql = "SELECT DISTINCT CONCAT(client_label, '-', category_label) AS label FROM " . DB_PREFIX . "transaction_type ORDER BY label ASC";

		$query = $this->db->query($sql);

		$label_data = [];

		foreach ($query->rows as $label) {
			$label_data[] = $label['label'];
		}

		return $label_data;
	}
}
