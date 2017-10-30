<?php
class M_data_survey extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->new_china = $this->load->database("new_china", TRUE);
	}

	public function test()
	{
		$data = array();
		$data[] = 'test';
		$data['qa'] = _IS_DEV_QA;

		// $this->
		$query = $this->new_china->get('DATA_SURVEY');
		$data['list'] = $query->result_array();

		return $data;
	}

	public function insert($data)
	{
		if (sizeof($data) < 1) return false;
		
		$now = date("Y-m-d H:i:s");
		$data['created_time'] = $now;

		$return = $this->new_china->insert('DATA_SURVEY', $data);

		return $return;
	}

	//리스트 
	public function get_list($where_arr=null, $start=null, $limit=null){
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->new_china->where($value["filed_name"], $value["filed_value"]);
			}
		}
		
		$this->new_china->select("data_survey_id
						   , name
						   , comment1
						   , created_time
						   , CASE WHEN created_time >= DATE_ADD(NOW(), INTERVAL - 1 DAY) 
						     THEN 1 ELSE 0 END AS is_new " ,FALSE);	
		$this->new_china->order_by("created_time", "DESC");
		$cursor = $this->new_china->get('DATA_SURVEY', $limit, $start);
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}

	// Get Data
	public function get_all($config = array())
	{
		if (!isset($config['event_id']) || !$config['event_id']) return array();

		$this->new_china->where('event_id', $config['event_id']);
		$query = $this->new_china->get('DATA_SURVEY');

		return $query->result_array();
	}

	//리스트 건수
	public function get_list_cnt($where_arr=null){
		$this->new_china->select('count(*) cnt');

		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->new_china->where($value["filed_name"], $value["filed_value"]);
			}
		}
		
		$cursor = $this->new_china->get('DATA_SURVEY');
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}
}