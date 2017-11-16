<?php
/**
 * 관련 테이블
 * COMMENT_CONFIG
 * COMMENT
 * COMMENT_REPLY
 */
class M_comment extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}

	/**
	 * COMMENT_CONFIG 리스트
	 */
	public function get_config_list($options=array())
	{
		$this->db->order_by("comment_config_id", "DESC");
		$this->db->from('COMMENT_CONFIG A');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_config($comment_config_id)
	{
		$this->db->select("*");
		$this->db->from('COMMENT_CONFIG');
		$this->db->where('comment_config_id',$comment_config_id);
		$query = $this->db->get();
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}
	//컨텐츠 수정
	public function update_config($comment_config_id, $data){

		$this->db->where('comment_config_id', $comment_config_id);
		return $this->db->update('COMMENT_CONFIG', $data);
	}
	//컨텐츠 입력
	public function insert_config($data)
	{
		return $this->db->insert('COMMENT_CONFIG', $data);
	}

	/**********************
	 * COMMENT 조회/수정
	 **********************/
	public function get_list($options=array())
	{
		if (!isset($options['comment_config_id'])) return array();
		if (!isset($options['offset'])) return array();
		if (!isset($options['limit'])) return array();

		$this->db->order_by("comment_id", "DESC");
		$this->db->from('COMMENT A');

		if (isset($options['author_id'])) $this->db->where('author_id',$options['author_id']);
		if (isset($options['type'])) $this->db->where('type',$options['type']);
		$this->db->where('comment_config_id',$options['comment_config_id']);
		$this->db->where('yn_deleted','0');
		$this->db->limit($options['limit'], $options['offset']);

		$query = $this->db->get();
		$data = $query->result_array();
		foreach ($data as $key => &$item) {
			$item['dt_created_o'] = new Datetime($item['dt_created']);
		}
		return $data;
	}
	public function get_list_total($options=array())
	{
		if (!isset($options['comment_config_id'])) return 0;

		$this->db->select("COUNT(*) as cnt");
		$this->db->from('COMMENT A');

		if (isset($options['author_id'])) $this->db->where('author_id',$options['author_id']);
		if (isset($options['type'])) $this->db->where('type',$options['type']);
		$this->db->where('comment_config_id',$options['comment_config_id']);
		$this->db->where('yn_deleted','0');

		$query = $this->db->get();
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function get($comment_id)
	{
		$this->db->select("*");
		$this->db->from('COMMENT');
		$this->db->where('comment_id',$comment_id);
		$query = $this->db->get();
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}

	//컨텐츠 수정
	public function update($comment_id, $data){

		$this->db->where('comment_id', $comment_id);
		return $this->db->update('COMMENT', $data);
	}
	//컨텐츠 입력
	public function insert($data)
	{
		return $this->db->insert('COMMENT', $data);
	}

	/****************************
	 * COMMENT_REPLY 조회/수정
	 ****************************/
	public function get_comments_replies($comment_ids)
	{
		if (sizeof($comment_ids) <  1) return array();

		$this->db->select("COMMENT_REPLY.*");
		$this->db->select("date_format(COMMENT_REPLY.dt_created, '%Y-%m-%d') as created_at", false);
		$this->db->from('COMMENT_REPLY');
		$this->db->where_in('COMMENT_REPLY.comment_id', $comment_ids);

		$this->db->where('COMMENT_REPLY.yn_deleted', 0);
		$this->db->order_by('dt_created', 'desc');
		$this->db->limit(100, 0);
		$query = $this->db->get();
		$data = $query->result_array();
		$result = array();
		foreach ($comment_ids as $key => $comment_id) {
			$result[$comment_id] = array();
		}
		foreach ($data as $key => $item) {
			$result[$item['comment_id']][] = $item;
		}

		return $result;
	}
	// 리스트
	public function get_reply_list($options=array())
	{
		if (!isset($options['comment_id'])) return array();

		$this->db->order_by("comment_reply_id", "DESC");
		$this->db->from('COMMENT_REPLY A');
		$this->db->where('comment_id',$options['comment_id']);

		$query = $this->db->get();
		return $query->result_array();
	}
	// 입력
	public function insert_reply($data)
	{
		return $this->db->insert('COMMENT_REPLY', $data);
	}

	/** data structure
		CREATE TABLE `COMMENT_CONFIG` (
		  `comment_config_id` int(11) NOT NULL AUTO_INCREMENT,
		  `desc_title` varchar(150) DEFAULT NULL COMMENT '설명: 제목',
		  `desc_note` text COMMENT '설명: 유의사항',
		  `yn_login` tinyint(1) NOT NULL DEFAULT 0 COMMENT '식별: 로그인 여부',
		  `dt_created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '생성 날자',
		  PRIMARY KEY (`comment_config_id`)
		)  COMMENT='한줄 코멘트 설정';

		CREATE TABLE `COMMENT` (
		  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `comment_config_id` bigint(20) DEFAULT null COMMENT '댓글 그룹id',
		  `type` bigint(20) NOT NULL DEFAULT '3' COMMENT '1: 공지, 2: 베스트, 3:일반',
		  `author_id` varchar(60) NOT NULL DEFAULT '' COMMENT '작성자 id',
		  `nickname` varchar(60) NOT NULL DEFAULT '' COMMENT '닉네임',
		  `ip_address` varchar(32) NOT NULL DEFAULT '' COMMENT 'ip address',
		  `yn_1` tinyint(4) NOT NULL DEFAULT 0 COMMENT '식별자1',
		  `yn_2` tinyint(4) NOT NULL DEFAULT 0 COMMENT '식별자2',
		  `yn_3` tinyint(4) NOT NULL DEFAULT 0 COMMENT '식별자3',
		  `desc_content` text NOT NULL DEFAULT '' COMMENT '설명: 내용',
		  `yn_deleted` tinyint(1) DEFAULT 0,
		  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`comment_id`),
		  KEY `idx_comment_config_id` (`comment_config_id`,`yn_deleted`)
		) COMMENT='한줄 코멘트';


		CREATE TABLE `COMMENT_REPLY` (
		  `comment_reply_id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `comment_id` bigint(20) DEFAULT NULL COMMENT '댓글 id',
		  `author_id` varchar(60) NOT NULL DEFAULT '' COMMENT '작성자 id',
		  `nickname` varchar(60) NOT NULL DEFAULT '' COMMENT '닉네임',
		  `ip_address` varchar(32) NOT NULL DEFAULT '' COMMENT 'ip address',
		  `desc_content` text NOT NULL DEFAULT '' COMMENT '설명: 내용',
		  `yn_deleted` tinyint(1) DEFAULT 0,
		  `dt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`comment_reply_id`),
		  KEY `idx_comment_id` (`comment_id`)
		) COMMENT='한줄 코멘트 - 댓글';

	 */

}