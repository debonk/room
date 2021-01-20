<?php
class ControllerCommonMenu extends Controller
{
	public function index()
	{
		$this->load->language('common/menu');

		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

		$data['menu_groups'] = [];
		$data['child_groups'] = [];
		$data['menu_title'] = [];

		$fa_icon_class = ['fa-tags', 'fa-user', 'fa-share-alt', 'fa-shopping-cart', 'fa-credit-card', 'fa-book', 'fa-bar-chart-o', 'fa-puzzle-piece', 'fa-rocket', 'fa-television', 'fa-globe', 'fa-cog', '', ''];

		$menu_groups = [
			'catalog'		=> [
				'catalog/category',
				'catalog/product',
				'catalog/recurring',
				'catalog/filter',
				'attribute'		=> ['catalog/attribute', 'catalog/attribute_group'],
				'catalog/option',
				'catalog/manufacturer',
				'vendor'		=> ['catalog/vendor', 'catalog/vendor_type'],
				'catalog/download',
				'catalog/review',
				'catalog/information'
			],
			'customer'		=> ['customer/customer', 'customer/customer_group', 'customer/custom_field'],
			'marketing'		=> ['marketing/marketing', 'marketing/coupon', 'marketing/affiliate', 'marketing/contact'],
			'sale'			=> [
				'sale/order',
				'sale/recurring',
				'sale/return',
				'voucher'		=> ['sale/voucher', 'sale/voucher_theme'],
				'paypal'		=> ['payment/pp_express']
			],
			'purchase'		=> ['purchase/purchase', 'purchase/supplier'],
			'accounting'	=> ['accounting/account', 'accounting/balance', 'accounting/expense', 'accounting/transaction'],
			'reports'		=> [
				'sale'			=> ['report/sale_order', 'report/sale_document', 'report/sale_tax', 'report/sale_coupon'],
				'product'		=> ['report/product_viewed', 'report/product_purchased'],
				'customer'		=> ['report/customer_online', 'report/customer_activity', 'report/customer_order', 'report/customer_reward', 'report/customer_credit'],
				'marketing'		=> ['report/marketing', 'report/affiliate', 'report/affiliate_activity'],
				'accounting'	=> ['report/transaction_balance'],
			],
			'extension'		=> ['extension/installer', 'extension/modification', 'extension/theme', 'extension/analytics', 'extension/captcha', 'extension/feed', 'extension/fraud', 'extension/module', 'extension/payment', 'extension/shipping', 'extension/total'],
			'themecontrol'	=> ['module/themecontrol', 'module/pavmegamenu', 'module/pavblog', 'module/pavnewsletter'],
			'design'		=> ['design/layout', 'design/banner'],
			'localisation'	=> [
				'localisation/location',
				'localisation/language',
				'localisation/currency',
				'localisation/slot',
				'localisation/ceremony',
				'localisation/stock_status',
				'localisation/order_status',
				'return'		=> ['localisation/return_status', 'localisation/return_action', 'localisation/return_reason'],
				'localisation/country',
				'localisation/zone',
				'localisation/geo_zone',
				'tax'		=> ['localisation/tax_class', 'localisation/tax_rate'],
				'localisation/unit_class',
				'localisation/length_class',
				'localisation/weight_class'
			],
			'system'		=> [
				'setting/store',
				'user'			=> ['user/user', 'user/user_permission', 'user/api'],
				'tools'			=> ['tool/upload', 'tool/backup', 'tool/error_log']
			]
		];

		$permissions = $this->user->getPermission();

		foreach ($permissions as $authority => $permission) {
			foreach ($permission as $value) {
				$permission_data[$value] = [
					'url'	=> $this->url->link($value, 'token=' . $this->session->data['token'], 'true'),
					'text'	=> $this->language->get('text_' . explode('/', $value)[1]),
					'class'	=> $authority
				];
			}
		}

		$menu_titles = array_keys($menu_groups);
		foreach ($menu_titles as $idx => $title) {
			$data['menu_titles'][$title] = [
				'text'	=> $this->language->get('text_' . $title),
				'icon'	=> $fa_icon_class[$idx]
			];
		}

		foreach ($menu_groups as $menu_group => $menu_items) {
			foreach ($menu_items as $child_group => $menu_item) {
				if (is_array($menu_item)) {
					foreach ($menu_item as $child_item) {
						if (array_key_exists($child_item, $permission_data)) {
							$data['child_groups'][$child_group][] = $permission_data[$child_item];
						}
					}

					if (!empty($data['child_groups'][$child_group])) {
						$data['menu_groups'][$menu_group][$child_group] = [
							'text'	=> $this->language->get('text_' . $child_group)
						];
					}
				} else {
					if (array_key_exists($menu_item, $permission_data)) {
						$data['menu_groups'][$menu_group][$menu_item] = $permission_data[$menu_item];
					}
				}
			}
		}

		if (isset($data['menu_groups']['sale']['sale/order'])) {
			$data['menu_groups']['sale']['sale/order']['url'] = $this->url->link('sale/order/yearView', 'token=' . $this->session->data['token'], true);
		}
		if (isset($data['child_groups']['paypal'][0])) {
			$data['child_groups']['paypal'][0]['url'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], true);
		}

		return $this->load->view('common/menu', $data);
	}
}
