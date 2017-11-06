<?php
/**
 * 관련 테이블
 * Start
 */
class M_oneline extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}

	public function get($options=array())
	{
		# code...
	}
}