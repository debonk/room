<?php
class ModelAccountingAccount extends Model {
	public function getAccount($account_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "account WHERE account_id = '" . (int)$account_id . "'");
		
		$account_data = $query->row;

/* 		$components = array(
			'asset' 		=> ['current_asset', 'fixed_asset', 'non_current_asset', 'prepayment'],
			'equity' 		=> ['equity'],
			'expense' 		=> ['depreciation', 'direct_cost', 'expense', 'overhead'],
			'liabilities' 	=> ['current_liability', 'liability', 'non_current_liability'],
			'revenue' 		=> ['sale', 'revenue', 'other_income']
		);
		
		foreach ($components as $component => $value) {
			if (in_array($account_data['type'], $value)) {
				$account_data['component'] = $component;
				
				break;
			} else {
				$account_data['component'] = '';
			}
		}
 */		
		return $account_data;
	}
}
