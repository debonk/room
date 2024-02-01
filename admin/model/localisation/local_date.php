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
	
	public function getInWord($value) {
		$value = abs($value);
		
		$word = '';
		$loop = true;

		while ($loop) {
			switch (true) {
				case ($value < 2000):
					$word .= ' '. $this->getInWordPart($value);

					$loop = false;
					break;
				
				case ($value < 1000000):
					$word .= ' '. $this->getInWordPart($value / 1000). ' ribu';
					// $value %= 1000;
					$value = fmod($value, 1000);

					$loop = true;
					break;
				
				case ($value < 1000000000):
					$word .= ' '. $this->getInWordPart($value / 1000000). ' juta';
					// $value %= 1000000;
					$value = fmod($value, 1000000);

					$loop = true;
					break;
				
				case ($value < 1000000000000):
					$word .= ' '. $this->getInWordPart($value / 1000000000). ' milyar';
					$value = fmod($value, 1000000000);

					$loop = true;
					break;
				
				case ($value < 1000000000000000):
					$word .= ' '. $this->getInWordPart($value / 1000000000000). ' trilyun';
					$value = fmod($value, 1000000000000);

					$loop = true;
					break;
				
				default:
					$loop = false;
			}
		}
		
		return trim($word);
	}
	
	public function getInWordPart($value) {
		$huruf = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

		$word_part = '';
		$loop = true;
		
		while ($loop) {
			switch (true) {
				case ($value < 12):
					$word_part .= ' '. $huruf[(int)$value];
					
					$loop = false;
					break;
				
				case ($value < 20):
					$value -= 10;
					$word_part .= ' '. $huruf[(int)$value]. ' belas';
					
					$loop = false;
					break;
				
				case ($value < 100):
					$word_part .= ' '. $huruf[(int)($value / 10)]. ' puluh';
					// $value %= 10;
					$value = fmod($value, 10);

					$loop = true;
					break;
				
				case ($value < 200):
					$word_part .= ' seratus';
					$value -= 100;

					$loop = true;
					break;
				
				case ($value < 1000):
					$word_part .= ' '. $huruf[(int)($value / 100)]. ' ratus';
					// $value %= 100;
					$value = fmod($value, 100);

					$loop = true;
					break;
				
				case ($value < 2000):
					$word_part .= ' seribu';
					$value -= 1000;

					$loop = true;
					break;
				
				default:
					$loop = false;
			}
		}
		
		return $word_part;
	}
}