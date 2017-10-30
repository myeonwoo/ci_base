<?php
class M_member extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->eng = $this->load->database('new_china', TRUE);
		$this->load->library('validate');

	}
	
	//전체 멤버 리스트 가져옴(만료된 아이디 제외)
	function member_list(){
		$this->eng->where('expire_time >',date('Y-m-d H:i:s'));
		$query = $this->eng->get('MEMBER');
		$result = $query->result_array();
		$query->free_result();
		
		return $result;
	}
	//전체 멤버 리스트 가져옴
	function member_list_all(){
		$query = $this->eng->get('MEMBER');
		$result = $query->result_array();
		$query->free_result();
		
		return $result;
	}

	//생성된 멤버 번호로 멤버 정보 가져옴
	function get_member($member_id){
		$this->eng->where('member_id',$member_id);
		$query = $this->eng->get('MEMBER');
		$result = $query->row_array();
		$query->free_result();
		
		return $result;
	}

	//어드민 유저 아이디로 실제 권한있는 아이디 체크
	function get_active_member_by_user_id($user_id){
		$this->eng->where('user_id',$user_id);
		$this->eng->where('expire_time >',date('Y-m-d H:i:s'));
		$query = $this->eng->get('MEMBER');
		$result = $query->row_array();
		$query->free_result();
		
		return $result;
	}

	//멤버 번호로 권한 만료처리
	function set_expire($member_id){
		$this->eng->where("member_id", $member_id);
		$data = array(
			'expire_time' => date('Y-m-d H:i:s')
			);
		$return = $this->eng->update('MEMBER', $data);
		return $return;
	}

	function insert_member($data=null){
		if(!$data){
			$data = array(
				'edited_by' => 'system',
				'user_id' => $this->session->userdata('adm_mb_id'),
				'user_name' => $this->session->userdata('adm_name'),
				'role' => 'guest',
				'created_time' => date('Y-m-d H:i:s'),
				'expire_time' => date('Y-m-d H:i:s',strtotime('+3 month')),
				);
		}
		$this->eng->insert('MEMBER', $data);
		return $this->eng->insert_id();
	}

	//로그인된 유저 권한을 반환,
	//유저 권한이 없을시 guest권한 발급 후 guest 반환
	function get_user_role($user_id){
		
		$this->eng->where('user_id',$user_id);
		$this->eng->where('expire_time >',date('Y-m-d H:i:s'));
		$query = $this->eng->get('MEMBER');
		$result = $query->row_array();
		$query->free_result();

		if($result){
			return $result['role'];
		}else{
			$this->insert_member();
			return 'guest';
		}
	}

	public function find_by_id($user_id)
	{
		$this->eng->where('user_id',$user_id);
		$this->eng->where('expire_time >',date('Y-m-d H:i:s'));
		$query = $this->eng->get('MEMBER');
		return $query->row_array();
	}
}