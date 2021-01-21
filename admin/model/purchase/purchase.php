<?php
class ModelPurchasePurchase extends Model {
	public function addPurchase($data) {
        if (!isset($data['invoice_prefix'])) {
			$data['invoice_prefix'] = 'PO' . date('ym');
			$data['invoice_no'] = $this->getInvoiceNoMax($data['invoice_prefix']) + 1;
		}

		$data_items = array(
			'telephone',
			'contact_person',
			'order_id',
			'supplier_id',
			'comment',
			'completed'
		);
		foreach ($data_items as $data_item) {
			if (!isset($data[$data_item])) {
				$data[$data_item] = '';
			}
		}

		$data['adjustment'] = $this->getNumber($data['adjustment']);
		$data['total'] = $this->getNumber($data['total']);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', invoice_no = '" . $this->db->escape($data['invoice_no']) . "', printed = '0', supplier_id = '" . (int)$data['supplier_id'] . "', supplier_name = '" . $this->db->escape($data['supplier_name']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', order_id = '" . (int)$data['order_id'] . "', adjustment = '" . (float)$data['adjustment'] . "', total = '" . (float)$data['total'] . "', comment = '" . $this->db->escape($data['comment']) . "', completed = '" . (int)$data['completed'] . "', date_added = NOW(), date_modified = NOW(), user_id = '" . $this->user->getId() . "', user_modified_id = '" . $this->user->getId() . "'");

		$purchase_id = $this->db->getLastId();

		if (isset($data['product'])) {
			$product_total = 0;
			
			foreach ($data['product'] as $product) {
				if (!isset($product['tax'])) {
					$product['tax'] = 0;
				}

				if (isset($product['purchase_price'])) {
					$product['price'] = $product['purchase_price'];
				}

				$product['quantity'] =  $this->getNumber($product['quantity']);
				$product['price'] =  $this->getNumber($product['price']);
				$product['total'] =  $product['quantity'] * $product['price'];
				$product['tax'] =  $this->getNumber($product['tax']);

				if ($product['product_id']) {
					$this->load->model('catalog/product');
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					$product['name'] = $product_info['name'];
					$product['unit_class'] = $product_info['unit_class'];
				} else {
					$product['product_id'] = 0;
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_product SET purchase_id = '" . (int)$purchase_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', unit_class = '" . $this->db->escape($product['unit_class']) . "'");

				$product_total += $product['total'];
			}

			$this->db->query("UPDATE " . DB_PREFIX . "purchase SET total = '" . (float)($product_total + $data['adjustment']) . "' WHERE purchase_id = '" . (int)$purchase_id . "'");
		}

		return $purchase_id;
	}

