<?php
class M_test extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}

	public function get_test(){
		$sql = "SELECT * FROM tb_test";
		//$sql = "SELECT NOW() AS today";

		$qry = $this->china_db->query($sql);
		$result = $qry->result_array();

		return $result;
	}

}



