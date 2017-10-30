<?php
class M_comment extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}

	//한줄 댓글 리스트
	function get_list($where_arr=null, $start=null, $limit=null){
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}
		$this->china_db->order_by("is_notice", "DESC");
		$this->china_db->order_by("created_time", "DESC");
		$cursor = $this->china_db->get('COMMENT', $limit, $start);

		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}

	//리스트 건수
	function get_list_cnt($where_arr=null){
		$this->china_db->select('count(*) cnt');

		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}
		
		$cursor = $this->china_db->get('COMMENT');
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}

	//한줄 댓글 등록
	function one_line_comment_ins($data){
		$result = $this->china_db->insert('COMMENT', $data);
		return $result;
	}

	//한줄 댓글 수정
	function one_line_comment_del($data){
		$this->china_db->where('group_id', $data["group_id"]);
		$this->china_db->where('comment_id', $data["comment_id"]);

		$this->china_db->set('deleted_yn', '1');
		$result = $this->china_db->update('COMMENT', $data);
		
		return $result;
	}

	//공지사항 지정
	function set_comment_notice($data){
		$this->china_db->where('group_id', $data["group_id"]);
		$this->china_db->where('comment_id', $data["comment_id"]);

		$this->china_db->set('is_notice', '1');
		$result = $this->china_db->update('COMMENT', $data);
		
		return $result;

	}

	// Get Data
    public function get_all($config = array())
    {
        if (!isset($config['group_id']) || !$config['group_id']) return array();

        $this->china_db->where('group_id', $config['group_id']);
        $this->china_db->order_by('created_time', 'desc');
        $query = $this->china_db->get('COMMENT', 1000, 0);

        return $query->result_array();
    }
}