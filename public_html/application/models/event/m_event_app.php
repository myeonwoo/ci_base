<?php
class M_event_app extends CI_Model {
	
	//private $table = 'EVENT_APP';
	
	
	
	function __construct() {
		parent::__construct();
		$this->new_china = $this->load->database('new_china', TRUE);
	}
		
	
	/**
	 * 관리자 이벤트 리스트
	 * @param unknown $limit
	 * @param unknown $start
	 * @return unknown
	 */
	function adminEventList($limit, $start){
		
		$this->new_china->order_by("event_id", "DESC");
		$this->new_china->limit($limit, $start);
		$query = $this->new_china->get('EVENT_APP');
		
		$return = $query->result_array();
		
		return $return;
		 
	}
	function get_cnt_newevent($config = array()){

		$sql = "SELECT count(*) as cnt from EVENT_APP
				where created_time > now() - INTERVAL 1 DAY
		";
		$query = $this->new_china->query($sql);
		$result = $query->row();

		return $result->cnt;	 
	}
	function eventList($config = array()){

		$this->new_china->where('use_yn =', 'Y');
		$this->new_china->order_by("event_id", "DESC");
		$this->new_china->limit($config['limit'], $config['start']);

		if (isset($config['search_title'])) $this->new_china->where('subject', $config['search_title']);
		$query = $this->new_china->get('EVENT_APP');
		
		$return = $query->result_array();
		
		return $return;		 
	}
	
	function eventTotal($config = array()){

		if (isset($config['search_title'])) $this->new_china->where('subject', $config['search_title']);
		$this->new_china->where('use_yn =', 'Y');
		$query = $this->new_china->get('EVENT_APP');
		$return = $query->num_rows();
		return $return;	 
	}
	
	function evnet_new_check($is_date = null){
		$this->new_china->select('count(*) as cnt');
		$this->new_china->where('use_yn', 'Y');
		$this->new_china->where('date_format(event_start_time, "%Y%m%d") = "'.$is_date.'"');
		$query = $this->new_china->get('EVENT_APP');
		$result = $query->row();
		return $result->cnt;
	}
	
	/**
	 * 중단기 이벤트 상세보기
	 * @param int $event_id
	 * @return unknown
	 */
	function eventView($event_id){
	
		$this->new_china->where('event_id =', $event_id);
		$query = $this->new_china->get('EVENT_APP');
		$return = $query->row_array();
		return $return;
	}
	
	/**
	 * 현재 진행중인 이벤트
	 * @return unknown
	 */
	function eventAll(){
	
		$this->new_china->where('event_start_time <= NOW() AND event_end_time >= NOW()');
		$this->new_china->where('use_yn =', 'Y');
		$this->new_china->order_by("event_id", "DESC");
		$query = $this->new_china->get('EVENT_APP');
		$return = $query->result_array();
		return $return;
	
	}
	
	/**
	 * 관리자 이벤트 리스트
	 * @return unknown
	 */
	function adminEventListRow(){
	
		$query = $this->new_china->get('EVENT_APP');
		$return = $query->num_rows();
		return $return;
	}
	
	
	/**
	 * 현재 진행중인 이벤트
	 * @return unknown
	 */
	function chinadangiEventAll(){
		
		$this->new_china->where('event_start_time <= NOW() AND event_end_time >= NOW()');
		$this->new_china->where('use_yn =', 'Y');
		//$this->new_china->where('event_type =', $event_type);
		$this->new_china->order_by("event_id", "DESC");
		$query = $this->new_china->get('EVENT_APP');
		
		//echo $this->billdb->last_query(); 
		$return = $query->result_array();
		return $return;
		
	}
	
	
	
	
	/**
	 * 중단기 이벤트 리스트
	 * @param string $ing 진행중인 이벤트 
	 * @return unknown
	 */
	function chinadangiEventList($ing = null){
		
		$limit = 8;
		
// 		if($this->input->get('event_code')){
// 			$this->new_china->where('event_code =', $this->input->get('event_code'));
// 		}
		
		if($this->input->get('search_name')){
			$this->new_china->like('subject', $this->input->get('search_name'), 'both');
		}
		
		if($ing == 'true'){
			$this->new_china->where('event_start_time <= NOW() AND event_end_time >= NOW()');
		}
		
		$this->new_china->where('use_yn =', 'Y');
// 		$this->new_china->where('event_type =', $event_type);
		$this->new_china->order_by("event_id", "DESC ");
		$this->new_china->limit($limit);
		$query = $this->new_china->get('EVENT_APP');
		

		$return = $query->result();
		return $return;
		
	}
	
	
	/**
	 * 중단기 이벤트 더 보기 리스트
	 * @param string $start 이벤트 시작일
	 * @param string $end 이벤트 종료일
	 * @param string $ing 진행중인 이벤트
	 * @return unknown
	 */
	function chinadangiEventListAdd($start =null,$end = null,$ing= null){
		
		if($ing == 'true'){
			$this->new_china->where('event_start_time <= NOW() AND event_end_time >= NOW()');
		}
		
		if($this->input->get('search_name')){
			$this->new_china->like('subject', $this->input->get('search_name'), 'both');
		}
		
		
// 		if($this->input->get('event_code')){
// 			$this->new_china->where('event_code =', $this->input->get('event_code'));
// 		}
		
		$this->new_china->where('use_yn =', 'Y');
		//$this->new_china->where('event_type =', $event_type);
		$this->new_china->order_by("event_id", "DESC ");
		$this->new_china->limit($end, $start);
		$query = $this->new_china->get('EVENT_APP');
		
		
		$return = $query->result();
		
		
		//echo  $this->new_china->last_query();
		return $return;
		
	}
	
	
	
	
	/**
	 * 중단기 이벤트 상세보기
	 * @param int $event_id
	 * @return unknown
	 */
	function chinadangiEventView($event_id){
		
		$this->new_china->where('event_id =', $event_id);
		$query = $this->new_china->get('EVENT_APP');
		
		$return = $query->row_array();
		
		return $return;
		
		
	}
	
	
	/**
	 * 이벤트 입력
	 * @param unknown $data
	 * @return unknown
	 */
	function eventAppIns($data){
		
		$return = $this->new_china->insert('EVENT_APP', $data);

		return $return;
		
	}
	
	
	/**
	 * 이벤트 수정
	 * @param unknown $data
	 * @param unknown $event_id
	 * @return unknown
	 */
	function eventAppMod($data, $event_id){
	
		$this->new_china->where('event_id', $event_id);
		$this->new_china->update('EVENT_APP', $data);
	
		return $return;
	}