	public function editPurchase($purchase_id, $data) {
		$data_items = array(
			'telephone',
			'contact_person',
			'order_id',
			'supplier_id',
			'comment',
			'completed'
		);
		foreach ($data_items as $data_item) {
			if (!isset($data[$data_item])) {
				$data[$data_item] = '';
			}
		}

		$data['adjustment'] = $this->getNumber($data['adjustment']);
		$data['total'] = $this->getNumber($data['total']);
		
		if (isset($data['order_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "purchase SET contact_person = '" . $this->db->escape($data['contact_person']) . "', adjustment = '" . (float)$data['adjustment'] . "', total = '" . (float)$data['total'] . "', comment = '" . $this->db->escape($data['comment']) . "', completed = '" . (int)$data['completed'] . "', date_modified = NOW(), user_modified_id = '" . $this->user->getId() . "'WHERE purchase_id = '" . (int)$purchase_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "purchase SET supplier_id = '" . (int)$data['supplier_id'] . "', supplier_name = '" . $this->db->escape($data['supplier_name']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', contact_person = '" . $this->db->escape($data['contact_person']) . "', adjustment = '" . (float)$data['adjustment'] . "', total = '" . (float)$data['total'] . "', comment = '" . $this->db->escape($data['comment']) . "', completed = '" . (int)$data['completed'] . "', date_modified = NOW(), user_modified_id = '" . $this->user->getId() . "'WHERE purchase_id = '" . (int)$purchase_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_product WHERE purchase_id = '" . (int)$purchase_id . "'");

		if (isset($data['product'])) {
			foreach ($data['product'] as $product) {
				if (!isset($product['tax'])) {
					$product['tax'] = 0;
				}

				$product['quantity'] =  $this->getNumber($product['quantity']);
				$product['price'] =  $this->getNumber($product['price']);
				$product['total'] =  $product['quantity'] * $product['price'];
				// $product['total'] =  $this->getNumber($product['total']);
				$product['tax'] =  $this->getNumber($product['tax']);

				if ($product['product_id']) {
					$this->load->model('catalog/product');
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					$product['name'] = $product_info['name'];
					$product['unit_class'] = $product_info['unit_class'];
				} else {
					$product['product_id'] = 0;
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_product SET purchase_id = '" . (int)$purchase_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', unit_class = '" . $this->db->escape($product['unit_class']) . "'");
			}
		}
	}

	public function deletePurchase($purchase_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase WHERE purchase_id = '" . (int)$purchase_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_product WHERE purchase_id = '" . (int)$purchase_id . "'");
	}

	public function getPurchase($purchase_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "purchase WHERE purchase_id = '" . (int)$purchase_id . "'");

		return $query->row;
	}

	public function getPurchases($data = array()) {
		$sql = "SELECT p.*, CONCAT(p.invoice_prefix, LPAD(p.invoice_no, 4, '0')) AS invoice, o.firstname, o.lastname, u.username FROM " . DB_PREFIX . "purchase p LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = p.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.user_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(p.date_added) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(p.date_added) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['supplier_name'])) {
			$implode[] = "p.supplier_name LIKE '%" . $this->db->escape($data['filter']['supplier_name']) . "%'";
		}

		if (!empty($data['filter']['invoice'])) {
			$implode[] = "CONCAT(p.invoice_prefix, LPAD(p.invoice_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['invoice']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "p.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'p.date_added',
			'p.supplier_name',
			'invoice',
			'p.telephone',
			'p.total',
			'u.username'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.date_added";
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

	public function getPurchasesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase p LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = p.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.user_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(p.date_added) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(p.date_added) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['supplier_name'])) {
			$implode[] = "p.supplier_name LIKE '%" . $this->db->escape($data['filter']['supplier_name']) . "%'";
		}

		if (!empty($data['filter']['invoice'])) {
			$implode[] = "CONCAT(p.invoice_prefix, LPAD(p.invoice_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['invoice']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "p.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPurchasesTotal($data = array()) {
		$sql = "SELECT SUM(p.total) AS total FROM " . DB_PREFIX . "purchase p LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = p.order_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.user_id)";

		$implode = array();

		if (!empty($data['filter']['date_start'])) {
			$implode[] = "DATE(p.date_added) >= '" . $this->db->escape($data['filter']['date_start']) . "'";
		}

		if (!empty($data['filter']['date_end'])) {
			$implode[] = "DATE(p.date_added) <= '" . $this->db->escape($data['filter']['date_end']) . "'";
		}

		if (!empty($data['filter']['supplier_name'])) {
			$implode[] = "p.supplier_name LIKE '%" . $this->db->escape($data['filter']['supplier_name']) . "%'";
		}

		if (!empty($data['filter']['invoice'])) {
			$implode[] = "CONCAT(p.invoice_prefix, LPAD(p.invoice_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter']['invoice']) . "%'";
		}

		if (!empty($data['filter']['order_id'])) {
			$implode[] = "p.order_id = '" . (int)$data['filter']['order_id'] . "'";
		}

		if (!empty($data['filter']['username'])) {
			$implode[] = "u.username = '" . $this->db->escape($data['filter']['username']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPurchaseProducts($purchase_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_product WHERE purchase_id = '" . (int)$purchase_id . "' ORDER BY purchase_product_id ASC");

		return $query->rows;
	}

	public function getPurchaseBySupplierOrder($supplier_id, $order_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "purchase WHERE supplier_id = '" . (int)$supplier_id . "' AND order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getInvoiceNoMax($invoice_prefix) {
		$sql = "SELECT MAX(invoice_no) AS total FROM `" . DB_PREFIX . "purchase` WHERE invoice_prefix = '" . $this->db->escape($invoice_prefix) . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	protected function getNumber($currency_string) {
		return preg_replace('/(?!-)[^0-9.]/', '', $currency_string);
	}

// End

	public function getTotalPurchasesByCompleteStatus() {
		$implode = array();

		$purchase_statuses = $this->config->get('config_complete_status');

		foreach ($purchase_statuses as $purchase_status_id) {
			$implode[] = "purchase_status_id = '" . (int)$purchase_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "purchase` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function editPurchasePrintStatus($purchase_id, $printed_status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "purchase` SET printed = '" . (int)$printed_status . "' WHERE purchase_id = '" . (int)$purchase_id . "'");
	}

	public function setPurchaseVendorPrintStatus($purchase_vendor_id, $type, $printed_status) {
		if ($this->db->escape($type) == 'agreement') {
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_vendor` SET agreement_printed = '" . (int)$printed_status . "' WHERE purchase_vendor_id = '" . (int)$purchase_vendor_id . "'");
		} elseif ($this->db->escape($type) == 'admission') {
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_vendor` SET admission_printed = '" . (int)$printed_status . "' WHERE purchase_vendor_id = '" . (int)$purchase_vendor_id . "'");
		}
	}

	public function setPurchaseVendorPrinted($purchase_vendor_id, $type) {
		if ($this->db->escape($type) == 'agreement') {
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_vendor` SET agreement_printed = '1' WHERE purchase_vendor_id = '" . (int)$purchase_vendor_id . "'");
		} elseif ($this->db->escape($type) == 'admission') {
			$this->db->query("UPDATE `" . DB_PREFIX . "purchase_vendor` SET admission_printed = '1' WHERE purchase_vendor_id = '" . (int)$purchase_vendor_id . "'");
		}
	}
}
