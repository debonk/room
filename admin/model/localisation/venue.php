<?php
class ModelLocalisationVenue extends Model {
	public function addVenue($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "venue SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', slots = '" . $this->db->escape($data['slots'] ? json_encode($data['slots']) : '') . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editVenue($venue_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "venue SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', slots = '" . $this->db->escape($data['slots'] ? json_encode($data['slots']) : '') . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE venue_id = '" . (int)$venue_id . "'");
	}

	public function deleteVenue($venue_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "venue WHERE venue_id = '" . (int)$venue_id . "'");
	}

	public function getVenue($venue_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "venue WHERE venue_id = '" . (int)$venue_id . "'");

		return $query->row;
	}

	public function getVenues() {
		$sql = "SELECT * FROM " . DB_PREFIX . "venue ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getVenuesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "venue");

		return $query->row['total'];
	}

	public function getVenueByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "venue WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getSlots() {
		$slots = ['pr', 'cd', 'po', 'mt'];

		return $slots;
	}
}
