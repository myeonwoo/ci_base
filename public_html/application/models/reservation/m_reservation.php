<?php


class M_reservation extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);

		$this->load->driver('cache');
        $this->cache_enabled = (_IS_DEV_QA) ? false : true;

	}

	//이벤트 예약 정보
	function get_info($event_id){
		$this->china_db->where('event_id =', $event_id);
		$cursor = $this->china_db->get('EVENT_MANAGEMENT');
		
		$return = $cursor->row_array();
		
		return $return;
	}

	//이벤트 예약 건수
	function get_list_cnt($event_id){
		$this->china_db->select('count(*) cnt');
		$this->china_db->where('event_id =', $event_id);
		$cursor = $this->china_db->get('EVENT_MANAGEMENT_MEM');
		
		$return = $cursor->result_array();
		
		return $return;
	}

	//이벤트 저장
	function event_ins($data){
		$return = $this->china_db->insert('EVENT_MANAGEMENT_MEM', $data);

		return $return;	
	}

	//이벤트 중복체크
	function chk_double_event_ins($user_id){
		$this->china_db->select('count(*) cnt');
		$this->china_db->where('user_id =', $user_id);
		$cursor = $this->china_db->get('EVENT_MANAGEMENT_MEM');
		
		$return = $cursor->row_array();
		
		return $return;
	}
	
	//이벤트에 따른 중복 체크
	function chk_event_user($user_id, $event_id){
		$this->china_db->select('count(*) cnt');
		$this->china_db->where('user_id', $user_id);
		$this->china_db->where('event_id', $event_id);
		$cursor = $this->china_db->get('EVENT_MANAGEMENT_MEM');
		$return = $cursor->row_array();
		return $return;
	}
}	