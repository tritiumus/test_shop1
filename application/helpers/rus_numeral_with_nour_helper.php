<?php
	/**
	 * @author Vaschenko Alexandr <Vash-Alexandr@yandex.ru>
	 */


	function rus_numeral_with_noun($val, $arr_noun) {
		
		if(count($arr_noun) != 3) {
			return false;
		}
		
		$diapaz = array(
			'10-19' => 2,
			'5-9' => 2,
			'0' => 2,
			'2-4' => 1,
			'1' => 0 
		);
		
		if(is_string($val)) {
			$val = trim($val);
		} else if(!is_int($val)) {
			return false;
		}
		
		foreach($diapaz as $dia => $word_ind) {
			if(strstr($dia, '-') !== false) {
				$arr_dia = explode('-', $dia);
				$len = strlen(current($arr_dia));
				$str_val = substr($val, -$len);
				if($arr_dia[0] <= $str_val && $arr_dia[1] >= $str_val) {
					return $arr_noun[$word_ind];
				}
			} else {
				$len = strlen($dia);
				if(substr($val, -$len) == $dia) {
					return $arr_noun[$word_ind];
				}
			}
		}
	}
?>
