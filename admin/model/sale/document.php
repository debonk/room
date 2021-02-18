<?php
class ModelSaleDocument extends Model
{
    public function addOrderDocumentByType($data)
    {
        switch ($data['client_type'] . '-' . $data['document_type']) {
            case 'customer-agreement':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_invoice_prefix'));

                $order_document_id = $this->addOrderDocument($data);

                // $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET order_document_id = '" . (int)$order_document_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

                break;

            case 'customer-receipt':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_receipt_customer_prefix'));
 
                $order_document_id = $this->addOrderDocument($data);

                // $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET order_document_id = '" . (int)$order_document_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

               break;

            case 'vendor-receipt':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_receipt_vendor_prefix'));

                $order_document_id = $this->addOrderDocument($data);

                // $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET order_document_id = '" . (int)$order_document_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

                break;

            case 'vendor-agreement':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_agreement_vendor_prefix'));

                $order_document_id = $this->addOrderDocument($data);

                break;

            case 'vendor-admission':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_admission_vendor_prefix'));
 
                $order_document_id = $this->addOrderDocument($data);

	            break;

            case 'vendor-purchase':
                $data['reference_prefix'] = str_ireplace('{YEAR}',date('Y'),$this->config->get('config_purchase_vendor_prefix'));

                $order_document_id = $this->addOrderDocument($data);

                $this->db->query("UPDATE `" . DB_PREFIX . "order_purchase` SET order_document_id = '" . (int)$order_document_id . "' WHERE order_purchase_id = '" . (int)$data['update_idx'] . "'");

                break;

            default:
                $data['reference_prefix'] = 'TMAP/' . date('ym') . '/';

                $order_document_id = 0;

                break;
        }

        // return $this->getOrderDocumentReference($order_document_id);
        return $order_document_id;
    }

    public function addOrderDocument($data)
    {
        if (!isset($data['date'])) {
            $data['date'] = date('Y-m-d');
        }

		$query = $this->db->query("SELECT MAX(reference_no) AS reference_no FROM `" . DB_PREFIX . "order_document` WHERE reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "'");

		if ($query->row['reference_no']) {
			$reference_no = $query->row['reference_no'] + 1;
		} else {
			$reference_no = $this->config->get('config_reference_start') + 1;
		}

        $sql = "INSERT INTO " . DB_PREFIX . "order_document SET order_id = '" . (int)$data['order_id'] . "', client_type = '" . $this->db->escape($data['client_type']) . "', document_type = '" . $this->db->escape($data['document_type']) . "', client_id = '" . (int)$data['client_id'] . "', date = '" . $this->db->escape($data['date']) . "', reference_prefix = '" . $this->db->escape($data['reference_prefix']) . "', reference_no = '" . (int)$reference_no . "', printed = 0, date_added = NOW(), user_id = '" . $this->user->getId() . "'";
        
        $this->db->query($sql);

        $order_document_id = $this->db->getLastId();
        
        return $order_document_id;
    }

    public function editDocumentPrintStatus($order_document_id, $printed_status)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "order_document` SET printed = '" . (int)$printed_status . "' WHERE order_document_id = '" . (int)$order_document_id . "'");
    }

    public function getOrderDocument($order_document_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, CONCAT(reference_prefix, LPAD(reference_no, 4, '0')) AS reference FROM " . DB_PREFIX . "order_document WHERE order_document_id = '" . (int)$order_document_id . "'");

        return $query->row;
    }

	public function getOrderDocuments($data = array()) {
		$sql = "SELECT *, CONCAT(reference_prefix, LPAD(reference_no, 4, '0')) AS reference FROM " . DB_PREFIX . "order_document";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = "order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_client_type'])) {
			$implode[] = "client_type = '" . $this->db->escape($data['filter_client_type']) . "'";

			if (!empty($data['filter_client_id'])) {
				$implode[] = "client_id = '" . (int)$data['filter_client_id'] . "'";
			}
		}		

		if (!empty($data['filter_document_type'])) {
			$implode[] = "document_type = '" . $this->db->escape($data['filter_document_type']) . "'";
		}		

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_reference'])) {
			$implode[] = "CONCAT(reference_prefix, LPAD(reference_no, 4, '0')) LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'order_id',
			'document_type',
			'document_type, client_id',
			'date',
			'reference'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date";
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
	
	// public function getOrderDocumentsByOrderClientId($order_vendor_id)
    // {
    //     $order_document_data = [];

    //     $query = $this->db->query("SELECT *, CONCAT(reference_prefix, LPAD(reference_no, 4, '0')) AS reference FROM " . DB_PREFIX . "order_document WHERE order_vendor_id = '" . (int)$order_vendor_id . "' ORDER BY date ASC");

    //     foreach ($query->rows as $value) {
    //         $order_document_data[$value['document_type']] = $value;
    //     }

    //     return $order_document_data;
    // }

    // public function getOrderDocumentReference($order_document_id)//blm digunakan
    // {
    //     $query = $this->db->query("SELECT DISTINCT CONCAT(reference_prefix, LPAD(reference_no, 4, '0')) AS reference FROM " . DB_PREFIX . "order_document WHERE order_document_id = '" . (int)$order_document_id . "'");

    //     return $query->row['reference'];
    // }

    ///////////////////
}
