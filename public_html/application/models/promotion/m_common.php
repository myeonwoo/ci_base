<?php
class M_common extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->new_china = $this->load->database("new_china", TRUE);
	}

	public function insert($data = array(), $table_name){
		if(sizeof($data) < 1) return false;
		$return = $this->new_china->insert($table_name, $data);
		return $return;
	}
	
	public function select($data = array(), $table_name){
		if(sizeof($data) > 0){
			// - 일반 where 조건
			if(sizeof($data['where']) > 0){
				foreach($data['where'] as $key => $value){
					$this->new_china->where($key, $value);
				}
			}
			
			// - 날짜 조건
			if($data['period']['start_time'] && $data['period']['end_time']){
				$this->new_china->where($data['period']['column'].' >= \''.$data['period']['start_time'].'\'');
				$this->new_china->where($data['period']['column'].' <= \''.$data['period']['end_time'].'\'');
			}
			
		}
		$query = $this->new_china->get($table_name);
		return $query->result_array();
	}
	
	public function group_count($data = array(), $table_name){
	
		if(sizeof($data) > 0){
			// - 일반 where 조건
			if(sizeof($data['where']) > 0){
				foreach($data['where'] as $key => $value){
					$this->new_china->where($key, $value);
				}
			}
	
			// - 날짜 조건
			if($data['period']['start_time'] && $data['period']['end_time']){
				$this->new_china->where($data['period']['column'].' >= \''.$data['period']['start_time'].'\'');
				$this->new_china->where($data['period']['column'].' < \''.$data['period']['end_time'].'\'');
			}
			
			// - 그룹 조건
			if($data['group']){
				$this->new_china->group_by($data['group']);
				$this->new_china->select($data['group'].", count(".($data['group'].") as count"));
			}
			
			// - 정렬
			if(sizeof($data['order']) > 0){
				foreach($data['order'] as $key => $value){
					$this->new_china->order_by($key, $value);
				}
			}
	
		}
		$query = $this->new_china->get($table_name);
		return $query->result_array();
	
	}
	
}