<?php
class M_landing extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->new_china = $this->load->database("new_china", TRUE);
	}
		
	function landing_info($url){
		if($url){
			$this->new_china->select("lad.lan_id");	
			$this->new_china->where("use_yn","Y");	
			$this->new_china->where("url",$url);	
			$landing_list = $this->new_china->get("LANDING_ADM AS lad");
			$landing_list = $landing_list->row_array();
			
			if(count($landing_list)>0){
				$this->new_china->select("lno.notice_id AS no_ba_id, lno.class_name, lno.notice_name, lno.notice_txt, '' AS img_map, '' AS link, '' AS top_area, '' AS left_area, lno.start_time, lno.end_time, 'n' AS info_type",false);
				$this->new_china->where('start_time <', 'NOW()', false);
				$this->new_china->where('end_time >', 'NOW()', false);
				$this->new_china->where('lan_id',$landing_list['lan_id']);
				$notice_list = $this->new_china->get('LANDING_NOTICE AS lno');
				$notice_list = $notice_list->result_array();
				
				$this->new_china->select("lba.banner_id AS no_ba_id, lba.class_name, lba.banner_name, lba.file_url, lba.img_map, lba.link, lba.top_area, lba.left_area, lba.start_time, lba.end_time, 'b' AS info_type",false);
				$this->new_china->where('start_time <', 'NOW()', false);
				$this->new_china->where('end_time >', 'NOW()', false);
				$this->new_china->where('lan_id',$landing_list['lan_id']);
				$banner_list = $this->new_china->get('LANDING_BANNER AS lba');
				$banner_list = $banner_list->result_array();
			}
			$landing_list_info =array(
												"notice_list" => $notice_list,
												"banner_list" => $banner_list,
										);
			return $landing_list_info;		
		}
	}
}	