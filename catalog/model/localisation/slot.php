<?php
class ModelLocalisationSlot extends Model {
	public function getSlot($slot_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "slot WHERE slot_id = '" . (int)$slot_id . "'");

		return $query->row;
	}

	// public function getSlots() {
		// $sql = "SELECT * FROM " . DB_PREFIX . "slot ORDER BY sort_order ASC";

		// $query = $this->db->query($sql);

		// return $query->rows;
	// }
}
