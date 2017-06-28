<?php
	class Catalog_model extends CI_Model {
		public function __construct() {
			parent::__construct();
		}
		
		/**
		 * Возвращает общее количество товаров в каталоге.
		 */
		public function get_count_prod() {
			return $this->db->count_all('catalog');
		}
		
		/**
		 * Возвращает массив с определённым количеством товаров для каталога.
		 */
		public function get_products($lim, $offset) {
			$ret = array();
			$this->db->limit($lim, $offset);
			$query = $this->db->get('catalog');
			foreach($query->result() as $row) {
				$arr_prod['name'] = $row->name;
				$arr_prod['price'] = $row->price;
				$arr_prod['par1'] = $row->par1;
				$arr_prod['par2'] = $row->par2;
				$ret[$row->id_c] = $arr_prod;
			}
			return $ret;
		}
		
		/**
		 * Возвращает id пользователя.
		 */
		public function get_user_id($addr) {
			$query = $this->db->get_where('users', array('ip' => $addr));
			$arr_res = $query->result_array();
			if(count($arr_res) === 0) {
				return 0;
			} else if(count($arr_res) == 1) {
				return reset($arr_res)['id_u'];
			} else {
				return false;
			}
		}
		
		/**
		 * Добавляет пользователя.
		 */
		public function add_user($addr) {
			$this->db->insert('users', array('ip' => $addr));
		}
		
		/**
		 * Возвращает id корзины для пользователя
		 */
		public function get_cart_id($uid) {
			$query = $this->db->get_where('cart', array('id_user' => $uid, 'ord_flag' => '0'));
			$arr_res = $query->result_array();
			if(count($arr_res) === 0) {
				return 0;
			} else if(count($arr_res) == 1) {
				return reset($arr_res)['id_cart'];
			} else {
				return false;
			}
		}
		
		/**
		 * Добавлят корзину для пользователя.
		 */
		public function add_cart($uid) {
			$this->db->insert('cart', array('id_user' => $uid));
		}
		
		/**
		 * Возвращает информацию о выбранных товарах в корзине.
		 * @return Массив с индексами - id продукта, значения - количество.
		 */
		public function get_curr_cart($id_cart) {
			$cart_arr = array();
			$this->db->join('catalog', 'prod_items.id_prod = catalog.id_c', 'left');
			$query = $this->db->get_where('prod_items', array('id_cart' => $id_cart));
			$arr_res = $query->result_array();
			if(count($arr_res) == 0) {
				return 0;
			} else {
				foreach($arr_res as $row) {
					$cart_arr[$row['id_prod']]['count'] = $row['count'];
					$cart_arr[$row['id_prod']]['price'] = $row['price'];
					$cart_arr[$row['id_prod']]['name'] = $row['name'];
				}
				return $cart_arr;
			}
		}
		
		/**
		 * Устанавливае количество для товара в корзине, или удаляяет товар, если количество 0.
		 */
		public function set_prod_co($id_cart, $id_prod, $co) {
			if($co > 0) {
				if($this->check_prod($id_cart, $id_prod) === 0) {
					$data = array('id_cart' => $id_cart, 'id_prod' => $id_prod, 'count' => $co);
					$this->db->insert('prod_items', $data);
				} else {
					$this->db->where('id_cart', $id_cart);
					$this->db->where('id_prod', $id_prod);
					$data = array('count' => $co);
					$this->db->update('prod_items', $data);
				}
			} else {
				$this->db->where('id_cart', $id_cart);
				$this->db->where('id_prod', $id_prod);
				$this->db->delete('prod_items');
			}
		}
		
		/**
		 * Проверяет пуста ли корзина и удаляет её, если пуста.
		 */
		public function del_cart_if_empty($id_cart) {
			$this->db->where('id_cart', $id_cart);
			$res = $this->db->count_all_results('prod_items');
			if($res === 0) {
				$this->db->where('id_cart', $id_cart);
				$this->db->delete('cart');
				return true;
			} 
			return false;
		}
		
		/**
		 * Возвращает количество записей товара в корзине.
		 */
		function check_prod($id_cart, $id_prod) {
			$this->db->where('id_cart', $id_cart);
			$this->db->where('id_prod', $id_prod);
			return $this->db->count_all_results('prod_items');
		}
		
		public function del_prod_from_cart($id_cart) {
			$this->db->where('id_cart', $id_cart);
			$this->db->delete('prod_items');
		}
		
		public function order_send($id_cart) {
			$this->db->where('id_cart', $id_cart);
			$data = array('ord_flag' => 1);
			$this->db->update('cart', $data);
		}
	}
?>
