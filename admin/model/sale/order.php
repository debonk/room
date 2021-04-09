<?php
class ModelSaleOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, s.name AS slot, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, (SELECT u.username FROM " . DB_PREFIX . "user u WHERE o.user_id = u.user_id) AS username FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "slot` s ON (s.slot_id = o.slot_id) WHERE o.order_id = '" . (int)$order_id . "'");
		// $order_query = $this->db->query("SELECT *, oo.value AS session_slot, s.name AS slot, cr.name AS ceremony, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, (SELECT u.username FROM " . DB_PREFIX . "user u WHERE o.user_id = u.user_id) AS username FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_option` oo ON (oo.order_id = o.order_id AND name = 'Session Slot') LEFT JOIN `" . DB_PREFIX . "slot` s ON (s.slot_id = o.slot_id) LEFT JOIN `" . DB_PREFIX . "ceremony` cr ON (cr.ceremony_id = o.ceremony_id) WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$customer_group_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$order_query->row['customer_group_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if ($customer_group_query->num_rows) {
				$customer_group = $customer_group_query->row['name'];
			} else {
				$customer_group = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'printed'                 => $order_query->row['printed'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'customer_group'          => $customer_group,
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'id_no'                	  => $order_query->row['id_no'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'title'              	  => $order_query->row['title'],
				'event_date'              => $order_query->row['event_date'],
				'slot_id'             	  => $order_query->row['slot_id'],
				'slot'             	  	  => $order_query->row['slot'],
				// 'ceremony_id'             => $order_query->row['ceremony_id'],
				// 'ceremony'                => $order_query->row['ceremony'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_profession'      => $order_query->row['payment_profession'],
				'payment_position'        => $order_query->row['payment_position'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'username'           	  => $order_query->row['username']
			);
		} else {
			return;
		}
	}

	public function getOrders($data = array()) {
		// $sql = "SELECT o.order_id, o.title, o.event_date, oo.value AS session_slot, s.code AS slot_code, s.name AS slot, cr.name AS ceremony, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id, os.name AS order_status, os.class AS order_status_class, o.invoice_no, o.invoice_prefix, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, op.name AS primary_product, op.model, u.username, (SELECT SUM(t.amount) FROM `" . DB_PREFIX . "transaction` t WHERE t.order_id = o.order_id AND t.label IN('customer', 'vendor')) AS total_paid FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "slot` s ON (s.slot_id = o.slot_id) LEFT JOIN `" . DB_PREFIX . "ceremony` cr ON (cr.ceremony_id = o.ceremony_id) LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id AND op.primary_type = 1) LEFT JOIN `" . DB_PREFIX . "order_option` oo ON (oo.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = o.user_id)";
		$sql = "SELECT o.order_id, o.title, o.event_date, s.code AS slot_code, s.name AS slot, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id, os.name AS order_status, os.class AS order_status_class, o.invoice_no, o.invoice_prefix, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, op.name AS primary_product, op.model, op.slot_prefix, u.username FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "slot` s ON (s.slot_id = o.slot_id) LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id AND op.primary_type = 1) LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = o.user_id)";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_month'])) {
			$sql .= " AND DATE_FORMAT(o.event_date,'%b %Y') = '" . $this->db->escape($data['filter_month']) . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_event_date'])) {
			$sql .= " AND DATE(o.event_date) = DATE('" . $this->db->escape($data['filter_event_date']) . "')";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'o.event_date',
			'primary_product',
			'customer',
			'status',
			'o.date_added',
			'o.total',
			'u.username'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.event_date";
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

	public function getOrderStatusId($order_id) {
		$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['order_status_id'];
	}

	public function getOrderProduct($order_id, $product_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getOrderProducts($order_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' ORDER BY primary_type DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrderPrimaryProduct($order_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND primary_type = '1'");

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$sql = "SELECT oo.*, o.sort_order FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option po ON (po.product_option_id = oo.product_option_id) LEFT JOIN " . DB_PREFIX . "option o ON (po.option_id = o.option_id) WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "' ORDER BY o.sort_order ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrderAttributes($order_id, $order_product_id) {
		$sql = "SELECT oa.*, agd.name AS attribute_group, ad.name AS attribute FROM " . DB_PREFIX . "order_attribute oa LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (agd.attribute_group_id = oa.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (ad.attribute_id = oa.attribute_id) WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "' ORDER BY oa.order_attribute_id ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addOrderVendor($order_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_vendor SET order_id = '" . (int)$order_id . "', vendor_id = '" . (int)$data['vendor_id'] . "', vendor_name = '" . $this->db->escape($data['vendor_name']) . "', vendor_type = '" . $this->db->escape($data['vendor_type']) . "', date_added = NOW(), user_id = '" . (int)$this->user->getId() . "'");

		$order_vendor_id = $this->db->getLastId();
		
		return $order_vendor_id;
	}

	public function deleteOrderVendor($order_id, $vendor_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_vendor WHERE order_id = '" . (int)$order_id . "' AND vendor_id = '" . (int)$vendor_id . "'");
	}

	public function getOrderVendor($order_id, $vendor_id) {
		$query = $this->db->query("SELECT DISTINCT ov.*, v.*, vt.name AS vendor_type, vt.deposit, (SELECT SUM(t.amount) FROM " . DB_PREFIX . "transaction t WHERE t.order_id = ov.order_id AND t.client_label = 'vendor' AND t.client_id = v.vendor_id) AS total FROM " . DB_PREFIX . "order_vendor ov LEFT JOIN " . DB_PREFIX . "vendor v ON (v.vendor_id = ov.vendor_id) LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id) WHERE ov.order_id = '" . (int)$order_id . "' AND ov.vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getOrderVendorByOrderVendorId($order_vendor_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "order_vendor WHERE order_vendor_id = '" . (int)$order_vendor_id . "'");//used by report sale document

		return $query->row;
	}

	public function getOrderVendors($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_vendor WHERE order_id = '" . (int)$order_id . "' ORDER BY vendor_name ASC");
		// $query = $this->db->query("SELECT ov.*, v.*, vt.name AS vendor_type, vt.deposit, (SELECT SUM(t.amount) FROM " . DB_PREFIX . "transaction t WHERE t.order_id = ov.order_id AND t.label = 'vendor' AND t.label_id = ov.vendor_id) AS total FROM " . DB_PREFIX . "order_vendor ov LEFT JOIN " . DB_PREFIX . "vendor v ON (v.vendor_id = ov.vendor_id) LEFT JOIN " . DB_PREFIX . "vendor_type vt ON (vt.vendor_type_id = v.vendor_type_id) WHERE ov.order_id = '" . (int)$order_id . "' ORDER BY v.vendor_name ASC");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrdersEventDate($data = array()) {
		$sql = "SELECT event_date FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_year'])) {
			$sql .= " AND DATE_FORMAT(event_date,'%Y') = '" . $this->db->escape($data['filter_year']) . "'";
		}

		$sql .= " ORDER BY event_date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	// public function getOrderAdmission($order_id, $vendor_id) {
		// $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "order_admission WHERE order_id = '" . (int)$order_id . "' AND vendor_id = '" . (int)$vendor_id . "'");

		// return $query->row;
	// }

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_event_date'])) {
			$sql .= " AND DATE(event_date) = DATE('" . $this->db->escape($data['filter_event_date']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getOrdersCountByVendorId($vendor_id) {//Used by vendor
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_vendor` WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			// $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE DATE_FORMAT(event_date,'%Y') = '" . date('Y', strtotime($order_info['event_date'])) . "'");
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = $this->config->get('config_reference_start') + 1;
			}
			
			// $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . str_pad($invoice_no,4,0,STR_PAD_LEFT);
		}
	}

	public function editOrderPrintStatus($order_id, $printed_status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET printed = '" . (int)$printed_status . "' WHERE order_id = '" . (int)$order_id . "'");
	}

	public function setOrderVendorPrintStatus($order_vendor_id, $type, $printed_status) {
		switch ($type) {
			case 'agreement':
				$this->db->query("UPDATE `" . DB_PREFIX . "order_vendor` SET agreement_printed = '" . (int)$printed_status . "' WHERE order_vendor_id = '" . (int)$order_vendor_id . "'");
				break;
			
			case 'admission':
				$this->db->query("UPDATE `" . DB_PREFIX . "order_vendor` SET admission_printed = '" . (int)$printed_status . "' WHERE order_vendor_id = '" . (int)$order_vendor_id . "'");
				break;
			
			case 'purchase':
				$this->db->query("UPDATE `" . DB_PREFIX . "order_vendor` SET purchase_printed = '" . (int)$printed_status . "' WHERE order_vendor_id = '" . (int)$order_vendor_id . "'");
				break;
			
			default:
				break;
		}
	}

	public function getPaymentPhases($order_id) {
		$payment_phase_data = array();

		$this->load->model('localisation/order_status');
		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		$payment_phase_data['initial_payment'] = array(
			'order_status_id'	=> $this->config->get('config_initial_payment_status_id'),
			'title'				=> $this->model_localisation_order_status->getOrderStatus($this->config->get('config_initial_payment_status_id'))['name'],
			'amount'			=> $this->config->get('config_initial_payment_amount'),
			'limit_stamp'		=> strtotime('+' . $this->config->get('config_initial_payment_limit') . ' days', strtotime($order_info['date_added'])),
			'auto_expired'		=> true
		);

		$payment_phase_data['down_payment'] = array(
			'order_status_id'	=> $this->config->get('config_down_payment_status_id'),
			'title'				=> $this->model_localisation_order_status->getOrderStatus($this->config->get('config_down_payment_status_id'))['name'],
			'amount'			=> round($order_info['total'] * $this->config->get('config_down_payment_amount') / 100000, 0) * 1000 - $payment_phase_data['initial_payment']['amount'],
			'limit_stamp'		=> min(strtotime('+' . $this->config->get('config_down_payment_limit') . ' days', $payment_phase_data['initial_payment']['limit_stamp']), strtotime('-2 days', strtotime($order_info['event_date']))),
			'auto_expired'		=> false
		);

		$payment_phase_data['full_payment'] = array(
			'order_status_id'	=> $this->config->get('config_full_payment_status_id'),
			'title'				=> $this->model_localisation_order_status->getOrderStatus($this->config->get('config_full_payment_status_id'))['name'],
			'amount'			=> $order_info['total'] - $payment_phase_data['down_payment']['amount'] - $payment_phase_data['initial_payment']['amount'],
			'limit_stamp'		=> max(strtotime('-' . $this->config->get('config_full_payment_limit') . ' days', strtotime($order_info['event_date'])), strtotime('+1 day', $payment_phase_data['down_payment']['limit_stamp'])),
			'auto_expired'		=> false
		);
		
		$this->load->model('accounting/transaction');
		
		$summary_data = [
			'client_label'		=> 'customer',
			'category_label'	=> 'order',
			'transaction_label'	=> 'cash'
		];

		$transaction_total = $this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $summary_data);

		foreach ($payment_phase_data as $key => $payment_phase) {
			// Cek Pembayaran belum lunas
			if ($transaction_total < $payment_phase['amount']) {
				$paid_status = false;
			} else {
				$paid_status = true;
				$transaction_total -= $payment_phase['amount'];
			}

			$limit_status = '';

			if (!$paid_status && in_array($order_info['order_status_id'], $this->config->get('config_processing_status'))) {
				if (strtotime('today') > $payment_phase['limit_stamp']) {
					$limit_status = 'expired';
				} elseif (strtotime('+' . $this->config->get('config_notification_start') . ' days') > $payment_phase['limit_stamp']) {
					$limit_status = 'warning';
				}
			}
			
			$payment_phase_data[$key]['paid_status'] = $paid_status;
			$payment_phase_data[$key]['limit_status'] = $limit_status;
		}
		
		// for debug purpose
		// $payment_phase_data['initial_payment']['limit_status'] = 'expired';
		// $payment_phase_data['down_payment']['limit_status'] = 'warning';
		// $payment_phase_data['full_payment']['limit_status'] = '';
		
		return $payment_phase_data;
	}

	public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false) {
		$order_info = $this->getOrder($order_id);
		
		if ($order_info) {
			# If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				# Redeem coupon, vouchers and reward points
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);

					if (property_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
						# Confirm coupon, vouchers and reward points
						$fraud_status_id = $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
						
						# If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
				}

				# Add commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->addTransaction($order_info['affiliate_id'], $order_info['commission'], $order_id);
				}

				# Stock subtraction
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($order_product_query->rows as $order_product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}

			$transaction_initial_status = false;
			$transaction_complete_status = false;

			# If current order status is not processing/complete but new status is processing/complete
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$transaction_initial_status = true;
			}

			# If current order status is not complete but new status is complete then commence completion transaction
			if (!in_array($order_info['order_status_id'], $this->config->get('config_complete_status')) && in_array($order_status_id, $this->config->get('config_complete_status'))) {
				$transaction_complete_status = true;
			}

			if ($transaction_initial_status || $transaction_complete_status) {
				$this->load->model('localisation/order_status');
				$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_status_id);

				$this->load->model('accounting/transaction');
				$transaction_type_info = $this->model_accounting_transaction->getTransactionType($order_status_info['transaction_type_id']);

				if ($transaction_type_info) {
					$accounts = [];
					$transaction_account = [];

					$transaction_type_accounts = $this->model_accounting_transaction->getTransactionTypeAccounts($order_status_info['transaction_type_id']);
					foreach ($transaction_type_accounts as $value) {
						if (!isset($accounts[$value['account_debit_id']])) {
							$accounts[$value['account_debit_id']] = 0;
						}
						if (!isset($accounts[$value['account_credit_id']])) {
							$accounts[$value['account_credit_id']] = 0;
						}
					}

					# If current order status is not processing but new status is processing then commence initial transaction
					if ($transaction_initial_status) {
						$amount = $order_info['total'];

						$amount_data = [
							'initial'	=> 0,
							'discount'	=> 0,
							'charged'	=> 0
						];

						foreach ($order_total_query->rows as $order_total) {
							if ($order_total['code'] == 'sub_total') {
								$amount_data['initial'] = $order_total['value'];
							} elseif ($order_total['code'] == 'marketing_budget' || $order_total['code'] == 'coupon') {
								$amount_data['discount'] -= $order_total['value'];
							} elseif ($order_total['code'] == 'low_order_fee') {
								$amount_data['charged'] = $order_total['value'];
							}
						}

						foreach ($transaction_type_accounts as $value) {
							if ($value['account_label'] == 'initial') {
								$accounts[$value['account_debit_id']] += $amount_data['initial'];
								$accounts[$value['account_credit_id']] -= $amount_data['initial'];
							} elseif ($value['account_label'] == 'discount') {
								$accounts[$value['account_debit_id']] += $amount_data['discount'];
								$accounts[$value['account_credit_id']] -= $amount_data['discount'];
							} elseif ($value['account_label'] == 'charged') {
								$accounts[$value['account_debit_id']] += $amount_data['charged'];
								$accounts[$value['account_credit_id']] -= $amount_data['charged'];
							}
						}

						$reference_prefix = 'S' . date('ym');
						$reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix) + 1;

						$description = 'Initial Transaction of Order #' . $order_id . ': ' . $order_info['firstname'] . ' ' . $order_info['lastname'];
					}

					# If current order status is not complete but new status is complete then commence completion transaction
					if ($transaction_complete_status) {
						$filter_transaction_label = ['initial', 'cash'];

						foreach ($filter_transaction_label as $transaction_label) {
							$filter_summary_data = [
								'category_label'	=> $transaction_type_info['category_label'],
								'transaction_label'	=> $transaction_label,
								'group'				=> 'transaction_label'
							];

							$total[$transaction_label] = abs($this->model_accounting_transaction->getTransactionsTotalByOrderId($order_id, $filter_summary_data));
						}

						$amount = $total['initial'] - $total['cash'];

						switch ($transaction_type_info['transaction_label']) {
							case 'complete':
								foreach ($transaction_type_accounts as $value) {
									$accounts[$value['account_debit_id']] += $total['initial'];
									$accounts[$value['account_credit_id']] -= $total['initial'];
								}

								$description = 'Completion Transaction of Order #' . $order_id . ': ' . $order_info['firstname'] . ' ' . $order_info['lastname'];

								break;

							case 'canceled':
								$charged_amount = $total['cash'];
								$canceled_amount = $total['initial'];

								foreach ($transaction_type_accounts as $value) {
									if ($value['account_label'] == 'charged') {
										$accounts[$value['account_debit_id']] += $charged_amount;
										$accounts[$value['account_credit_id']] -= $charged_amount;
									} elseif ($value['account_label'] == 'canceled') {
										$accounts[$value['account_debit_id']] += $canceled_amount;
										$accounts[$value['account_credit_id']] -= $canceled_amount;
									}
								}

								$description = 'Cancelation Transaction of Order #' . $order_id . ': ' . $order_info['firstname'] . ' ' . $order_info['lastname'];

								break;

							default:
								break;
						}

						$reference_prefix = 'S' . date('ym');
						$reference_no = $this->model_accounting_transaction->getLastReferenceNo($reference_prefix) + 1;
					}

					foreach ($accounts as $account_id => $value) {
						if ($value > 0) {
							$transaction_account[] = [
								'account_id'	=> $account_id,
								'debit'			=> $value,
								'credit'		=> 0
							];
						} elseif ($value < 0) {
							$transaction_account[] = [
								'account_id'	=> $account_id,
								'debit'			=> 0,
								'credit'		=> -$value
							];
						}
					}

					$transaction_data = array(
						'transaction_type_id'	=> $transaction_type_info['transaction_type_id'],
						'order_id'				=> $order_id,
						'client_id'				=> $order_info['customer_id'],
						'reference_prefix'		=> $reference_prefix,
						'reference_no' 			=> $reference_no,
						'date' 					=> date('Y-m-d'),
						'payment_method'		=> '',
						'description' 			=> $description,
						'amount' 				=> $amount,
						'customer_name' 		=> 'system',
						'transaction_account' 	=> $transaction_account
					);

					$this->model_accounting_transaction->addTransaction($transaction_data);
				}
			}

			# Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			# Add the order history
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), user_id = '" . $this->user->getId() . "'");

			# If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				# Delete system transaction
				$this->db->query("DELETE t, ta FROM " . DB_PREFIX . "transaction t, " . DB_PREFIX . "transaction_account ta  WHERE client_label = 'customer' AND category_label = 'order' AND manual_select = 0 AND order_id = '" . (int)$order_id . "' AND client_id = '" . (int)$order_info['customer_id'] . "' AND ta.transaction_id = t.transaction_id");

				// Restock
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

				foreach ($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$this->load->model('account/order');

				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

				foreach ($order_total_query->rows as $order_total) {
					$this->load->model('total/' . $order_total['code']);

					if (property_exists($this->{'model_total_' . $order_total['code']}, 'unconfirm')) {
						$this->{'model_total_' . $order_total['code']}->unconfirm($order_id);
					}
				}

				// Remove commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id']) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->deleteTransaction($order_id);
				}
			}

			$this->cache->delete('product');
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify, u.username FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON (oh.order_status_id = os.order_status_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = oh.user_id) WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC, oh.order_history_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "' AND order_status_id > 0");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}

	public function getSlotUsed($slot_idx) {
		switch ($slot_idx) {
			case 'prf':
				$slot_used = array(
					'prp',
					'prm'
				);
				
				break;
				
			case 'cdf':
				$slot_used = array(
					'cdp',
					'cdm'
				);
				
				break;
				
			case 'krp':
				$slot_used = array(
					'prp',
					'cdp'
				);
				
				break;
				
			case 'krm':
				$slot_used = array(
					'prm',
					'cdm'
				);
				
				break;
				
			case 'krf':
				$slot_used = array(
					'prp',
					'prm',
					'cdp',
					'cdm'
				);
				
				break;
				
			case 'pof':
				$slot_used = array(
					'pop',
					'pom'
				);
				
				break;
				
			default:
				$slot_used = array(
					$slot_idx
				);
		}
		
		return $slot_used;
	}
}
