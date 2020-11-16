<?php
class ModelCatalogVendorType extends Model {
	public function addVendorType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_type SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		return $vendor_type_id;
	}

	public function editVendorType($vendor_type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "vendor_type SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE vendor_type_id = '" . (int)$vendor_type_id . "'");
	}

	public function deleteVendorType($vendor_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_type WHERE vendor_type_id = '" . (int)$vendor_type_id . "'");
	}

	public function getVendorType($vendor_type_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_type WHERE vendor_type_id = '" . (int)$vendor_type_id . "'");

		return $query->row;
	}

	public function getVendorTypes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "vendor_type";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getTotalVendorTypes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor_type");

		return $query->row['total'];
	}
}
