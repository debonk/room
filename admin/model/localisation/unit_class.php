<?php
class ModelLocalisationUnitClass extends Model {
	public function addUnitClass($data) {
		$query = $this->db->query("SELECT MAX(unit_class_id) AS total FROM " . DB_PREFIX . "unit_class");
		$unit_class_id = $query->row['total'] + 1;

		foreach ($data['unit_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "unit_class SET unit_class_id = '" . (int)$unit_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}

		$this->cache->delete('unit_class');
	}

	public function editUnitClass($unit_class_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "unit_class WHERE unit_class_id = '" . (int)$unit_class_id . "'");

		foreach ($data['unit_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "unit_class SET unit_class_id = '" . (int)$unit_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}

		$this->cache->delete('unit_class');
	}

	public function deleteUnitClass($unit_class_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "unit_class WHERE unit_class_id = '" . (int)$unit_class_id . "'");

		$this->cache->delete('unit_class');
	}

	public function getUnitClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "unit_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY title";

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
		} else {
			$unit_class_data = $this->cache->get('unit_class.' . (int)$this->config->get('config_language_id'));

			if (!$unit_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY title ASC");

				$unit_class_data = $query->rows;

				$this->cache->set('unit_class.' . (int)$this->config->get('config_language_id'), $unit_class_data);
			}

			return $unit_class_data;
		}
	}

	public function getUnitClass($unit_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class WHERE unit_class_id = '" . (int)$unit_class_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getUnitClassDescriptions($unit_class_id) {
		$unit_class_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class WHERE unit_class_id = '" . (int)$unit_class_id . "'");

		foreach ($query->rows as $result) {
			$unit_class_data[$result['language_id']] = array(
				'title' => $result['title']
			);
		}

		return $unit_class_data;
	}

	public function getUnitClassesCount() {
		$query = $this->db->query("SELECT COUNT(DISTINCT unit_class_id) AS total FROM " . DB_PREFIX . "unit_class");

		return $query->row['total'];
	}
}
