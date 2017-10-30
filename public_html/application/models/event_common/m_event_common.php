<?php
class M_event_common extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}
	
	function check_user($type = null, $user_id = null){
		$this->china_db->select("column1");
		$this->china_db->where("type", $type);
		$this->china_db->where("column1", $user_id);
		$qry= $this->china_db->get("CHINA_EVENT_COMMON_TABLE");
		$result = $qry->row();
		return $result->column1;
	}
	
	function event_join($data = array()){
		$result = $this->china_db->insert('CHINA_EVENT_COMMON_TABLE', $data);
		return $result;
	}
	
	function get_all($config = array()){
		if (!isset($config['event_id']) || !$config['event_id']) return array();

		$this->china_db->where('type', $config['event_id']);
		$query = $this->china_db->get('CHINA_EVENT_COMMON_TABLE');

		return $query->result_array();
	}
	
}

