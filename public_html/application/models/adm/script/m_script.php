<?php
class M_script extends CI_Model {
	
	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('new_china', true);
	}

	/*
	*	url에 해당하는 스크립트 코드 데이터 반환 (컨트롤러 사용)
	*/
	function get_script_code_by_url($script_url=null,$script_type=null,$device_type=null){
	//url에 해당하는 스크립트 아이디 값 구하기
		$result = $this->get_script_by_url($script_url,$script_type,$device_type);
		if(!$result) return null;

		$script_id = $result['script_id'];
	//script_id에 해당하는 스크립트 아이디 값 구하기	
		$result = $this->get_script_code_by_script_id($script_id);
		if(!$result) return null;

		foreach ($result as $key => $value) {
			$result_str = $result_str . $value['script_import_url']. $value['script_code'];
		}
		return htmlspecialchars_decode($result_str);
	}

	/*
	*	url에 해당하는 단일 스크립트 데이터 반환
	*/
	function get_script_by_url($script_url=null,$script_type=null,$device_type=null){
		//파라미터 null일 경우 return false;
		if($script_type==null||$device_type==null){return null;}

		//body용 스크립트는 조건 필요
		if($script_type=='B'){
			$this->db->where('script_url',$script_url);			
			$this->db->where('start_time<','NOW()',false);
			$this->db->where('end_time>','NOW()',false);
		}

		$this->db->where('script_type',$script_type);
		$this->db->where('device_type',$device_type);
		$cursor = $this->db->get('SCRIPT_LIST');

		$result = $cursor->row_array();
		$cursor->free_result();
		return $result;
	}

	/*
	*	script_type에 해당하는 전체 데이터 가져옴
	*/
	function get_script_all($script_type=null){
		//파라미터 null일 경우 return false;
		if($script_type==null){return null;}

		$this->db->where('script_type',$script_type);
		$cursor = $this->db->get('SCRIPT_LIST');

		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}


	/*
	*	script_id에 해당하는 데이터 가져옴
	*/
	function get_script_by_script_id($script_id=null){
		//파라미터 null일 경우 return false;
		if($script_id==null){return null;}

		$this->db->where('script_id',$script_id);
		$cursor = $this->db->get('SCRIPT_LIST');

		$result = $cursor->row_array();
		$cursor->free_result();
		return $result;
	}

	/*
	*	script_id에 해당하는 스크립트 코드 데이터 반환(use_yn 조건)
	*/
	function get_script_code_by_script_id($script_id=null){
		//파라미터 null일 경우 return false;
		if($script_id==null){return null;}

		$this->db->where('script_id',$script_id);
		$this->db->where('use_yn','Y');
		$this->db->order_by('sort','ASC');

		$cursor = $this->db->get('SCRIPT_CODE');

		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}

	/*
	*	script_id에 해당하는 전체 스크립트 코드 데이터 반환 (어드민용)
	*/
	function get_script_code_by_script_id_all($script_id=null){
		//파라미터 null일 경우 return false;
		if($script_id==null){return null;}

		$this->db->where('script_id',$script_id);
		$this->db->order_by('sort','ASC');

		$cursor = $this->db->get('SCRIPT_CODE');

		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}

	/*
	*	script 입력
	*/
	function ins_script($data=null){
		//파라미터 null일 경우 return false;
		if($data==null){return null;}

		$result=array('result'=>'FAIL','msg'=>'');

		$insert_data['script_name']=$data['script_name'];
		$insert_data['script_url']=$data['script_url'];
		$insert_data['start_time']=$data['start_time'];
		$insert_data['end_time']=$data['end_time'];
		$insert_data['script_type']=$data['script_type'];
		$insert_data['device_type']=$data['device_type'];

		//스크립트 리스트 입력
		$ins_list_result = $this->db->insert('SCRIPT_LIST',$insert_data);

		$script_id = $this->db->insert_id(); //마지막으로 실행한 아이디값 가져옴

		if($ins_list_result==0){//실패하면 중도 exit
			$result['msg']="FAIL_INS_SCRIPT_LIST";
			return $result;
		}

		//스크립트 코드 입력
		$ins_code_result = $this->_ins_script_code($script_id,$data['script_code']);

		if($ins_code_result==null){//실패하면 중도 exit
			//TODO: 스크립트 리스트 롤백 필요!
			$result['msg']="FAIL_INS_SCRIPT_CODE";
			return $result;			
		}else{//정상 입력
			$result['result']="SUCCESS";
			$data['script_id']=$script_id;
			$this->_ins_script_ins_log($data);
			return $result;
		}
	}



	/*
	*	script 수정
	*/
	function mod_script($data=null){
		//파라미터 null일 경우 return false;
		if($data==null){return null;}

		$result=array('result'=>'FAIL','msg'=>'');

		$update_data['script_name']=$data['script_name'];
		$update_data['script_url']=$data['script_url'];
		$update_data['start_time']=$data['start_time'];
		$update_data['end_time']=$data['end_time'];
		$update_data['script_type']=$data['script_type'];
		$update_data['device_type']=$data['device_type'];

		//스크립트 리스트 수정
		$this->db->where('script_id',$data['script_id']);
		$result['other']=$data['script_id'];
		$ins_list_result = $this->db->update('SCRIPT_LIST',$update_data);
		
		if($ins_list_result==0){//실패하면 중도 exit
			$result['msg']="FAIL_INS_SCRIPT_LIST";
			return $result;
		}

		//스크립트 코드 전체 삭제 후 전체 입력
		$del_code_result = $this->_del_script_code($data['script_id']);
		if($del_code_result==null){//실패하면 중도 exit
			//TODO: 스크립트 리스트 롤백 필요!
			$result['msg']="FAIL_DEL_SCRIPT_CODE";
			return $result;
		}

		$ins_code_result = $this->_ins_script_code($data['script_id'],$data['script_code']);
		if($ins_code_result==null){//실패하면 중도 exit
			//TODO: 스크립트 리스트 롤백 필요!.. 트랜젝션 거는게 더 나을듯
			$result['msg']="FAIL_INS_SCRIPT_CODE";
			return $result;			
		}else{//정상 입력
			$result['result']="SUCCESS";
			$this->_ins_script_ins_log($data);
			return $result;
		}
	}

	/*
	*	script 삭제
	*/
	function del_script($data=null){
		//TODO:삭제
	}

	/*
	*	script_id를 가진 script code 전체 삭제
	*/
	private function _del_script_code($script_id=null){
		if($script_id==null){return null;}

		$this->db->where('script_id',$script_id);
		$ins_list_result = $this->db->delete('SCRIPT_CODE');
		return $ins_list_result;
	}

	/*
	*	script code 입력
	*/
	private function _ins_script_code($script_id=null,$data=null){
		if($script_id==null||$data==null){return null;}

		foreach ($data as $key => $value) {
			$insert_data['script_id']=$script_id;
			$insert_data['sort']=$value['sort'];
			$insert_data['use_yn']=$value['use_yn'];
			$this->db->set('script_import_url',htmlspecialchars($value['script_import_url']));
			$this->db->set('script_code',htmlspecialchars($value['script_code']));

			//스크립트 리스트 입력
			$ins_list_result = $this->db->insert('SCRIPT_CODE',$insert_data);
			if($ins_list_result==0){
				return null;
			}
		}
		return count($data);
	}

	/*
	*	입력 로그
	*/
	private function _ins_script_ins_log($data){
		if($data==null){return null;}

		//make log_text
		$log_text=json_encode($data);

		$insert_data['script_id']=$data['script_id'];
		$insert_data['admin_id']=$this->session->userdata("adm_mb_id");
		$insert_data['created_time']=date('Y-m-d H:i:s');
		$insert_data['log_type']='INS';

		$this->db->set('log_text',htmlspecialchars($log_text));

		//스크립트 리스트 입력
		$ins_list_result = $this->db->insert('SCRIPT_LOG',$insert_data);
	}

	/*
	*	에러 로그
	*/
	function err_script_ins_log($data){
		if($data==null){return null;}

		$insert_data['script_id']=$data['script_id'];
		$insert_data['log_text']=$data['log_text'];
		$insert_data['admin_id']=$data['admin_id'];
		$insert_data['created_time']=date('Y-m-d H:i:s');
		$insert_data['log_type']='ERR';

		//스크립트 리스트 입력
		$ins_list_result = $this->db->insert('SCRIPT_LOG',$insert_data);
	}

	/*
	*	script_id에 해당하는 전체 스크립트 로그 데이터 반환 (어드민용)
	*/
	function get_script_log_by_script_id($script_id=null,$log_type=null){
		//파라미터 null일 경우 return false;
		if($script_id==null||$log_type==null){return null;}

		$this->db->where('script_id',$script_id);
		$this->db->where('log_type',$log_type);
		$this->db->order_by('script_log_id','DESC');

		$cursor = $this->db->get('SCRIPT_LOG');
		
		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}
	/*
	*	script_id에 해당하는 스크립트 로그 데이터 반환 (어드민용)
	*/
	function get_script_log_by_script_log_id($script_log_id=null){
		//파라미터 null일 경우 return false;
		if($script_log_id==null){return null;}

		$this->db->where('script_log_id',$script_log_id);

		$cursor = $this->db->get('SCRIPT_LOG');
		
		$result = $cursor->row_array();
		$cursor->free_result();
		return $result;
	}

	//문제가 있는 스크립트_코드의 사용여부를 n으로 만든다
	function set_use_n_err_script_code($script_code_id){
		if($script_code_id==null){return null;}

		$update_data['use_yn']='N';

		//스크립트 리스트 수정
		$this->db->where('script_code_id',$script_code_id);
		$ins_list_result = $this->db->update('SCRIPT_CODE',$update_data);

	}

	//URL 유효성 테스트용 스크립트 리스트 (사용중인 스크립트 전체)
	function get_script_test_list(){

		$this->db->where('use_yn','Y');
		$cursor = $this->db->get('SCRIPT_CODE');

		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;

	}

	//스크립트 검사결과- 기록된 에러로그 리스트
	function get_script_error_list(){

		$this->db->where('log_type','ERR');
		$cursor = $this->db->get('SCRIPT_LOG');

		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;

	}

	//문자열 내 사이트 주소 검출
	function get_domain_from_script($script){
		
		preg_match('/\b(?:src=)[\'"]{1}[\/a-zA-Z0-9-+&@#\/%?=~_|!:,.;]*[\'"]{1}/', $script, $matches);

		return $matches;
	}

}