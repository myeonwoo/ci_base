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

	public function increment_config($data_survey_config_id)
    {
        $this->db->where('data_survey_config_id', $data_survey_config_id);
        $this->db->set('cnt_inserted', 'cnt_inserted+1', FALSE);
        return $this->db->update('DATA_SURVEY_CONFIG');
    }

    public function insert_data($data)
    {
        $this->db->insert('DATA_SURVEY', $data);
        return $this->db->insert_id();
    }
    public function update_data($data_survey_id, $data){
		$this->db->where('data_survey_id', $data_survey_id);
		return $this->db->update('DATA_SURVEY', $data);
	}
	public function get_data_list($data_survey_config_id, $options=array()){
		$this->db->where('data_survey_config_id', $data_survey_config_id);
		$query = $this->db->get('DATA_SURVEY');
		return $query->result_array();
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
	 	  `msg_onsubmit` varchar(150) DEFAULT '입력 완료되었습니다. 감사합니다' COMMENT '데이타 입력 성공시 출력 메세지',
	 	  `desc` text COMMENT '이벤트 설명',
	 	  `note` text COMMENT '유의사항',
	 	  PRIMARY KEY (`data_survey_config_id`)
	 	) COMMENT='설문 정보 설정';

	 	CREATE TABLE `DATA_SURVEY` (
	 	  `data_survey_id` int(11) NOT NULL AUTO_INCREMENT,
	 	  `data_survey_config_id` int(11) DEFAULT NULL COMMENT 'DATA_SURVEY_CONFIG 아이디',
	 	  `user_id` varchar(150) DEFAULT NULL COMMENT '아이디',
	 	  `name` varchar(150) DEFAULT NULL COMMENT '이름',
	 	  `tel` varchar(45) DEFAULT NULL COMMENT '전화번호',
	 	  `email` varchar(150) DEFAULT NULL COMMENT '이메일',
	 	  `upload_path` varchar(255) DEFAULT NULL COMMENT '파일경로',
	 	  `comment1` varchar(255) DEFAULT NULL COMMENT '코멘트 1',
	 	  `comment2` varchar(255) DEFAULT NULL COMMENT '코멘트 2',
	 	  `comment3` varchar(255) DEFAULT NULL COMMENT '코멘트 3',
	 	  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
	 	  PRIMARY KEY (`data_survey_id`)
	 	) COMMENT='설문 정보';
	 */
}