<?php
	class Cart extends CI_Controller {
		public function index() {
			$this->load->helper(array('url', 'rus_numeral_with_nour'));
			$this->load->model('catalog_model', 'cat');
			$currency = array(0 => 'карбованец', 1 => 'карбованца', 2 => 'карбованцев');
			
			$data['title'] = 'Корзина';
			$this->load->view('header', $data);
			
			$id_cart = $this->get_id_cart();
			if($id_cart != 0) {
				$arr_cart = $this->cat->get_curr_cart($id_cart);
				$full_price = 0;
				foreach($arr_cart as $pos) {
					$full_price += $pos['count']*$pos['price'];
				}
				$full_price .= ' '.rus_numeral_with_noun($full_price, $currency);
				$data['full_price'] = $full_price;
				$data['arr_cart'] = $arr_cart;
			} else {
				$data['full_price'] = '<p>Корзина пуста</p>';
				$data['flag_cart'] = 'empty';
			}
			$data['site_url'] = site_url();
			$this->load->view('cart', $data);
		}
		
		public function clear_cart() {
			$this->load->model('catalog_model', 'cat');
			$id_cart = $this->get_id_cart();
			if($id_cart != 0) {
				$this->cat->del_prod_from_cart($id_cart);
				$this->cat->del_cart_if_empty($id_cart);
			}
			sleep(2);
		}
		
		function get_id_cart() {
			$this->load->model('catalog_model', 'cat');
			
			$longip = ip2long($_SERVER['REMOTE_ADDR']);
			$uid = $this->cat->get_user_id($longip);
			if($uid === 0) {
				$this->cat->add_user($longip);
				$uid = $this->cat->get_user_id($longip);
			} else if($uid === false) {
				die('<p>Произошла ошибка при получении id пользователя из БД </p>');
				
			}
			
			$id_cart = $this->cat->get_cart_id($uid);
			return $id_cart;
		}
		
		public function send_order() {
			$this->load->model('catalog_model', 'cat');
			$this->load->helper('mailsender');
			$this->load->config('test_shop1');
			
			$id_cart = $this->get_id_cart();
			
			$status = array();
			if($id_cart != 0) {
				///<html>
				$message = '<p>Заказ №'.$id_cart.'<p><table border="1" bordercolor="black">';
				$message .= '<tr><th>id</th><th>наименование</th><th>Цена</th><th>Количество</th><th>Стоимость</th></tr>';
				$email = $this->config->item('email1', 'test_shop1');
				$arr_cart = $this->cat->get_curr_cart($id_cart);
				$total_price = 0;
				foreach($arr_cart as $key => $row) {
					$message .= '<tr><td>'.$key.'</td>';
					$message .= '<td>'.$row['name'].'</td>';
					$message .= '<td>'.$row['price'].'</td>';
					$message .= '<td>'.$row['count'].'</td>';
					$cost = $row['count']*$row['price'];
					$message .= '<td>'.$cost.'</td>';
					$message .= '</tr>';
					$total_price += $cost;
				}
				$message .= '</table>';
				$message .= '<p>Общая стоимость: '.$total_price.'</p>';
				//$message .= '</body></html>';
				//echo $message;
				//return;
				$res = mailsender($email, $message, 'Заказ № '.$id_cart);
				if(array_key_exists(0, $res)) {
					$this->cat->order_send($id_cart);
					$status['state'] = 'success';
					$status['mess'] = '<p>Заказ успечно отправлен</p>';
				} else {
					$status['state'] = 'fail';
					$status['mess'] =  'Не удалось отправить заказ, попробуйте ещё раз.';
				}
				
			} else {
				$status['state'] = 'fail';
				$status['mess'] =  'Произошла ошибка при отправке заказа.';
			}
			
			echo json_encode($status);
		}
	}
?>
