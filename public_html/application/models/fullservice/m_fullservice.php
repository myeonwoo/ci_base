
<?php
class M_fullservice extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}
	
	function get_answer_list_page($test_month, $level, $territory, $part){
		
		$this->china_db->where('test_month', $test_month);
		$this->china_db->where('level', $level);
		$this->china_db->where('territory', $territory);
		$this->china_db->where('part', $part);
			
		$cursor = $this->china_db->get('HSK_ANSWER_DETAIL');
		$result = $cursor->result_array();
	
		$cursor->free_result();
		
		return $result;
	}
	
	//풀서비스 본페이지 tab3 경향분석
	function get_trend_list($test_month, $level){
	
		$this->china_db->where('test_nth', $test_month);
		$this->china_db->where('level', $level);
		$this->china_db->where('gubun', 'HGB001');
		$this->china_db->where('teacher', 'TCR001');
			
		$cursor = $this->china_db->get('HSK_TREND');
		$result = $cursor->result_array();
	
		$cursor->free_result();
	
		return $result;
	}
	

	
	//풀서비스 본페이지 tab3 경향분석
	function get_comment_list($test_nth){	
		$sql = "
			SELECT
			        teacher
			       ,(SELECT cd_nm FROM COMMON_CODE_DETAIL S1 WHERE T1.teacher = S1.cd AND upcode = 'TCR' AND del_yn = 'N') AS teacher_nm
			       ,(SELECT sort FROM COMMON_CODE_DETAIL S1 WHERE T1.teacher = S1.cd AND upcode = 'TCR' AND del_yn = 'N') AS sort
			       ,level
			       ,(SELECT cd_nm FROM COMMON_CODE_DETAIL S1 WHERE T1.level = S1.cd AND upcode = 'HLV' AND del_yn = 'N') AS level_nm
			       ,(SELECT img_url FROM SUB_IMG S1 WHERE T1.teacher = S1.cd) AS img_url
			       ,trend
			FROM   HSK_TREND T1
			WHERE  test_nth = '".$test_nth."'
			AND    gubun      = 'HGB002'
        ";
		
		$cursor = $this->china_db->query($sql);
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}
	
	//풀서비스 방문자수 update
	function update_event_count($type){
		
		$this->china_db->where('type',$type);
		$this->china_db->set('click_cnt','click_cnt+1',false);
		$result = $this->china_db->update('EVENT_CLICK');
		
		return $result;
	}
	
	//풀서비스 방문자수 count selet
	function get_event_count($type){
		$this->china_db->where('type',$type);
		$this->china_db->select('click_cnt');
		$cursor = $this->china_db->get('EVENT_CLICK');
		$result = $cursor->row_array();
		
		return $result;
	}
	
	//**************************************************************************
	//** 정답 관리 함수 시작
	//**************************************************************************
	
	//풀서비스 정답 리스트
	function get_answer_list($where_arr=null){
	
		$this->china_db->select("test_month
							   ,SUBSTR(test_month, 1, 4) as year
							   ,SUBSTR(test_month, 5, 6) as month
							   ,level
							   ,territory
							   ,part
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.level     = S1.cd AND S1.upcode = 'HLV' AND del_yn = 'N') as level_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.territory = S1.cd AND S1.upcode = 'TER' AND del_yn = 'N') as territory_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.part      = S1.cd AND S1.upcode = 'PRT' AND del_yn = 'N') as part_nm
							   ,wdate", false);
	
		$this->china_db->order_by("wdate", "DESC");
	
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				if ($value["filed_value"] != ""){
					$this->china_db->where($value["filed_name"], $value["filed_value"]);
				}
			}
		}
	
		$cursor = $this->china_db->get('HSK_ANSWER AS T1');
		$result = $cursor->result_array();
	
		$cursor->free_result();
	
	
		return $result;
	}
	
	//풀서비스 정답 get
	function get_answer_info($where_arr=null){
	
		//$this->china_db->order_by("created_time", "ASC");
			
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}
	
		$cursor = $this->china_db->get('HSK_ANSWER');
		$result = $cursor->row_array();
	
		$cursor->free_result();
	
		return $result;
	}
	
	//풀서비스 정답 리스트
	function get_answer_detail_list($where_arr=null){
			
		$this->china_db->order_by("q_num", "ASC");
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				//if ($value["filed_value"] != ""){
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
				//}
			}
		}
	
		$cursor = $this->china_db->get('HSK_ANSWER_DETAIL');
		$result = $cursor->result_array();
	
		$cursor->free_result();
	
		return $result;
	}
	
	
	//풀서비스 정답 등록
	function ins_answer($data){
		//$result = $this->china_db->insert('HSK_ANSWER', $data);
	
		// insert or update
		$sql = "
				INSERT INTO HSK_ANSWER(
					test_month
					, level
					, territory
					, part
					, reg_id
					, wdate
				)
				VALUES(
					?
					, ?
					, ?
					, ?
					, ?
					, NOW()
				)
				 ON DUPLICATE KEY UPDATE
					  reg_id=VALUES(reg_id)
					,  wdate=VALUES(wdate)
				;
			";
		$result = $this->china_db->query($sql, $data);
	
		return $result;
	}
	
	//풀서비스 정답 상세 등록/수정
	function ins_answer_detail($data){
	
		// insert or update
		$sql = "
				INSERT INTO HSK_ANSWER_DETAIL(
					test_month
					, level
					, territory
					, part
					, q_num
					, answer
					, reg_id
					, wdate
				)
				VALUES(
					?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, NOW()
				)
				 ON DUPLICATE KEY UPDATE
					  answer=VALUES(answer)
					, reg_id=VALUES(reg_id)
					,  wdate=VALUES(wdate)
				;
			";
		$result = $this->china_db->query($sql, $data);
	
	
		return $result;
	}
	
	//**************************************************************************
	//** 정답 관리 함수 끝
	//**************************************************************************

	
	//**************************************************************************
	//** 출제경향 관리 함수 시작
	//**************************************************************************
	
	//풀서비스 출제경향 조회
	function get_review_list($where_arr=null, $order="wdate desc"){
	
		$this->china_db->select("test_month, test_nth
							   ,SUBSTR(test_month, 1, 4) as year
							   ,SUBSTR(test_month, 5, 6) as month
							   ,SUBSTR(test_nth, 7, 8) as nth
							   ,level
							   ,gubun
							   ,teacher
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.level     = S1.cd AND S1.upcode = 'HLV' AND del_yn = 'N') as level_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.gubun     = S1.cd AND S1.upcode = 'HGB' AND del_yn = 'N') as gubun_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.teacher   = S1.cd AND S1.upcode = 'TCR' AND del_yn = 'N') as teacher_nm
							   ,wdate,extra_yn", false);
	
		$this->china_db->order_by($order);
	
		//HSK_TREND.gubun 정의 (HGB001:총평, HGB002:한줄평)
		$this->china_db->where("gubun", "HGB001");
		
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				if ($value["filed_value"] != ""){
					$this->china_db->where($value["filed_name"], $value["filed_value"]);
				}
			}
		}
	
		$cursor = $this->china_db->get('HSK_TREND AS T1');
		$result = $cursor->result_array();

		$cursor->free_result();
		
		return $result;
	}
		
	
	//풀서비스 출제경향 단건 조회
	function get_review_info($where_arr=null){
	
		$this->china_db->select("test_month, test_nth
							   ,SUBSTR(test_month, 1, 4) as year
							   ,SUBSTR(test_month, 5, 6) as month
				 			   ,SUBSTR(test_nth, 7, 8) as nth
							   ,level
							   ,gubun 
							   ,teacher
							   ,trend
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.level     = S1.cd AND S1.upcode = 'HLV' AND del_yn = 'N') as level_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.gubun     = S1.cd AND S1.upcode = 'HGB' AND del_yn = 'N') as gubun_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.teacher   = S1.cd AND S1.upcode = 'TCR' AND del_yn = 'N') as teacher_nm
							   ,wdate,extra_yn", false);
				
		//HSK_TREND.gubun 정의 (HGB001:총평, HGB002:한줄평)
		$this->china_db->where("gubun", "HGB001");
		//$this->china_db->where("teacher", "TCR001");
		
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}		
	
		$cursor = $this->china_db->get('HSK_TREND AS T1');
		$result = $cursor->row_array();

		$cursor->free_result();
		
		return $result;
	}
	
	//철제경향 등록
	function ins_review($data){

		$result = $this->china_db->insert('HSK_TREND', $data);
	
		return $result;
	}
	
	
	//철제경향 수정
	function mod_review($data){
		
		$update_data = array(
				'trend' 	=> $data['trend'],
				'reg_id'	=> $data['reg_id'],
				'wdate' 	=> $data['wdate'],
				'extra_yn'	=> $data['extra_yn'],
				'test_nth'	=> $data['test_nth']
		);
		
		$this->china_db->where('test_nth', $data['test_nth']);
		$this->china_db->where('test_month', $data['test_month']); 
		$this->china_db->where('level', 	$data['level']);
		$this->china_db->where('teacher', 	$data['teacher']);
		$this->china_db->where('gubun', 	'HGB001'); //출제경향 데이터 구분값
		
						
		$result = $this->china_db->update('HSK_TREND', $update_data);
	
		return $result;
	}
	
	//출제경향 이전회차 정보 조회 (키값 변경 후 조회하는 쿼리)
	function searchPrevInfo($test_nth){
		$this->china_db->select("*",false);
		$this->china_db->where("test_nth = (select max(test_nth) from HSK_TREND where test_nth < ".$test_nth.")");
		$this->china_db->where("level in ('HLV006','HLV005','HLV004','HLV003')");
		$cursor = $this->china_db->get('HSK_TREND');
		
		$result = $cursor->result_array();
		$cursor->free_result();

		return $result;
		
	}
	//출제경향 이후회차 정보 조회 (키값 변경 후 조회하는 쿼리2)
	function searchAfterInfo($test_nth){
		$this->china_db->select("*",false);
		$this->china_db->where("test_nth = (select min(test_nth) from HSK_TREND where test_nth > ".$test_nth.")");
		$this->china_db->where("level in ('HLV006','HLV005','HLV004','HLV003')");
		
		$cursor = $this->china_db->get('HSK_TREND');
		$result = $cursor->result_array();
		$cursor->free_result();
		
		return $result;
	}
	//**************************************************************************
	//** 출제경향 관리 함수 끝
	//**************************************************************************		

	//**************************************************************************
	//** 한줄평 관리 함수 시작
	//**************************************************************************

	function get_comment_list_admin($where_arr=null, $order = 'wdate desc'){
		
		$this->china_db->select("test_month
							   ,SUBSTR(test_month, 1, 4) as year
							   ,SUBSTR(test_month, 5, 6) as month
							   ,level
							   ,gubun
							   ,teacher
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.level     = S1.cd AND S1.upcode = 'HLV' AND del_yn = 'N') as level_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.gubun     = S1.cd AND S1.upcode = 'HGB' AND del_yn = 'N') as gubun_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.teacher   = S1.cd AND S1.upcode = 'TCR' AND del_yn = 'N') as teacher_nm
							   ,wdate", false);
	
		$this->china_db->order_by($order);
	
		//HSK_TREND.gubun 정의 (HGB001:총평, HGB002:한줄평)
		$this->china_db->where("gubun", "HGB002");
		
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				if ($value["filed_value"] != ""){
					$this->china_db->where($value["filed_name"], $value["filed_value"]);
				}
			}
		}
	
		$cursor = $this->china_db->get('HSK_TREND AS T1');
		$result = $cursor->result_array();
	
		$cursor->free_result();
		
		return $result;
	}
	
	//풀서비스 한줄평 단건 조회
	function get_comment_info($where_arr=null){
	
		$this->china_db->select("test_month
							   ,SUBSTR(test_month, 1, 4) as year
							   ,SUBSTR(test_month, 5, 6) as month
							   ,level
							   ,gubun
							   ,teacher
							   ,trend
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.level     = S1.cd AND S1.upcode = 'HLV' AND del_yn = 'N') as level_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.gubun     = S1.cd AND S1.upcode = 'HGB' AND del_yn = 'N') as gubun_nm
						       ,(SELECT S1.cd_nm FROM COMMON_CODE_DETAIL AS S1 WHERE T1.teacher   = S1.cd AND S1.upcode = 'TCR' AND del_yn = 'N') as teacher_nm
							   ,wdate", false);
	
		//HSK_TREND.gubun 정의 (HGB001:총평, HGB002:한줄평)
		$this->china_db->where("gubun", "HGB002");
	
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}
	
		$cursor = $this->china_db->get('HSK_TREND AS T1');
		$result = $cursor->row_array();
	
		$cursor->free_result();
	
		return $result;
	}
	
	
	//철제경향 등록
	function ins_comment($data){
	
		$result = $this->china_db->insert('HSK_TREND', $data);
	
		return $result;
	}
	
	
	//철제경향 수정
	function mod_comment($data){
	
		$update_data = array(
				'trend' 	=> $data['trend'],
				'reg_id'	=> $data['reg_id'],
				'wdate' 	=> $data['wdate']
		);
	
	
		$this->china_db->where('test_month', $data['test_month']);
		$this->china_db->where('level', 	$data['level']);
		$this->china_db->where('teacher', 	$data['teacher']);
		$this->china_db->where('gubun', 	'HGB002'); //출제경향 데이터 구분값
	
	
		$result = $this->china_db->update('HSK_TREND', $update_data);
	
		return $result;
	}
	
	//**************************************************************************
	//** 한줄평 관리 함수 끝
	//**************************************************************************
	
	//지난 적중특강 리스트
	function get_pass_hsk_list($where_arr = null, $start=null, $limit=null){
		$this->china_db->order_by("level", "DESC");
		$this->china_db->order_by("test_month", "DESC");

		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($key, $value);
			}
		}
		
		
		$cursor = $this->china_db->get('HSK_PASS_DATA', $limit, $start);
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;

	}

	//지난 적중특강 리스트 건수
	function get_pass_hsk_list_cnt($where_arr = null){
		foreach ($where_arr as $key => $value) {
			$this->china_db->where($key, $value);
		}
		
		$this->china_db->select('count(*) cnt');
		$cursor = $this->china_db->get('HSK_PASS_DATA');
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;

	}


	//지난 적중특강 
	function get_pass_hsk_info($data_id){
		if($data_id) $this->china_db->where("data_id", $data_id);
		
		$cursor = $this->china_db->get('HSK_PASS_DATA');
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;

	}

	//지난 적증특강 등록
	function pass_hsk_ins($data){
		$result = $this->china_db->insert('HSK_PASS_DATA', $data);
		return $result;
	}

	//지난 적중특강 수정 
	function pass_hsk_mod($data, $data_id){
		$this->china_db->where('data_id', $data_id);
		$result = $this->china_db->update('HSK_PASS_DATA', $data);
		return $result;
	}
	
	// - 보여질 데이터 조회
	function view_month_data(){
		$this->china_db->select("test_nth");
		$this->china_db->where('level', 'VIEWMO');
		$this->china_db->where('gubun', 'VIEWMO');
		$this->china_db->where('teacher', 'VIEWMO');
		$cursor = $this->china_db->get('HSK_TREND');
		$result = $cursor->row_array();
		return $result;
	}
	
	// - 화면에 보여질 month 수정
	function view_month_update($view_date){
		$data = array(
				'test_nth' => $view_date
		);
		$this->china_db->where('level', 'VIEWMO');
		$this->china_db->where('gubun', 'VIEWMO');
		$this->china_db->where('teacher', 'VIEWMO');
		$result = $this->china_db->update('HSK_TREND', $data);
		return $result;
	}
}

