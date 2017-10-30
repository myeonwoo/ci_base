<?php
class M_landing_adm extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->new_china = $this->load->database("new_china", TRUE);
	}

	//랜딩 정보
	function landing_total_num($search_type=null, $search_value = null){
		$this->new_china->select("count(lan_id) AS total_num");
		if($search_value) $this->new_china->or_like("title", $search_value);
		if($search_value) $this->new_china->or_like("url", $search_value);
		$study_list = $this->new_china->get("LANDING_ADM");
		
		return $study_list->row_array();
	}
	
	function landing_list_f($where=null){
		
		$sql = "SELECT * 
					FROM (
						SELECT 
						  lad.lan_id,
						  lad.url,
						  lad.title,
						  lad.use_yn,
						  lno.notice_id AS no_ba_id,
						  lno.class_name,
						  lno.notice_name,
						  lno.notice_txt,  
						  '' AS img_map,
						  '' AS link,
						  '' AS top_area,
						  '' AS left_area,
						  lno.start_time,
						  lno.end_time,
						  'n' AS info_type 
						FROM LANDING_ADM AS lad 
						INNER JOIN LANDING_NOTICE AS lno ON lad.`lan_id` = lno.`lan_id` 
						UNION 
						SELECT
						  lad.lan_id,
						  lad.url,
						  lad.title,
						  lad.use_yn,
						  lba.banner_id AS no_ba_id,
						  lba.class_name,
						  lba.banner_name,
						  lba.file_url,
						  lba.img_map,
						  lba.link,
						  lba.top_area,
						  lba.left_area,
						  lba.start_time,
						  lba.end_time,
						  'b' AS info_type 
						FROM LANDING_ADM AS lad 
						INNER JOIN LANDING_BANNER AS lba ON lad.`lan_id` = lba.`lan_id`
						) AS landing_info";
		if(count($where)>0){
			$sql .= " WHERE ";
			foreach ($where as $key=>$rs){
				if($key > 0) $sql .= " AND ";
				if(in_array($key,array("title"))){
					$sql .= $key."like('%$rs%')";
				}else if($key == "time"){
					$sql .= "start_time < NOW() AND end_time > NOW()";
				}else{
					$sql .= $key."='$rs'";
				}
			}
		}	
		
		$study_list = $this->new_china->query($sql);
		return $study_list->result_array();
	}
	
	function landing_list($offset=null,$limit=null,$search_type=null,$search_value=null,$lan_id=null){
		$this->new_china->select("lad.lan_id, lad.url, lad.title, lad.use_yn");
	
		if($lan_id) $this->new_china->where("lan_id",$lan_id);
		if($search_value) $this->new_china->or_like("lad.url", $search_value);
		if($offset) $this->new_china->limit($limit, $offset);
		
		$this->new_china->where("use_yn","Y");
		$this->new_china->order_by("lad.lan_id","desc");
		$landing_list = $this->new_china->get("LANDING_ADM AS lad");
		$landing_list = $landing_list->result_array();

		if($lan_id){
			$this->new_china->select("lno.notice_id AS no_ba_id, lno.class_name, lno.notice_name, lno.notice_txt, '' AS img_map, '' AS link, '' AS top_area, '' AS left_area, lno.start_time, lno.end_time, 'n' AS info_type",false);
			$this->new_china->where('lan_id',$lan_id);
			$notice_list = $this->new_china->get('LANDING_NOTICE AS lno');
			$notice_list = $notice_list->result_array();
			
			$this->new_china->select("lba.banner_id AS no_ba_id, lba.class_name, lba.banner_name, lba.file_url, lba.img_map, lba.link, lba.top_area, lba.left_area, lba.start_time, lba.end_time, 'b' AS info_type",false);
			$this->new_china->where('lan_id',$lan_id);
			$banner_list = $this->new_china->get('LANDING_BANNER AS lba');
			$banner_list = $banner_list->result_array();
		}
		$landing_list_info =array(
											"landing_list" => $landing_list,
											"notice_list" => $notice_list,
											"banner_list" => $banner_list,
									);
		return $landing_list_info;
		
	}
	
	function landing_ins($title, $url, $use_yn, $user_id){
		
		$this->new_china->where("url",$url);
		$landing_list = $this->new_china->get("LANDING_ADM");
		$landing_list = $landing_list->row_array();
		
		if(count($landing_list)>0){
			alert('이미 등록 된 URL 입니다.','/adm/landing_adm/main/apply_view?lan_id='.$landing_list['lan_id']);
			exit;
		}
		
		$this->new_china->set("title",$title);
		$this->new_china->set("url",$url);
		$this->new_china->set("use_yn",$use_yn);
		$this->new_china->set("create_id",$user_id);
		$this->new_china->set("create_time","NOW()",false);
		$this->new_china->insert("LANDING_ADM");
		
		return $this->new_china->insert_id();
	}
	
	function banner_ins($lan_id, $banner_name, $class_name, $file_url, $start_time, $end_time, $link=null, $img_map=null){
		$this->new_china->set("lan_id",$lan_id);
		$this->new_china->set("banner_name",$banner_name);
		$this->new_china->set("class_name",$class_name);
		$this->new_china->set("file_url",$file_url);
		$this->new_china->set("start_time",$start_time);
		$this->new_china->set("end_time",$end_time);
		if($link) $this->new_china->set("link",$link);
		if($img_map) $this->new_china->set("img_map",$img_map);
		$this->new_china->insert("LANDING_BANNER");
		
		return $this->new_china->insert_id();
	}
	
	function notice_ins($lan_id, $notice_name, $notice_class_name, $notice_txt, $notice_start_time, $notice_end_time){
		$this->new_china->set("lan_id",$lan_id);
		$this->new_china->set("start_time",$notice_start_time);
		$this->new_china->set("end_time",$notice_end_time);
		$this->new_china->set("class_name",$notice_class_name);
		$this->new_china->set("notice_txt",$notice_txt);
		$this->new_china->set("notice_name",$notice_name);
		$this->new_china->insert("LANDING_NOTICE");
		
		return $this->new_china->insert_id();
	}
	
	function landing_upd($lan_id, $title, $url, $use_yn, $user_id){
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->set("title",$title);
		$this->new_china->set("url",$url);
		$this->new_china->set("use_yn",$use_yn);
		$this->new_china->set("update_id",$user_id);
		$this->new_china->set("update_time","NOW()",false);
		return $this->new_china->update("LANDING_ADM");		
	}
	
	function landing_del($lan_id){
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->delete("LANDING_BANNER");
		
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->delete("LANDING_NOTICE");
		
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->delete("LANDING_ADM");
	}
	
	function banner_notice_del($lan_id){
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->delete("LANDING_NOTICE");
		
		$this->new_china->where("lan_id",$lan_id);
		$this->new_china->delete("LANDING_BANNER");
	}
}	