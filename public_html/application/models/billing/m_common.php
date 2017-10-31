<?php
//공통코드 조회
class M_common extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
		$this->load->library('billing/ST_common'); //공통 관련 API
	}

	function column_code($column_name){
		$excu_list = $this->st_common->column_code($column_name);	//공통 billing api 호출   
		$result = null;

		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	//column_value를 key로 
        	foreach ($excu_list["result"] as $key => $value) {
        		 $result[$value["column_value"]] = $value;
        	}
        }

		return $result;
	}
}