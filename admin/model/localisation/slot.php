<?php
class ModelLocalisationSlot extends Model {
	public function addSlot($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "slot SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editSlot($slot_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "slot SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE slot_id = '" . (int)$slot_id . "'");
	}

	public function deleteSlot($slot_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "slot WHERE slot_id = '" . (int)$slot_id . "'");
	}

	public function getSlot($slot_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "slot WHERE slot_id = '" . (int)$slot_id . "'");

		return $query->row;
	}

	public function getSlots() {
		$sql = "SELECT * FROM " . DB_PREFIX . "slot ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSlotsCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "slot");

		return $query->row['total'];
	}
}