	/**
	 * 배포 이벤트 당첨자 리스트 가져오기
	 * @param unknown $data
	 * @param unknown $event_id
	 * @return unknown
	 */
	function event_winner_list($board_arti_id, $output_date){

		$this->event_db = $this->load->database('event', TRUE);

		$this->event_db->select("DISTINCT board_memo_id,reg_id,reg_name,MAX(reg_dt) as reg_dt, agr_cnt", false);
		$this->event_db->where(
			array(
				"board_arti_id" => $board_arti_id,
				"note" => $output_date,
				"del_yn" => "N"
			)
		);
		$this->event_db->group_by("reg_id");
		$this->event_db->order_by("board_memo_id","ASC");

		$result  = $this->event_db->get("b_board_memo")->result_array();
		return $result;
	}

	public function get_event_reservation($event_mem_id){
// $event_mem_id = '177657';
// $event_mem_id = '48863';
		$this->event_db = $this->load->database('event', TRUE);
		$this->event_db->where('EVENT_MEM_ID', $event_mem_id);
		$query = $this->event_db->get('event_management_mem');
		return $query->row_array();
	}
	
	public function get_event_mem_list($event_id, $search_type = null, $search_value = null, $search_start_date = null, $search_end_date = null, $sms_check = null){
		
// 		$this->new_china->select("event_mem_id, event_id, user_id, name, tel, email, fileup_fullpath, comment, comment2, add_option_agree, pc_mobile_gbn, created_time, add_option_sms", false);
// 		$this->new_china->where('EVENT_ID', $event_id);
// 		if($search_type == "user_id"){
// 			if($search_value){
// 				$this->new_china->where('user_id', $search_value);
// 			}
// 		}else if($search_type == "name"){
// 			if($search_value){
// 				$this->new_china->where('name', $search_value);
// 			}
// 		}else if($search_type == "add_option_sms"){
// 			if($sms_check){
// 				$this->new_china->where('add_option_sms', $sms_check);
// 			}
// 		}
// 		if($search_start_date && $search_end_date){
// 			$this->new_china->where('created_time >',$search_start_date);
// 			$this->new_china->where('created_time <',$search_end_date);
// 		}
// 		$this->new_china->order_by( "event_mem_id", "desc");
// 		$result  = $this->new_china->get("EVENT_MANAGEMENT_MEM")->result_array();
		
		$sub_where = "AND main.event_id = ".$event_id;
		if($search_type == "user_id"){
			if($search_value){
				$sub_where = $sub_where." AND main.user_id = '".$search_value."'";
			}
		}else if($search_type == "add_option_sms"){
			if($sms_check){
				$sub_where = $sub_where." AND main.add_option_sms = '".$sms_check."'";
			}
		}else if($search_type == "name"){
			if($search_value){
				$sub_where = $sub_where." AND main.name = '".$search_value."'";
			}
		}
		
		if($search_start_date && $search_end_date){
			$sub_where = $sub_where." AND main.created_time > '".$search_start_date."' AND main.created_time < '".$search_end_date."'";
		}
		
		$sql = "SELECT 
					@rownum:=@rownum+1 as num
					, main.event_mem_id
					, main.event_id
					, main.user_id
					, main.name
					, main.tel
					, main.email
					, main.fileup_fullpath
					, main.comment
					, main.comment2
					, main.add_option_agree
					, main.pc_mobile_gbn
					, main.created_time
					, main.add_option_sms
				FROM 
					EVENT_MANAGEMENT_MEM as main, (SELECT @rownum:=0) r
				WHERE 1 =1 
				".$sub_where.
				" ORDER BY num desc";
		
		$qry = $this->new_china->query($sql);
		$result = $qry->result_array();
		$qry->free_result();
		return $result;
	}

}