<?php
class ModelUserUser extends Model {
	public function getUserGroupId($user_id) {
		$query = $this->db->query("SELECT user_group_id FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

		return $query->row['user_group_id'];
	}

	public function checkUser($user_id) {
		$query = $this->db->query("SELECT user_id FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");

		return $query->row;
	}
}