<?php
class ModelSalePurchase extends Model
{
    public function addOrderPurchase($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_purchase SET order_document_id = 0, order_vendor_id = '" . (int)$data['order_vendor_id'] . "', order_id = '" . (int)$data['order_id'] . "', vendor_id = '" . (int)$data['vendor_id'] . "', adjustment = 0, comment = '', vendor_reference = '', completed = 0, date_added = NOW(), date_completed = NULL, user_id = '" . $this->user->getId() . "', user_completed_id = 0");

        $order_purchase_id = $this->db->getLastId();

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_purchase_product WHERE order_purchase_id = '" . (int)$order_purchase_id . "'");

        if (isset($data['product'])) {
            foreach ($data['product'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_purchase_product SET order_purchase_id = '" . (int)$order_purchase_id . "', order_product_id = '" . (int)$product['order_product_id'] . "', product_id = '" . (int)$product['product_id'] . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "'");
            }
        }

		// return $order_purchase_id;
    }

    public function editOrderPurchase($order_purchase_id, $data)
    {
        if (isset($data['completed']) && !is_null($data['completed'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "order_purchase SET adjustment = '" . (float)$data['adjustment'] . "', comment = '" . $this->db->escape($data['comment']) . "', vendor_reference = '" . $this->db->escape($data['vendor_reference']) . "', completed = '" . (int)$data['completed'] . "', date_completed = NOW(), user_completed_id = '" . $this->user->getId() . "' WHERE order_purchase_id = '" . (int)$order_purchase_id . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "order_purchase SET comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW(), user_id = '" . $this->user->getId() . "' WHERE order_purchase_id = '" . (int)$order_purchase_id . "'");
        }
    }

    public function deleteOrderPurchases($order_id, $completed = null)
    {
        $order_purchases = $this->getOrderPurchases($order_id);

        foreach ($order_purchases as $order_purchase) {
            if (isset($completed)) {
                if ($order_purchase['completed'] == $completed) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "order_purchase_product WHERE order_purchase_id = '" . (int)$order_purchase['order_purchase_id'] . "'");
                    $this->db->query("DELETE FROM " . DB_PREFIX . "order_purchase WHERE order_id = '" . (int)$order_id . "' AND completed = '" . (int)$completed . "'");
                }
            } else {
                $this->db->query("DELETE FROM " . DB_PREFIX . "order_purchase_product WHERE order_purchase_id = '" . (int)$order_purchase['order_purchase_id'] . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "order_purchase WHERE order_id = '" . (int)$order_id . "'");
            }
        }
    }

    public function getOrderPurchase($order_id, $vendor_id)
    {
        // $query = $this->db->query("SELECT DISTINCT op.*, od.*, CONCAT(od.reference_prefix, LPAD(od.reference_no, 4, '0')) AS reference, SUM(opp.total) AS total, ov.* FROM " . DB_PREFIX . "order_purchase op LEFT JOIN " . DB_PREFIX . "order_document od ON (od.order_document_id = op.order_document_id) LEFT JOIN " . DB_PREFIX . "order_purchase_product opp ON (opp.order_purchase_id = op.order_purchase_id) LEFT JOIN " . DB_PREFIX . "order_vendor ov ON (ov.order_vendor_id = op.order_vendor_id) WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_id = '" . (int)$vendor_id . "'");
        $query = $this->db->query("SELECT DISTINCT op.*, od.*, CONCAT(od.reference_prefix, LPAD(od.reference_no, 4, '0')) AS reference, ov.* FROM " . DB_PREFIX . "order_purchase op LEFT JOIN " . DB_PREFIX . "order_document od ON (od.order_document_id = op.order_document_id) LEFT JOIN " . DB_PREFIX . "order_vendor ov ON (ov.order_vendor_id = op.order_vendor_id) WHERE op.order_id = '" . (int)$order_id . "' AND op.vendor_id = '" . (int)$vendor_id . "'");

        return $query->row;
    }

    public function getOrderPurchases($order_id)
    {
        // $query = $this->db->query("SELECT op.*, od.*, CONCAT(od.reference_prefix, LPAD(od.reference_no, 4, '0')) AS reference, SUM(opp.total) AS total, ov.* FROM " . DB_PREFIX . "order_purchase op LEFT JOIN " . DB_PREFIX . "order_document od ON (od.order_document_id = op.order_document_id) LEFT JOIN " . DB_PREFIX . "order_purchase_product opp ON (opp.order_purchase_id = op.order_purchase_id) LEFT JOIN " . DB_PREFIX . "order_vendor ov ON (ov.order_vendor_id = op.order_vendor_id) WHERE op.order_id = '" . (int)$order_id . "' ORDER BY od.date ASC");
        $query = $this->db->query("SELECT op.*, od.*, CONCAT(od.reference_prefix, LPAD(od.reference_no, 4, '0')) AS reference, ov.* FROM " . DB_PREFIX . "order_purchase op LEFT JOIN " . DB_PREFIX . "order_document od ON (od.order_document_id = op.order_document_id) LEFT JOIN " . DB_PREFIX . "order_vendor ov ON (ov.order_vendor_id = op.order_vendor_id) WHERE op.order_id = '" . (int)$order_id . "' ORDER BY op.order_purchase_id ASC");

        return $query->rows;
    }

    public function getOrderPurchaseProduct($order_purchase_id, $product_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "order_purchase_product WHERE order_purchase_id = '" . (int)$order_purchase_id . "' AND product_id = '" . (int)$product_id . "'");

        return $query->row;
    }

    public function getOrderPurchaseProducts($order_purchase_id)
    {
        $query = $this->db->query("SELECT opp.*, op.name, op.model, op.quantity AS total_quantity, op.unit_class, op.primary_type, op.category FROM " . DB_PREFIX . "order_purchase_product opp LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_product_id = opp.order_product_id) WHERE order_purchase_id = '" . (int)$order_purchase_id . "' ORDER BY opp.order_purchase_product_id ASC");

        return $query->rows;
    }

    //
    public function getPurchaseByOrderVendorId($order_vendor_id)
    {
        $order_purchase_data = [];

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_purchase WHERE order_vendor_id = '" . (int)$order_vendor_id . "'");

        foreach ($query->rows as $order_purchase) {
            $purchase_products = $this->getOrderPurchaseProducts($order_purchase['order_purchase_id']);

            $order_purchase_data[] = array_merge($order_purchase, ['purchase_products' => $purchase_products]);
        }

        return $order_purchase_data;
    }
}
