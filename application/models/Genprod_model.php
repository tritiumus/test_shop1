<?php
	class Genprod_model extends CI_Model {
		public function __construct() {
			parent::__construct();
		}
		
		public function get_count_prod() {
			return $this->db->count_all('catalog');
		}
		
		public function add_prod($arr_prod) {
			$this->db->insert('catalog', $arr_prod);
		}
	}
?>
