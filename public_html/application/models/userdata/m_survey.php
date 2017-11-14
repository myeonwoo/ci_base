<?php
/**
 * 관련 테이블
 * DATA_SURVEY
 * DATA_SURVEY_CONFIG
 */
class M_survey extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}

	/**
	 * data_survey config 리스트
	 */
	public function get_config_list($options=array())
	{
		if(isset($options['search_word'])) $this->db->like('title',$options['search_word']);
		$this->db->order_by("data_survey_config_id", "DESC");
		$this->db->from('DATA_SURVEY_CONFIG');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_config($data_survey_config_id)
	{
		$this->db->where('data_survey_config_id',$data_survey_config_id);
		$this->db->from('DATA_SURVEY_CONFIG');
		$query = $this->db->get();

		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}

	public function insert_config($data){
		return $this->db->insert('DATA_SURVEY_CONFIG', $data);
	}
	
	public function update_config($data_survey_config_id, $data){
		$this->db->where('data_survey_config_id', $data_survey_config_id);
		return $this->db->update('DATA_SURVEY_CONFIG', $data);
	}

	/**
	 * 테이블 구조
	 	CREATE TABLE `DATA_SURVEY_CONFIG` (
	 	  `data_survey_config_id` int(11) NOT NULL AUTO_INCREMENT,
	 	  `title` varchar(150) DEFAULT NULL COMMENT '이벤트 제목',
	 	  `gather_id` int(11) DEFAULT NULL COMMENT 'MEMBER 단기 : 수집 이벤트 코드',
	 	  `yn_phone` tinyint(1) DEFAULT '0' COMMENT '사용여부: phone',
	 	  `yn_name` tinyint(1) DEFAULT '0' COMMENT '사용여부: name',
	 	  `yn_address` tinyint(1) DEFAULT '0' COMMENT '사용여부: address',
	 	  `yn_email` tinyint(1) DEFAULT '0' COMMENT '사용여부: email',
	 	  `yn_login` tinyint(1) DEFAULT '1' COMMENT '이벤트 지원시 로그인 여부',
	 	  `yn_bank_name` tinyint(1) DEFAULT '0' COMMENT '은행명 사용여부',
	 	  `yn_bank_account` tinyint(1) DEFAULT '0' COMMENT '은행계좌명 사용여부',
	 	  `yn_bank_owner` tinyint(1) DEFAULT '0' COMMENT '은행계좌명의 사용여부',
	 	  `yn_user_comment` tinyint(1) DEFAULT '0' COMMENT '사용여부: 지원자 코멘트',
	 	  `yn_use` tinyint(1) DEFAULT '1' COMMENT '이벤트 사용 여부',
	 	  `yn_file` tinyint(1) DEFAULT '0' COMMENT '사용여부: 첨부 file',
	 	  `cnt_limit` int(11) DEFAULT '1000' COMMENT '이벤트 접수 제한수',
	 	  `cnt_inserted` int(11) DEFAULT '0' COMMENT '이벤트 지원수',
	 	  `cnt_like` bigint(20) DEFAULT '0',
	 	  `dt_start` datetime DEFAULT NULL COMMENT '이벤트 시작',
	 	  `dt_end` datetime DEFAULT NULL COMMENT '이벤트 종료',
	 	  `dt_created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '생성 날자',
	 	  `desc` text COMMENT '이벤트 설명',
	 	  `note` text COMMENT '유의사항',
	 	  PRIMARY KEY (`data_survey_config_id`)
	 	) COMMENT='설문 정보 설정';

	 	CREATE TABLE `DATA_SURVEY` (
	 	  `data_survey_id` int(11) NOT NULL AUTO_INCREMENT,
	 	  `event_id` int(11) DEFAULT NULL COMMENT '그룹 아이디',
	 	  `user_id` varchar(150) DEFAULT NULL,
	 	  `name` varchar(150) DEFAULT NULL,
	 	  `tel` varchar(45) DEFAULT NULL,
	 	  `email` varchar(150) DEFAULT NULL,
	 	  `upload_path` varchar(255) DEFAULT NULL,
	 	  `comment1` varchar(255) DEFAULT NULL,
	 	  `comment2` varchar(255) DEFAULT NULL,
	 	  `comment3` varchar(255) DEFAULT NULL,
	 	  `exam_type` char(10) DEFAULT NULL,
	 	  `exam_score` varchar(255) DEFAULT NULL,
	 	  `friend_id` char(16) DEFAULT NULL,
	 	  `title` varchar(255) DEFAULT NULL,
	 	  `description` text,
	 	  `upload_path_exam` varchar(255) DEFAULT NULL,
	 	  `upload_path_user` varchar(255) DEFAULT NULL,
	 	  `is_temp` tinyint(1) DEFAULT NULL,
	 	  `quiz_step` smallint(3) DEFAULT '0',
	 	  `quiz_time` datetime DEFAULT CURRENT_TIMESTAMP,
	 	  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
	 	  PRIMARY KEY (`data_survey_id`)
	 	) COMMENT='설문 정보';
	 */
}