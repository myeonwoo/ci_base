<?php

/**
 * 마이그레이션
 -- 남미숙 
select * from nanna_b2014100717122594 order by no desc; -- 공지
select * from nanna_b2014100717140254 order by no desc; -- 후기
b2014100717122594	b2014100717130993	b2014100717140254	b2014100717143380
b2014100717122594	b2014100717130993	b2014100717140254	b2014100717143380
;
-- 강현주
select * from nanna_b2010111119495344 order by no desc; -- 공지
select * from nanna_b2010111521130607 order by no desc; -- 후기
b2010111119495344	b2010111521132272	b2010111521130607	b2013080718285351
;
-- 김수현 
select * from nanna_b2014061614460484 order by no desc; -- 공지
select * from nanna_b2014061614473652 order by no desc; -- 후기
b2014061614460484	b2014061614465475	b2014061614473652	b2014061614481047

 *
*/
class Migration extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_data = $this->load->database("china_data", TRUE);
	}

	public function get_test(){
		$sql = "SELECT * FROM nanna_b2014100717122594 limit 10";

		$qry = $this->china_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}

	// 남미숙 후기
	public function nam_postscript(){
		$sql = "SELECT * FROM nanna_b2014100717140254";
		$qry = $this->china_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}
	// 강현주 후기
	public function kang_postscript(){
		$sql = "SELECT * FROM nanna_b2010111521130607 limit 0, 5000";
		$sql = "SELECT * FROM nanna_b2010111521130607 where wdate>'2012-12-12'";
		$qry = $this->china_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}
	// 김수현 후기
	public function kim_postscript(){
		$sql = "SELECT * FROM nanna_b2014061614473652";
		$qry = $this->china_data->query($sql);
		$result = $qry->result_array();

		return $result;
	}

}



