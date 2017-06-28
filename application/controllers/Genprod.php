<?php
	class Genprod extends CI_Controller {
		public function index($name, $quantity = 50) {
			$this->load->model('genprod_model', 'gen_mod');
			$co = $this->gen_mod->get_count_prod();
			if(!isset($name)) {
				echo '<p>Не задано наименование для товара. Добавьте наименование в url после genprod/index/. По умолчанию количество добавляемого товара равно 50. После наиметования товара через слеш можно указать необходимое количество. </p>';
				return;
			}
			$nm = urldecode($name);
			echo '<p>Количество до добавления = '.$co.'</p>';
			for($i = 0; $i < $quantity; ++$i) {
				$num  = $co + 1 + $i;
				$cost =  rand(1, 1000);
				$this->gen_mod->add_prod(array('name' => $nm.' '.$num, 'price' => $cost, 'par1' => 'Параметр 1 для товара '.$num, 'par2' => 'Параметр 2 для товара '.$num));
			}
			echo '<p>В таблицу добавлено ещё '.$quantity.' товаров</p>';
		}
	}
?>
