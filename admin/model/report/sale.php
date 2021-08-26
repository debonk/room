<?php
class ModelReportSale extends Model
{
	public function getTotalSales($data = array())
	{
		$sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByCountry()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total, SUM(o.total) AS amount, c.iso_code_2 FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.order_status_id > '0' GROUP BY o.payment_country_id");

		return $query->rows;
	}

	public function getTotalOrdersByDay()
	{
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByWeek()
	{
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByMonth()
	{
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByYear()
	{
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getOrders($data = array())
	{
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC";

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

	public function getTotalOrders($data = array())
	{
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), DAY(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), WEEK(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrdersCommission($data = array())
	{
		$sql = "SELECT o.order_id, o.title, o.event_date, CONCAT(o.firstname, ' ', o.lastname) AS customer, os.name AS order_status, o.invoice_no, o.invoice_prefix, o.total, o.currency_code, o.currency_value, o.date_added, op.name AS primary_product, o.user_id, u.username FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id AND op.primary_type = 1) LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = o.user_id)";

		if (isset($data['filter']['order_status'])) {
			$implode = implode(', ', $data['filter']['order_status']);

			if ($implode) {
				$sql .= " WHERE o.order_status_id IN (" . $implode . ")";
			}
		}

		if (!empty($data['filter']['date_added_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter']['date_added_start']) . "')";
		}

		if (!empty($data['filter']['date_added_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter']['date_added_end']) . "')";
		}

		if (!empty($data['filter']['date_event_start'])) {
			$sql .= " AND DATE(o.event_date) >= DATE('" . $this->db->escape($data['filter']['date_event_start']) . "')";
		}

		if (!empty($data['filter']['date_event_end'])) {
			$sql .= " AND DATE(o.event_date) <= DATE('" . $this->db->escape($data['filter']['date_event_end']) . "')";
		}

		if (!empty($data['filter']['username'])) {
			$sql .= " AND u.username LIKE '%" . $this->db->escape($data['filter']['username']) . "%'";
		}

		$sort_data = array(
			'o.event_date',
			'o.date_added',
			'order_status',
			'username'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.date_added";
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

	public function getOrdersCommissionCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = o.user_id)";

		if (isset($data['filter']['order_status'])) {
			$implode = implode(', ', $data['filter']['order_status']);

			if ($implode) {
				$sql .= " WHERE o.order_status_id IN (" . $implode . ")";
			}
		}

		if (!empty($data['filter']['date_added_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter']['date_added_start']) . "')";
		}

		if (!empty($data['filter']['date_added_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter']['date_added_end']) . "')";
		}

		if (!empty($data['filter']['date_event_start'])) {
			$sql .= " AND DATE(o.event_date) >= DATE('" . $this->db->escape($data['filter']['date_event_start']) . "')";
		}

		if (!empty($data['filter']['date_event_end'])) {
			$sql .= " AND DATE(o.event_date) <= DATE('" . $this->db->escape($data['filter']['date_event_end']) . "')";
		}

		if (!empty($data['filter']['username'])) {
			$sql .= " AND u.username LIKE '%" . $this->db->escape($data['filter']['username']) . "%'";
		}

		// if (isset($data['group']) && $data['group'] == 'username') {
		// 	$sql .= " GROUP BY u.username";
		// }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrderProductsCommission($order_id) {
		$sql = "SELECT SUM(commission) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTaxes($data = array())
	{
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalTaxes($data = array())
	{
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array())
	{
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalShipping($data = array())
	{
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function createDocumentsView()
	{
		$view_name = DB_PREFIX . 'view_order_document';

		$sql = "SELECT 1 AS exist FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . $this->db->escape($view_name) . "'";

		$query = $this->db->query($sql);

		if (!$query->num_rows) {
			$implode_sql = array();

			$implode_sql[] = "SELECT 'customer-order' AS type, o.order_id AS type_id, o.order_id, DATE(o.date_added) AS date, CONCAT(o.invoice_prefix, LPAD(o.invoice_no, 4, '0')) AS reference, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.printed, u.username FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = o.user_id) WHERE o.order_id > 0 AND o.invoice_no > 0 ";

			$implode_sql[] = "SELECT CONCAT(t.client_label, '-transaction') AS type, t.transaction_id AS type_id, t.order_id, t.date AS date, CONCAT(t.reference_prefix, LPAD(t.reference_no, 4, '0')) AS reference, t.customer_name AS customer, t.printed, u.username FROM `" . DB_PREFIX . "transaction` t LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = t.user_id) WHERE t.client_label IN ('vendor', 'customer')";

			$implode_sql[] = "SELECT CONCAT(od.client_type, '-', od.document_type) AS type, od.order_document_id AS type_id, od.order_id, od.date AS date, CONCAT(od.reference_prefix, LPAD(od.reference_no, 4, '0')) AS reference, ov.vendor_name AS customer, od.printed, u.username FROM `" . DB_PREFIX . "order_document` od LEFT JOIN `" . DB_PREFIX . "order_vendor` ov ON (ov.order_vendor_id = od.client_id) LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = od.user_id)";

			if ($implode_sql) {
				$sql = " CREATE VIEW " . $view_name . " AS " . implode(" UNION ", $implode_sql);
			}

			$query = $this->db->query($sql);
		}
	}

	public function getDocuments($data = array())
	{
		$this->createDocumentsView();

		$sql = "SELECT * FROM `" . DB_PREFIX . "view_order_document`";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = " order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = " reference LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'type',
			'order_id',
			'date',
			'reference',
			'customer',
			'printed',
			'username',
			'order_id DESC, reference'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY order_id DESC, reference";
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

	public function getDocumentsCount($data = array())
	{
		$this->createDocumentsView();

		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "view_order_document`";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = " order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = " reference LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
