<?php
class ModelPurchaseSupplier extends Model {
	public function addSupplier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET supplier_name = '" . $this->db->escape($data['supplier_name']) . "', vendor_type_id = '" . (int)$data['vendor_type_id'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', website = '" . $this->db->escape($data['website']) . "', address = '" . $this->db->escape($data['address']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editSupplier($supplier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "supplier SET supplier_name = '" . $this->db->escape($data['supplier_name']) . "', vendor_type_id = '" . (int)$data['vendor_type_id'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', website = '" . $this->db->escape($data['website']) . "', address = '" . $this->db->escape($data['address']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', status = '" . (int)$data['status'] . "' WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT DISTINCT s.*, vt.name AS vendor_type, vt.deposit FROM " . DB_PREFIX . "supplier s LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = s.vendor_type_id) WHERE s.supplier_id = '" . (int)$supplier_id . "'");

		return $query->row;
	}

	public function getSuppliers($data = array()) {
		$sql = "SELECT s.*, vt.name AS vendor_type, vt.deposit FROM " . DB_PREFIX . "supplier s LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = s.vendor_type_id)";

		$implode = array();

		if (!empty($data['filter_supplier_name'])) {
			$implode[] = "s.supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
		}

		if (isset($data['filter_vendor_type_id'])) {
			$implode[] = "s.vendor_type_id = '" . (int)$data['filter_vendor_type_id'] . "'";
		} elseif (isset($data['filter_vendor'])) {//Supplier yg sekaligus vendor.
			$implode[] = "s.vendor_type_id > 0";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "s.status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			's.supplier_name',
			'vendor_type',
			's.telephone',
			's.email',
			's.status',
			's.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY s.supplier_name";
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

	public function getSupplierByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "supplier WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getSuppliersCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier";

		$implode = array();

		if (!empty($data['filter_supplier_name'])) {
			$implode[] = "supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
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

	public function getSuppliersCountByVendorTypeId($vendor_type_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier WHERE vendor_type_id = '" . (int)$vendor_type_id . "'");

		return $query->row['total'];
	}
	
	public function getSuppliersByStatus($status) {
		$sql = "SELECT v.*, vt.name AS vendor_type FROM " . DB_PREFIX . "supplier v LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id) WHERE v.status = '" . (int)$status . "' ORDER BY v.supplier_name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
