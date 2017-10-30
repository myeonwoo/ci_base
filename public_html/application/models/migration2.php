<?php

/**
 * 마이그레이션
	박세연
	SELECT * from b_board_article where board_id = 24 and teacher_id = 55 and del_yn = 'N'
	크리스틴 한:
	select * from b_board_article where board_id = 24 and teacher_id = 53 and del_yn = 'N'
	빅토리아 신
	select * from b_board_article where board_id = 24 and teacher_id = 54 and del_yn = 'N'
	최종훈
	select * from b_board_article where board_id = 24 and teacher_id = 56 and del_yn = 'N'
	에릭 최
	select * from b_board_article where board_id = 24 and teacher_id = 152 and del_yn = 'N'
	수 리: 
	select * from b_board_article where board_id = 24 and teacher_id = 166 and del_yn = 'N' 
	세라 원: 
	select * from b_board_article where board_id = 24 and teacher_id = 153 and del_yn = 'N' 
	신은미: 
	select * from b_board_article where board_id = 24 and teacher_id = 140 and del_yn = 'N' 
	신화식: 
	select * from b_board_article where board_id = 24 and teacher_id = 143 and del_yn = 'N' 
	민상홍: 
	select * from b_board_article where board_id = 24 and teacher_id = 168 and del_yn = 'N' 
	권민경: 
	select * from b_board_article where board_id = 24 and teacher_id = 127 and del_yn = 'N' 
	조나단 김: 
	select * from b_board_article where board_id = 24 and teacher_id = 154 and del_yn = 'N'
	
 *
*/
class Migration2 extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	// 토플 후기
	public function toefl_postscript(){
		$this->toefl_board_data = $this->load->database("toefl_board_data", TRUE);

		$sql = "SELECT * from b_board_article 
			where board_id = 24 
			and teacher_id in (55,53,54,56,152,166,153,140,143,168,127,154) and del_yn = 'N' 
			order by board_arti_id desc";
		$qry = $this->toefl_board_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}

	// 토스 후기
	public function tos_postscript(){
		$this->toefl_board_data = $this->load->database("toefl_board_data", TRUE);

		$sql = "SELECT * from b_board_article 
			where board_id = 47 
			and teacher_id in (13,101,24) and del_yn = 'N' 
			order by board_arti_id desc";
		$qry = $this->toefl_board_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}


	public function toefl_qna()
	{
		$this->toefl_board_data = $this->load->database("toefl_board_data", TRUE);

		$sql = "SELECT A.*
				, B.board_arti_id as comment_board_arti_id, B.cont as comment_cont
				, B.reg_name as comment_nickname, B.reg_dt as comment_reg_dt
				, B.reg_dt as comment_update, B.reg_ip as comment_ipaddress
			from b_board_article A
			left join b_board_memo B on A.board_arti_id = B.board_arti_id
			where A.board_id=16 and A.teacher_id in (53,54,55,56,140,143,153,168,127,152,152,154,166) and A.del_yn = 'N'
			-- and A.teacher_id = 55
			-- order by board_arti_id desc
			-- limit 1000
		";
		$qry = $this->toefl_board_data->query($sql);
		$result = $qry->result_array();

		$tmp = array();
		$board_arti_id = null;
		foreach ($result as $key => $item) {
			if (isset($tmp[$item['board_arti_id']])){
				if ($item['comment_board_arti_id']) {
					$tmp[$item['board_arti_id']]['comments'][] = array(
						'comment_board_arti_id' => $item['comment_board_arti_id']
						,'comment_cont' => $item['comment_cont']
						,'comment_nickname' => $item['comment_nickname']
						,'comment_reg_dt' => $item['comment_reg_dt']
						,'comment_update' => $item['comment_update']
						,'comment_ipaddress' => $item['comment_ipaddress']
					);
				}
			} else {
				$tmp[$item['board_arti_id']] = $item;
				$tmp[$item['board_arti_id']]['comments'] = array();
				if ($item['comment_board_arti_id']) {
					$tmp[$item['board_arti_id']]['comments'][] = array(
						'comment_board_arti_id' => $item['comment_board_arti_id']
						,'comment_cont' => $item['comment_cont']
						,'comment_nickname' => $item['comment_nickname']
						,'comment_reg_dt' => $item['comment_reg_dt']
						,'comment_update' => $item['comment_update']
						,'comment_ipaddress' => $item['comment_ipaddress']
					);
				}
			}
		}
		return $tmp;

		return $result;
	}

	public function tos_qna()
	{
		$this->toefl_board_data = $this->load->database("toefl_board_data", TRUE);

		$sql = "SELECT A.*
				, B.board_arti_id as comment_board_arti_id, B.cont as comment_cont
				, B.reg_name as comment_nickname, B.reg_dt as comment_reg_dt
				, B.reg_dt as comment_update, B.reg_ip as comment_ipaddress
			from b_board_article A
			left join b_board_memo B on A.board_arti_id = B.board_arti_id
			where A.board_id=39 and A.teacher_id in (13,24,101) and A.del_yn = 'N'
	-- and A.teacher_id = 55
	-- order by board_arti_id desc
	-- limit 1000
		";
		$qry = $this->toefl_board_data->query($sql);
		$result = $qry->result_array();

		$tmp = array();
		$board_arti_id = null;
		foreach ($result as $key => $item) {
			if (isset($tmp[$item['board_arti_id']])){
				if ($item['comment_board_arti_id']) {
					$tmp[$item['board_arti_id']]['comments'][] = array(
						'comment_board_arti_id' => $item['comment_board_arti_id']
						,'comment_cont' => $item['comment_cont']
						,'comment_nickname' => $item['comment_nickname']
						,'comment_reg_dt' => $item['comment_reg_dt']
						,'comment_update' => $item['comment_update']
						,'comment_ipaddress' => $item['comment_ipaddress']
					);
				}
			} else {
				$tmp[$item['board_arti_id']] = $item;
				$tmp[$item['board_arti_id']]['comments'] = array();
				if ($item['comment_board_arti_id']) {
					$tmp[$item['board_arti_id']]['comments'][] = array(
						'comment_board_arti_id' => $item['comment_board_arti_id']
						,'comment_cont' => $item['comment_cont']
						,'comment_nickname' => $item['comment_nickname']
						,'comment_reg_dt' => $item['comment_reg_dt']
						,'comment_update' => $item['comment_update']
						,'comment_ipaddress' => $item['comment_ipaddress']
					);
				}
			}
		}
		return $tmp;

		return $result;
	}
}