<?php
class ModelLocalisationCeremony extends Model {
	public function addCeremony($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ceremony SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editCeremony($ceremony_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ceremony SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE ceremony_id = '" . (int)$ceremony_id . "'");
	}

	public function deleteCeremony($ceremony_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ceremony WHERE ceremony_id = '" . (int)$ceremony_id . "'");
	}

	public function getCeremony($ceremony_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ceremony WHERE ceremony_id = '" . (int)$ceremony_id . "'");

		return $query->row;
	}

	public function getCeremonies() {
		$sql = "SELECT * FROM " . DB_PREFIX . "ceremony ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCeremoniesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ceremony");

		return $query->row['total'];
	}
}
