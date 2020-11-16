<?php
class ModelLocalisationCeremony extends Model {
	public function getCeremony($ceremony_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ceremony WHERE ceremony_id = '" . (int)$ceremony_id . "'");

		return $query->row;
	}

	// public function getCeremonies() {
		// $sql = "SELECT * FROM " . DB_PREFIX . "ceremony ORDER BY sort_order ASC";

		// $query = $this->db->query($sql);

		// return $query->rows;
	// }
}
