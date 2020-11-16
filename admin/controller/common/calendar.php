<?php
class ControllerCommonCalendar extends Controller {
	public function index() {
		$this->load->language('common/calendar');
		
		// $this->load->model('common/payroll');
		
		$language_items = array(
			'text_months_mmm',
			'text_months_mmmm',
			'text_days_d',
			'text_days_dd'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$highlight_date = array(
			'2020-04-10',
			'2020-04-01',
			'2020-04-25'
		);
		
		$setting = array(
			'month_format'	=> 'mmmm',
			'day_format'	=> 'd',
			'date_data'		=> 'today',
			'highlight'		=> $highlight_date
		);

		foreach ($setting as $key => $value) {
			if (isset($this->request->post[$key])) {
				$setting[$key] = $this->request->post[$key];
			}
		}

		$data['months'] = explode(', ', $data['text_months_' . $setting['month_format']]);
		$data['weekdays'] = explode(', ', $data['text_days_' . $setting['day_format']]);
		
		// $date_data = date('Y-m-d', strtotime($setting['date_data']));
		$date_data = date('Y-m', strtotime($setting['date_data'])) . '-01';

		$data['calendars'] = array();

		$date_data = getdate(strtotime($date_data));
		
		$data['title'] = $data['months'][$date_data['mon'] - 1] . ' ' . $date_data['year'];
		
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $date_data['mon'], $date_data['year']);
		$total_days = ceil(($date_data['wday'] + $days_in_month) / 7) * 7;

// print_r(($date_data));
// print_r( '<br>');
// var_dump(empty($data['text_days_' . $setting['day_format']]));
// print_r( '<br>');
		
		// /* array "blank" days until the first of the current week */
		$counter = -$date_data['wday'];
		
		// $slot_data = array(
			// 'prp'	=> 1,
			// 'prf'	=> 0,
			// 'cdp'	=> 1,
			// 'cdf'	=> 0,
			// 'krp'	=> 0,
			// 'prm'	=> 1,
			// 'cdm'	=> 1,
			// 'krm'	=> 0,
			// 'krf'	=> 0,
			// 'pop'	=> 1,
			// 'pom'	=> 1,
			// 'pof'	=> 0
		// );
		
		for($i = 0; $i < $total_days; $i++) {
			$date = date('Y-m-d', strtotime('+' . $counter . ' day', $date_data['0']));
			
			$class = 'default';
				
			if ($counter >= 0 && $counter < $days_in_month) {
				
				if (in_array($date, $setting['highlight'])) {
					$class = 'primary';
				}

				$data['calendars'][$date] = array(
					'date'		=> $date,
					'text'		=> $counter + 1,
					'class'		=> $class,
					// 'slot_data'	=> $slot_data,
					'slot_data'	=> array(),
					'url'		=> $this->url->link('sale/order/add', 'token=' . $this->session->data['token'] . '&event_date=' . $date, true),
				);
					
			} else {
				$data['calendars'][$date] = array(
					'date'		=> '',
					'text'		=> '',
					'class'	=> '',
					'slot_data'	=> array(),
					'url'		=> ''
				);
			}
			
			$counter++;
		}
		
		$this->response->setOutput($this->load->view('common/calendar', $data));
	}
}
