<?php
//선생님 조회
class M_teacher extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
		$this->load->library('billing/ST_teacher'); //선생님 관련 API
	}

	function getTeachers_by_bizcode($biz_code, $page, $offset){
		$excu_list = $this->st_teacher->getTeachers_by_bizcode($biz_code, $page, $offset);	//공통 billing api 호출   
		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	//primary key를 array key로 
        	foreach ($excu_list["result"]["list"] as $key => $value) {
        		 $result[$value["teacher_id"]] = $value;
        	}
        }

		return $result;
	}
}