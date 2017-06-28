<?php
	class Catalog extends CI_Controller {
		public function index($offset = 0) {
			$arr_cart = array();
			$this->load->library('pagination');
			$this->load->model('catalog_model', 'cat');
			$this->load->helper(array('url', 'rus_numeral_with_nour'));
			$currency = array(0 => 'карбованец', 1 => 'карбованца', 2 => 'карбованцев');
			
			$longip = ip2long($_SERVER['REMOTE_ADDR']);
			$uid = $this->cat->get_user_id($longip);
			if($uid === 0) {
				$this->cat->add_user($longip);
				$uid = $this->cat->get_user_id($longip);
			} else if($uid === false) {
				echo '<p>Произошла ошибка при получении id пользователя из БД </p>';
			}
			
			if($uid != 0) {
				//echo '<p>Получен id пользователя '.$uid.'</p>';
			} else {
				die('<p>Ошибка при работе пользователя с адресом '.$_SERVER['REMOTE_ADDR'].' </p>');
			}
			
			$co = $this->cat->get_count_prod();
			
			$config['base_url'] = site_url().'/Catalog/index';
			$config['total_rows'] = $co;
			$config['per_page'] = 24;
			$config['first_link'] = 'Первая';
			$config['last_link'] = 'Послeдняя';
			//$config['full_tag_open'] = '<nav aria-label="Page navigation" class="pag"><ul class="pagination">';
			//$config['full_tag_close'] = '</ul></nav>';
			$config['full_tag_open'] = '<p>';
			$config['full_tag_close'] = '</p>';
			/*$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li>';
			$config['cur_tag_close'] = '</li>';*/
			
			$config['num_tag_open'] = '<div class="num">';
			$config['num_tag_close'] = '</div>';
			$config['cur_tag_open'] = '<div class="current">';
			$config['cur_tag_close'] = '</div>';
			$config['first_tag_open'] = '<div class="num">';
			$config['first_tag_close'] = '</div>';
			$config['last_tag_open'] = '<div class="num">';
			$config['last_tag_close'] = '</div>';
			$config['next_tag_open'] = '<div class="num">';
			$config['next_tag_close'] = '</div>';
			$config['prev_tag_open'] = '<div class="num">';
			$config['prev_tag_close'] = '</div>';

			$this->pagination->initialize($config);
			
			$data['title'] = 'Каталог товаров';
			
			$id_cart = $this->cat->get_cart_id($uid);
			if($id_cart != 0) {
				$arr_cart = $this->cat->get_curr_cart($id_cart);
				$full_price = 0;
				foreach($arr_cart as $pos) {
					$full_price += $pos['count']*$pos['price'];
				}
				$full_price .= ' '.rus_numeral_with_noun($full_price, $currency);
			} else {
				$full_price = 'Корзина пуста';
			}
			$data['full_price'] = $full_price;
			$data['site_url'] = site_url();
			$data['curr_cart'] = $arr_cart;
			$this->load->view('header', $data);
			$data['catalog'] = $this->cat->get_products(24, $offset);
			$data['pagination'] =  $this->pagination->create_links();
			
			$this->load->view('catalog', $data);
		}
		
		public function set_prod_in_cart() {
			$this->load->model('catalog_model', 'cat');
			$longip = ip2long($_SERVER['REMOTE_ADDR']);
			$uid = $this->cat->get_user_id($longip);
			$id_p = $_POST['id_p'];
			$co_p = $_POST['co_p'];
			
			$id_cart = $this->cat->get_cart_id($uid);
			if($id_cart === 0) {
				$this->cat->add_cart($uid);
				$id_cart = $this->cat->get_cart_id($uid);
			} else if($id_cart === false) {
				return;
			} 
			
			$res = $this->cat->set_prod_co($id_cart, $id_p, $co_p);
			
			if($co_p == 0) {
				$del_stat = $this->cat->del_cart_if_empty($id_cart);
				if($del_stat) {
					echo 'Корзина пуста';
					return;
				}
			}
			
			echo $this->get_full_price($id_cart);
		}
		
		function get_full_price($id_cart) {
			$this->load->model('catalog_model', 'cat');
			$this->load->helper('rus_numeral_with_nour');
			$currency = array(0 => 'карбованец', 1 => 'карбованца', 2 => 'карбованцев');
			
			$arr_cart = $this->cat->get_curr_cart($id_cart);
			
			$full_price = 0;
			foreach($arr_cart as $pos) {
				$full_price += $pos['count']*$pos['price'];
			}
			$full_price .= ' '.rus_numeral_with_noun($full_price, $currency);
			
			return $full_price;
		}
	}
?>
