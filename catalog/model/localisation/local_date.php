<?php
class ModelLocalisationLocalDate extends Model {
	public function getInFormatDate($date_str = '') {
		if ($date_str) {
			$date_data = strtotime($this->db->escape($date_str));
		} else {
			$date_data = strtotime('now');
		}
		
		$list_day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
		$day = $list_day[idate('w', $date_data)];
		
		$list_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
		$month = $list_month[idate('m', $date_data) - 1];
		
		$date = idate('d', $date_data);
		$year = idate('Y', $date_data);
		
		$date_format = [
			'day'		=> $day,
			'date'		=> $date,
			'month'		=> $month,
			'year'		=> $year,
			'long_date'	=> $date . ' ' . $month . ' ' . $year
		];
		
		return $date_format;
	}
}