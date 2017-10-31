<?php


class M_banner_output extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);

		$this->load->driver('cache');
        $this->cache_enabled = (_IS_DEV_QA) ? false : true;

	}

	function get_banner_subcategory_list($banner_category_id){
		$this->china_db->where('parent_category_id =', $banner_category_id);
		$this->china_db->where('use_yn =', '1');
		$this->china_db->order_by("order", "ASC");
		
		$query = $this->china_db->get('BANNER_CATEGORY');
		
		$data = $query->result_array();

		return $data;
		
	}

	function get_output_list($category_id){
		
		$cacheKey = $this->cache->memcached->get_cache_key(__CLASS__, __FUNCTION__, func_get_args());
        if ($this->cache_enabled) $return = $this->cache->memcached->get($cacheKey);

	        if (!$return) {

			$this->china_db->where(" (deleted_time IS  NULL or deleted_time = '0000-00-00 00:00:00') ", NULL, FALSE );

			$this->china_db->where("use_yn =",'Y');
			$this->china_db->where("start_time <= now()", NULL, FALSE);
			$this->china_db->where("end_time >=  now()", NULL, FALSE);
			$this->china_db->where('category_id =',$category_id);
			//$this->china_db->or_where('deleted_time =', "0000-00-00 00:00:00");
			$this->china_db->order_by("category_id", "ASC");
			$this->china_db->order_by("display_order", "ASC");
			$query = $this->china_db->get('BANNER');
			
			$data = $query->result_array();
					
			if($data){
				foreach($data as $k => $v){
					$return[$v['sub_category_id']][$v['banner_id']]['subject'] = $v['subject'];
					$return[$v['sub_category_id']][$v['banner_id']]['banner_id'] = $v['banner_id'];
					$return[$v['sub_category_id']][$v['banner_id']]['content'] = $v['content'];
					$return[$v['sub_category_id']][$v['banner_id']]['img_url'] = $v['img_url'];
					$return[$v['sub_category_id']][$v['banner_id']]['link_url'] = $v['link_url'];
					$return[$v['sub_category_id']][$v['banner_id']]['display_order'] = $v['display_order'];
					$return[$v['sub_category_id']][$v['banner_id']]['target'] = $v['target'];
					$return[$v['sub_category_id']][$v['banner_id']]['link_type'] = $v['link_type'];
					$return[$v['sub_category_id']][$v['banner_id']]['image_map'] = $v['image_map'];
					$return[$v['sub_category_id']][$v['banner_id']]['color'] = $v['color'];
					$return[$v['sub_category_id']][$v['banner_id']]['width'] = $v['width'];
					
					$return[$v['sub_category_id']][$v['banner_id']]['linebanner_style'] = $v['linebanner_style'];
					$return[$v['sub_category_id']][$v['banner_id']]['linebanner_date'] = $v['linebanner_date'];
				}
			}else{
				$return = array();
			}
			$this->cache->memcached->save($cacheKey, $return, 1 * 60);
		}
		return $return;
	}

	

	
}



?>
