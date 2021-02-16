<?php
class ModelCommonReference extends Model
{
	public function addReferenceByType($data)
	{
		switch ($data['type']) {
			case 'customer-agreement':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_invoice_prefix'));

				$reference_id = $this->addReference($data);

				// $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			case 'customer-receipt':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_receipt_customer_prefix'));

				$reference_id = $this->addReference($data);

				// $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			case 'vendor-receipt':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_receipt_vendor_prefix'));

				$reference_id = $this->addReference($data);

				// $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			case 'vendor-agreement':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_agreement_vendor_prefix'));

				$reference_id = $this->addReference($data);

				// $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			case 'vendor-admission':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_admission_vendor_prefix'));

				$reference_id = $this->addReference($data);

				// $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			case 'vendor-purchase':
				$data['prefix'] = str_ireplace('{YEAR}', date('Y'), $this->config->get('config_purchase_vendor_prefix'));

				$reference_id = $this->addReference($data);

				$this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET reference_id = '" . (int)$reference_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

				break;

			default:
				// $label_data = array(
				// 	'B'	=> 'asset',
				// 	'L'	=> 'liability',
				// 	'Q'	=> 'equity',
				// 	'R'	=> 'revenue',
				// 	'E'	=> 'expense'
				// );

				// if (!isset($data['reference_prefix'])) {
				// 	$data['reference_prefix'] = array_search($account_to_info['component'], $label_data) . date('ym');
				// 	$data['reference_no'] = $this->getLastReferenceNo($data['reference_prefix']) + 1;
				// }

				$data['prefix'] = 'TMAP/' . date('ym') . '/';

				$reference_id = 0;

				break;
		}

		return $reference_id;
	}

	public function addReference($data)
	{
		if (!isset($data['date'])) {
			$data['date'] = date('Y-m-d');
		}

		$query = $this->db->query("SELECT MAX(no) AS no FROM `" . DB_PREFIX . "reference` WHERE prefix = '" . $this->db->escape($data['prefix']) . "'");

		if ($query->row['no']) {
			$no = $query->row['no'] + 1;
		} else {
			$no = $this->config->get('config_reference_start') + 1;
		}

		$sql = "INSERT INTO " . DB_PREFIX . "reference SET order_id = '" . (int)$data['order_id'] . "', date = '" . $this->db->escape($data['date']) . "', type = '" . $this->db->escape($data['type']) . "', prefix = '" . $this->db->escape($data['prefix']) . "', no = '" . (int)$no . "', printed = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";

		$this->db->query($sql);

		$reference_id = $this->db->getLastId();

		return $reference_id;
	}

	public function editReferencePrintStatus($reference_id, $printed_status)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "reference` SET printed = '" . (int)$printed_status . "' WHERE reference_id = '" . (int)$reference_id . "'");
	}

	public function getReference($reference_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(prefix, LPAD(no, 4, '0')) AS reference FROM " . DB_PREFIX . "reference WHERE reference_id = '" . (int)$reference_id . "'");

		return $query->row;
	}

	public function getReferencesByOrderVendorId($order_vendor_id)
	{
		$reference_data = [];

		$query = $this->db->query("SELECT *, CONCAT(prefix, LPAD(no, 4, '0')) AS reference FROM " . DB_PREFIX . "reference WHERE order_vendor_id = '" . (int)$order_vendor_id . "' ORDER BY date ASC");

		foreach ($query->rows as $value) {
			$reference_data[$value['type']] = $value;
		}

		return $reference_data;
	}

	// public function getReferenceReference($reference_id)//blm digunakan
	// {
	//     $query = $this->db->query("SELECT DISTINCT CONCAT(prefix, LPAD(no, 4, '0')) AS reference FROM " . DB_PREFIX . "reference WHERE reference_id = '" . (int)$reference_id . "'");

	//     return $query->row['reference'];
	// }

	///////////////////
}
