<?php
class ModelCatalogVendor extends Model {
	public function addVendor($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor SET vendor_name = '" . $this->db->escape($data['vendor_name']) . "', vendor_type_id = '" . (int)$data['vendor_type_id'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', website = '" . $this->db->escape($data['website']) . "', address = '" . $this->db->escape($data['address']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editVendor($vendor_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "vendor SET vendor_name = '" . $this->db->escape($data['vendor_name']) . "', vendor_type_id = '" . (int)$data['vendor_type_id'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', website = '" . $this->db->escape($data['website']) . "', address = '" . $this->db->escape($data['address']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', status = '" . (int)$data['status'] . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
	}

	public function deleteVendor($vendor_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendor WHERE vendor_id = '" . (int)$vendor_id . "'");
	}

	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT DISTINCT v.*, vt.name AS vendor_type, vt.deposit FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id) WHERE v.vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getVendorByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "vendor WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getVendors($data = array()) {
		$sql = "SELECT v.*, vt.name AS vendor_type, vt.deposit FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id)";

		$implode = array();

		if (!empty($data['filter_vendor_name'])) {
			$implode[] = "v.vendor_name LIKE '%" . $this->db->escape($data['filter_vendor_name']) . "%'";
		}

		if (!empty($data['filter_vendor_type_id'])) {
			$implode[] = "v.vendor_type_id = '" . (int)$data['filter_vendor_type_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "v.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'v.vendor_name',
			'vendor_type',
			'v.telephone',
			'v.email',
			'v.status',
			'v.date_added',
			'vt.sort_order',
			'vt.sort_order ASC, v.vendor_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.vendor_name";
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

	public function getVendorsCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor";

		$implode = array();

		if (!empty($data['filter_vendor_name'])) {
			$implode[] = "vendor_name LIKE '%" . $this->db->escape($data['filter_vendor_name']) . "%'";
		}

		if (!empty($data['filter_vendor_type_id'])) {
			$implode[] = "vendor_type_id = '" . (int)$data['filter_vendor_type_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getVendorsCountByVendorTypeId($vendor_type_id) {//Used by Vendor Type
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor WHERE vendor_type_id = '" . (int)$vendor_type_id . "'");

		return $query->row['total'];
	}
	
	public function getVendorsByStatus($status) {//Used by order_info
		$sql = "SELECT v.*, vt.name AS vendor_type FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id) WHERE v.status = '" . (int)$status . "' ORDER BY v.vendor_name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
