<?php
class M_teacher extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}

	public function get_list($options=array()){
		$this->db->order_by("order", "ASC");

		if (isset($options['yn_used'])) $this->db->where('yn_used', $options['yn_used']);
		
		$qurey = $this->db->get('TEACHER');
		return $qurey->result_array();
		
		$cursor->free_result();

		return $result;
	}
	//컨텐츠 수정
	public function update($id, $data){
		$this->db->where('id', $id);
		return $this->db->update('TEACHER', $data);
	}
	public function insert($data)
	{
		return $this->db->insert('TEACHER', $data);
	}
	/** data structure
	CREATE TABLE `TEACHER` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `teacher_id` int(11) DEFAULT NULL COMMENT '선생님 아이디',
	  `teacher_name` varchar(50) DEFAULT NULL COMMENT '선생님 이름',
	  `order` int(11) NOT NULL DEFAULT 100 COMMENT '정렬순서',
	  `img1_url` varchar(255) DEFAULT NULL COMMENT '1번 이미지: ',
	  `img1_link` varchar(255) DEFAULT NULL COMMENT '1번 이미지 링크: 메인',
	  `img1_link_type` int(4) DEFAULT 1 COMMENT '1번 이미지 속성 (1 link_url, 2 image_map)',
	  `img1_link_html` text DEFAULT '' COMMENT '1번 이미지 속성 참고하는 html',
	  `img1_link_target` tinyint(4) DEFAULT 0 COMMENT '1번 이미지 속성 타겟 (1: _blank,  0: self)',
	  `img2_url` varchar(255) DEFAULT NULL COMMENT '2번 이미지: 선생님 대표 이미지 - 수강신청',
	  `img2_link` varchar(255) DEFAULT NULL COMMENT '2번 이미지 링크: 메인',
	  `img2_link_type` int(4) DEFAULT 1 COMMENT '2번 이미지 속성 (1 link_url, 2 image_map)',
	  `img2_link_html` text DEFAULT '' COMMENT '2번 이미지 속성 참고하는 html',
	  `img2_link_target` tinyint(4) DEFAULT 0 COMMENT '2번 이미지 속성 타겟 (1: _blank,  0: self)',
	  `img3_url` varchar(255) DEFAULT NULL COMMENT '3번 이미지: 선생님 대표 이미지 - 수강신청',
	  `img3_link` varchar(255) DEFAULT NULL COMMENT '3번 이미지 링크: 메인',
	  `img3_link_type` int(4) DEFAULT 1 COMMENT '3번 이미지 속성 (1 link_url, 2 image_map)',
	  `img3_link_html` text DEFAULT '' COMMENT '3번 이미지 속성 참고하는 html',
	  `img3_link_target` tinyint(4) DEFAULT 0 COMMENT '3번 이미지 속성 타겟 (1: _blank,  0: self)',

	  `movie1_url` varchar(255) DEFAULT NULL COMMENT '1번 영상: ',
	  `movie2_url` varchar(255) DEFAULT NULL COMMENT '2번 영상: ',
	  `desc_main` text  COMMENT '설명: 메인',
	  `desc_motto` text COMMENT '설명: 좌우명',
	  `desc_spcialty` text  COMMENT '설명: 주특기',  
	  `desc_history` text  COMMENT '설명: 약력',  
	  `yn_used` tinyint(4) NOT NULL COMMENT '사용여부 (1: Y,  0: N)',
	  `yn_deleted` tinyint(4) NOT NULL COMMENT '삭제여부 (1: Y,  0: N)',
	  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
	  PRIMARY KEY (`id`)
	) COMMENT='선생님 테이블';
	 */
	//선생님 상세 리스트


	//선생님 상세 정보
	function get_info($teacher_id){
		$this->db->where("teacher_id", $teacher_id);
		
		$query = $this->db->get('TEACHER');
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}

	//선생님 상세 등록
	function teacher_detail_ins($data){
		$result = $this->db->insert('TEACHER', $data);
		return $result;
	}

	//선생님 상세 수정
	function teacher_detail_mod($data, $teacher_detail_id){
		$this->db->where('teacher_detail_id', $teacher_detail_id);
		$result = $this->db->update('TEACHER', $data);
		
		return $result;
	}

	// 선생님 리스트 (index:teacher_id)
    public function get_teacher_list_reg($config=array()) {

        if (isset($config['where_active_status'])) $this->db->where('active_status', $config['where_active_status']);
        if (isset($config['where_teacher_id'])) $this->db->where('teacher_id', $config['where_teacher_id']);
        if (isset($config['wherein_teacher_id'])) $this->db->where_in('teacher_id', $config['wherein_teacher_id']);
        if (isset($config['orderby_teacher_name'])) $this->db->order_by('teacher_name', $config['orderby_teacher_name']);
        
        $query = $this->db->get('TEACHER');
        $tmp = $query->result_array();
        $data = array();
        foreach ($tmp as $key => $item) {
            $data[$item['teacher_id']] = $item;
        }

        return $data;
    }
}