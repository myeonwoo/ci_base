<?php
class M_content extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}
	//컨텐츠 카테고리 리스트 전체
	public function get_category_list_all($config=array()){

		if (isset($config['yn_used'])) $this->db->where('yn_used', $config['yn_used']);

		$query = $this->db->get('CONTENT_CATEGORY');
		$data = $query->result_array();

		$return = array();
		foreach($data as $k => $v){
			$return[$v['content_category_id']] = $v;
		}
		
		return $return;
	}
	public function get_category($options=array()){

		// $this->db->select('A.*, B.subject as content_category_subject');
		// if (isset($options['content_id'])) $this->db->where('A.content_id', $options['content_id']);
		if (isset($options['subject'])) $this->db->where('subject', $options['subject']);
		$this->db->from('CONTENT_CATEGORY');

		$query = $this->db->get();

		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}

		return $data;
	}
	public function add_category($data)
	{
		return $this->db->insert('CONTENT_CATEGORY', $data);
	}



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
	//컨텐츠 리스트
	public function get_list($options=array()){
		$now = date('Y-m-d H:i:s');

		if (isset($options['yn_used'])) $this->db->where('yn_used', $options['yn_used']);
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

		return $data;
	}
	//컨텐츠 수정
	public function update($content_id, $data){

		$this->db->where('content_id', $content_id);
		return $this->db->update('CONTENT', $data);
	}
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
	  `desc_main` text  COMMENT '설명: 메인',
	  `img1_url` varchar(255) DEFAULT NULL COMMENT '1번 이미지',
	  `img1_link` varchar(255) DEFAULT NULL COMMENT '1번 이미지 링크: 메인',
	  `img1_link_type` int(4) DEFAULT 1 COMMENT '1번 이미지 속성 (1 link_url, 2 image_map)',
	  `img1_link_html` text DEFAULT '' COMMENT '1번 이미지 속성 참고하는 html',
	  `img1_link_target` tinyint(4) DEFAULT 0 COMMENT '1번 이미지 속성 타겟 (1: _blank,  0: self)',
	  `img2_url` varchar(255) DEFAULT NULL COMMENT '이미지: 추가1',
	  `img2_link` varchar(255) DEFAULT NULL COMMENT '2번 이미지 링크: 메인',
	  `img2_link_type` int(4) DEFAULT 1 COMMENT '2번 이미지 속성 (1 link_url, 2 image_map)',
	  `img2_link_html` text DEFAULT '' COMMENT '2번 이미지 속성 참고하는 html',
	  `img2_link_target` tinyint(4) DEFAULT 0 COMMENT '2번 이미지 속성 타겟 (1: _blank,  0: self)',
	  `img3_url` varchar(255) DEFAULT NULL COMMENT '이미지: 추가3',
	  `img3_link` varchar(255) DEFAULT NULL COMMENT '3번 이미지 링크: 메인',
	  `img3_link_type` int(4) DEFAULT 1 COMMENT '3번 이미지 속성 (1 link_url, 2 image_map)',
	  `img3_link_html` text DEFAULT '' COMMENT '3번 이미지 속성 참고하는 html',
	  `img3_link_target` tinyint(4) DEFAULT 0 COMMENT '3번 이미지 속성 타겟 (1: _blank,  0: self)',
	  `order` int(11) NOT NULL DEFAULT 100 COMMENT '정렬순서',
	  `yn_used` tinyint(4) NOT NULL COMMENT '사용여부 (1: Y,  0: N)',
	  `yn_deleted` tinyint(4) NOT NULL COMMENT '삭제여부 (1: Y,  0: N)',
	  `dt_dday` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
	  `dt_start` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
	  `dt_end` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
	  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
	  PRIMARY KEY (`content_id`)
	) COMMENT='여러 컨텐츠 관련 테이블';

	 */








	public function get_list_del($category_id=null, $sub_category_id=null,$type=null, $config=array()){
		$now = date('Y-m-d H:i:s');

		$this->db->select('BNN.*');
		$this->db->from('BANNER AS BNN');
		$this->db->join('BANNER_CATEGORY AS BCG','BNN.sub_category_id = BCG.category_id','inner');
		if($category_id)  $this->db->where('BNN.category_id',$category_id);
		if($sub_category_id) $this->db->where('BNN.sub_category_id',$sub_category_id);
		if($type) $this->db->where('BCG.category_type',$type);
		
		$this->db->where(" (BNN.deleted_time IS  NULL or BNN.deleted_time = '0000-00-00 00:00:00') ", NULL, FALSE );
		$this->db->where("BNN.end_time >= date_add(now(), interval -15 day) ", NULL, FALSE);
		
		if (isset($config['use_event_period'])) {
			$this->db->where('BNN.start_time <',$now);
			$this->db->where('BNN.end_time >',$now);
		}
		if (isset($config['use_yn'])) $this->db->where('BNN.use_yn', 'Y');

		$this->db->order_by("BNN.display_order", "ASC");

		$cursor = $this->db->get();
		
		$return = $cursor->result_array();
		
		return $return;
	}

	/****
		* @Desc 	가장 최근 배너 
		* @param	
	*****/
	public function get_latest($sub_category_id)
	{
		$now = date('Y-m-d H:i:s');

		$this->db->where('sub_category_id', $sub_category_id);
		$this->db->where('use_yn', 'Y');
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

	

	//배너 정보
	function get_info($banner_id){
		$this->db->select("*, SUBSTRING_INDEX(`d_day_color`,'_', 1 ) AS d_day_color1,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 2 ) ,'_', -1 ) AS d_day_color2,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 3 ) ,'_', -1 ) AS d_day_color3,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 4 ) ,'_', -1 ) AS d_day_color4,
								SUBSTRING_INDEX(`d_day_color`,'_', -1 ) AS d_day_opacity,
								SUBSTRING_INDEX(`d_day_position`,'_', 1 ) AS d_day_left,
								SUBSTRING_INDEX(`d_day_position`,'_', -1 ) AS d_day_top ", FALSE);
		$this->db->where('banner_id =', $banner_id);
		$cursor = $this->db->get('BANNER');
		
		$return = $cursor->row_array();
		
		return $return;
	}


	//배너 저장
	function banner_ins($data){
		$return = $this->db->insert('BANNER', $data);

		return $return;
		
	}
	
	//배너 수정
	function banner_mod($data, $banner_id){
	
		$this->db->where('banner_id', $banner_id);
		$return = $this->db->update('BANNER', $data);
		
		return $return;
	}
	

	//배너 리스트 - 서비스 페이지 사용
	function banner_list($category_id, $sub_category_id=null){
		$this->db->select("*, SUBSTRING_INDEX(`d_day_color`,'_', 1 ) AS d_day_color1,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 2 ) ,'_', -1 ) AS d_day_color2,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 3 ) ,'_', -1 ) AS d_day_color3,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 4 ) ,'_', -1 ) AS d_day_color4,
								SUBSTRING_INDEX(`d_day_color`,'_', -1 ) AS d_day_opacity,
								SUBSTRING_INDEX(`d_day_position`,'_', 1 ) AS d_day_left,
								SUBSTRING_INDEX(`d_day_position`,'_', -1 ) AS d_day_top ", FALSE);
		if($category_id)  $this->db->where('category_id', $category_id);
		if($sub_category_id) $this->db->where('sub_category_id', $sub_category_id);
		
		$this->db->where(" (deleted_time IS  NULL or deleted_time = '0000-00-00 00:00:00') ", NULL, FALSE );
		$this->db->where("use_yn", "Y");
		$this->db->where("popup_yn", "N");	//팝업 제외 

		//$this->db->where("end_time >= date_add(now(), interval -15 day) ", NULL, FALSE);
		$this->db->where("start_time <= now()", NULL, FALSE);
		$this->db->where("end_time >= now()", NULL, FALSE);

		$this->db->order_by("category_id", "ASC");
		$this->db->order_by("sub_category_id", "ASC");
		$this->db->order_by("display_order", "ASC");

		$cursor = $this->db->get('BANNER');
		
		$return = $cursor->result_array();
		
		return $return;
	}

	/****
		* @Desc 	메인 페이지 팝업
		* @param	
	*****/
	public function main_popup()
	{
		/*$sql = "SELECT * 
			FROM china_dangicokr.BANNER where category_id = 1 and popup_yn = 'Y' and use_yn='Y'
			order by banner_id desc limit 1
		";*/

		$now = date('Y-m-d H:i:s');

		$this->db->where('category_id', '1');
		$this->db->where('sub_category_id', '83');
		$this->db->where('use_yn', 'Y');
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