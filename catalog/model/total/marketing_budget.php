<?php
class ModelTotalMarketingBudget extends Model {
	public function getTotal($total) {
		if (isset($this->session->data['marketing_budget'])) {
			$this->load->language('total/marketing_budget');

			$max_budget = $this->config->get('marketing_budget_max_budget');

			if (!$max_budget || $this->session->data['marketing_budget'] <= $max_budget) {
				$total['totals'][] = array(
					'code'       => 'marketing_budget',
					'title'      => $this->language->get('text_marketing_budget'),
					'value'      => -$this->session->data['marketing_budget'],
					'sort_order' => $this->config->get('marketing_budget_sort_order')
				);

				$total['total'] -= $this->session->data['marketing_budget'];
			}
		}
	}
}