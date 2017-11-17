<?php
/**
 * 관련 테이블
 * CONTENT_CATEGORY
 * CONTENT
 */
class M_content extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}
	//컨텐츠 카테고리 리스트 전체
	public function get_category_list_all($options=array()){

		if (isset($options['yn_used'])) $this->db->where('yn_used', $options['yn_used']);

		$query = $this->db->get('CONTENT_CATEGORY');
		$data = $query->result_array();

		$return = array();
		foreach($data as $k => $v){
			$return[$v['content_category_id']] = $v;
		}
		
		return $return;
	}
	public function get_category($options=array()){
		if (isset($options['subject'])) $this->db->where('subject', $options['subject']);
		$this->db->from('CONTENT_CATEGORY');

		$query = $this->db->get();

		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}
	// 분류값 추가
	public function add_category($data)
	{
		return $this->db->insert('CONTENT_CATEGORY', $data);
	}

	// 컨텐츠 조회
	public function get($content_id){
		$now = date('Y-m-d H:i:s');

		$this->db->select('A.*, B.subject as content_category_subject');
		$this->db->where('content_id',$content_id);
		$this->db->from('CONTENT A');
		$this->db->join('CONTENT_CATEGORY B', 'A.content_category_id = B.content_category_id');

		$query = $this->db->get();

		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}

		return $data;
	}
	public function get_content($content_id){
		$this->db->select('A.*');
		$this->db->where('content_id',$content_id);
		$this->db->from('CONTENT A');

		$query = $this->db->get();

		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}

		return $data;
	}
	//컨텐츠 리스트
	public function get_list($options=array()){
		$now = date('Y-m-d H:i:s');

		if (isset($options['url_main_page'])) $this->db->where('A.url_main_page', $options['url_main_page']);
		if (isset($options['yn_used'])) $this->db->where('A.yn_used', $options['yn_used']);
		if (isset($options['content_category_id'])) $this->db->where('A.content_category_id', $options['content_category_id']);
		if (isset($options['in_content_category_id']) && sizeof($options['in_content_category_id'])>0) $this->db->where_in('A.content_category_id', $options['in_content_category_id']);
		if (isset($options['like_subject'])) $this->db->like('subject', $options['like_subject']);
		if (isset($options['yn_deleted'])) $this->db->where('yn_deleted', $options['yn_deleted']);

		if (isset($options['dt_start'])) $this->db->where('dt_start <', $options['dt_start']); 
		if (isset($options['dt_end'])) $this->db->where('dt_end >', $options['dt_end']); 
		$this->db->select('A.*, B.subject as content_category_subject');
		$this->db->from('CONTENT A');
		$this->db->join('CONTENT_CATEGORY B', 'A.content_category_id = B.content_category_id');
		// $this->db->order_by("A.content_id", "DESC");

		$query = $this->db->get();
		// $query = $this->db->get('CONTENT');
		$data = $query->result_array();

		return $data;
	}
	public function get_list_groupby_content_category_id($options=array()){
		$now = date('Y-m-d H:i:s');

		if (isset($options['url_main_page'])) $this->db->where('A.url_main_page', $options['url_main_page']);
		if (isset($options['yn_used'])) $this->db->where('A.yn_used', $options['yn_used']);
		if (isset($options['content_category_id'])) $this->db->where('A.content_category_id', $options['content_category_id']);
		if (isset($options['in_content_category_id']) && sizeof($options['in_content_category_id'])>0) $this->db->where_in('A.content_category_id', $options['in_content_category_id']);
		if (isset($options['like_subject'])) $this->db->like('subject', $options['like_subject']);
		if (isset($options['yn_deleted'])) $this->db->where('yn_deleted', $options['yn_deleted']);

		if (isset($options['dt_start'])) $this->db->where('dt_start <', $options['dt_start']); 
		if (isset($options['dt_end'])) $this->db->where('dt_end >', $options['dt_end']); 
		$this->db->select('A.*, B.subject as content_category_subject');
		$this->db->from('CONTENT A');
		$this->db->join('CONTENT_CATEGORY B', 'A.content_category_id = B.content_category_id');

		$query = $this->db->get();
		// $query = $this->db->get('CONTENT');
		$data = $query->result_array();
		$tmp = array();
		foreach ($data as $key => $item) {
			$tmp[$item['content_category_id']][] = $item;
		}

		return $tmp;
	}
	//컨텐츠 수정
	public function update($content_id, $data){

		$this->db->where('content_id', $content_id);
		return $this->db->update('CONTENT', $data);
	}
	//컨텐츠 입력
	public function insert($data)
	{
		return $this->db->insert('CONTENT', $data);
	}

	/** data structure
		CREATE TABLE `CONTENT_CATEGORY` (
		  `content_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `parent_id` int(11) DEFAULT NULL COMMENT '부모 content_category_id',
		  `order` int(10) DEFAULT NULL COMMENT '정렬순서',
		  `subject` varchar(150) NOT NULL DEFAULT '',
		  `yn_used` tinyint(4) NOT NULL COMMENT '사용여부 (1: Y,  0: N)',
		  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
		  PRIMARY KEY (`content_category_id`)
		) COMMENT='여러 컨텐츠 카테고리 관련 테이블';

		CREATE TABLE `CONTENT` (
		  `content_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `content_category_id` int(11) NOT NULL COMMENT '카테고리 아이디',
		  `teacher_id` int(11) DEFAULT NULL COMMENT '선생님 아이디',
		  `subject` varchar(255) DEFAULT '미정' COMMENT '제목',
		  `position_x` int(11) DEFAULT 0 COMMENT '컨텐츠 위치 (x)',
		  `position_y` int(11) DEFAULT 0 COMMENT '컨텐츠 위치 (y)',
		  `position_el_selector` varchar(255) NOT NULL DEFAULT '' COMMENT '컨텐츠 위치: element selector (ID, class, element 혼용)',
		  `desc_main` text COMMENT '설명: 메인',
		  `desc_intro` text COMMENT '설명: 소개',
		  `url_main_page` varchar(255) NOT NULL DEFAULT '' COMMENT '주용되는 페이지 주소',
		  `img1_url` varchar(255) DEFAULT NULL COMMENT '1번 이미지',
		  `img1_link` varchar(255) DEFAULT NULL COMMENT '1번 이미지 링크: 메인',
		  `img1_link_type` int(4) DEFAULT '1' COMMENT '1번 이미지 속성 (1 link_url, 2 image_map)',
		  `img1_link_html` text COMMENT '1번 이미지 속성 참고하는 html',
		  `img1_link_target` tinyint(4) DEFAULT '0' COMMENT '1번 이미지 속성 타겟 (1: _blank,  0: self)',
		  `img1_width` int(11) NOT NULL DEFAULT '100' COMMENT '1번 이미지 넓이',
		  `img2_url` varchar(255) DEFAULT NULL COMMENT '이미지: 추가1',
		  `img2_link` varchar(255) DEFAULT NULL COMMENT '2번 이미지 링크: 메인',
		  `img2_link_type` int(4) DEFAULT '1' COMMENT '2번 이미지 속성 (1 link_url, 2 image_map)',
		  `img2_link_html` text COMMENT '2번 이미지 속성 참고하는 html',
		  `img2_link_target` tinyint(4) DEFAULT '0' COMMENT '2번 이미지 속성 타겟 (1: _blank,  0: self)',
		  `img2_width` int(11) NOT NULL DEFAULT '100' COMMENT '2번 이미지 넓이',
		  `img3_url` varchar(255) DEFAULT NULL COMMENT '이미지: 추가3',
		  `img3_link` varchar(255) DEFAULT NULL COMMENT '3번 이미지 링크: 메인',
		  `img3_link_type` int(4) DEFAULT '1' COMMENT '3번 이미지 속성 (1 link_url, 2 image_map)',
		  `img3_link_html` text COMMENT '3번 이미지 속성 참고하는 html',
		  `img3_link_target` tinyint(4) DEFAULT '0' COMMENT '3번 이미지 속성 타겟 (1: _blank,  0: self)',
		  `img3_width` int(11) NOT NULL DEFAULT '100' COMMENT '3번 이미지 넓이',
		  `movie1_key` varchar(255) DEFAULT '' COMMENT '1번 무료 영상 관련 키',
		  `movie1_url` varchar(500) DEFAULT '' COMMENT '1번 무료 영상 url',
		  `cnt_view` int(11) DEFAULT '0' COMMENT '1번 무료 영상 관련 키',
		  `order` int(11) NOT NULL DEFAULT '100' COMMENT '정렬순서',
		  `yn_used` tinyint(4) NOT NULL COMMENT '사용여부 (1: Y,  0: N)',
		  `yn_deleted` tinyint(4) NOT NULL COMMENT '삭제여부 (1: Y,  0: N)',
		  `dt_dday` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
		  `dt_start` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
		  `dt_end` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
		  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
		  PRIMARY KEY (`content_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1344 DEFAULT CHARSET=utf8 COMMENT='여러 컨텐츠 관련 테이블';

		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('0', '10', '일반 배너', '1', now());
		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('0', '10', '플로팅 배너', '1', now());
		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('0', '10', '유의사항', '1', now());
		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('0', '10', '학습자료', '1', now());
		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('4', '10', '무료자료', '1', now());
		INSERT INTO `global_dangicokr`.`CONTENT_CATEGORY` (`parent_id`, `order`, `subject`, `yn_used`, `dt_created`) VALUES ('4', '10', '이벤트자료', '1', now());
	 */

	/****
		* @Desc 	가장 최근 배너 
		* @param	
	*****/
	public function get_latest($content_category_id)
	{
		$now = date('Y-m-d H:i:s');

		$this->db->where('content_category_id', $content_category_id);
		$this->db->where('yn_used', 'Y');
		$this->db->where('deleted_time', null);
		$this->db->where('start_time <',$now);
		$this->db->where('end_time >',$now);

		$this->db->order_by("banner_id", "DESC");
		$this->db->limit(1);
		$query = $this->db->get('BANNER');
		
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}
}