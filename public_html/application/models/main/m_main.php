<?php
/****
 * @Desc     메인 페이지에 사용되어지는 데이타
 * @param
 * @Table
 * @Author    mw.lim
 *****/
class M_main extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->china_db = $this->load->database("new_china", TRUE);

		$this->load->library('validate');

		$this->load->driver('cache');
		$this->cache_enabled = (_IS_DEV_QA) ? false : true;
	}

	/****
	 * @Desc     메인 - 중단기에서 사용되는 데이타-> 나중에 삭제! 
	 * @param
	 * @Table
	 * @Author    kwonohya
	 *****/
	public function china_main_dataset($config = array())
	{
		$cacheKey = $this->cache->memcached->get_cache_key('chinadangi'.__CLASS__, __FUNCTION__, func_get_args());

		if ($this->cache_enabled) $data = $this->cache->memcached->get($cacheKey);
		if(!$data){
			$CI =& get_instance();
			if (!$CI->bbs_v3 ) $CI->load->model('bbs/bbs_v3');
			

			$banner_ref = array(
					168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,198,207,212
			);

			$data = array();
			
			
			$data['banners'] = $this->get_banners_by_sub_category_id($banner_ref);
			$data['main_popup'] = $this-> main_popup();
			

			$this->cache->memcached->save($cacheKey, $data, 1 * 60);
		}

		return $data;
	}

	/****
	 * @Desc     메인 - 중단기에서 사용되는 데이타(메인개편 v4용) > 2017.5.17
	 * @param
	 * @Table
	 * @Author    kwonohya
	 *****/
	public function china_main_dataset_v4($config = array())
	{
		$cacheKey = $this->cache->memcached->get_cache_key('chinadangi'.__CLASS__, __FUNCTION__, func_get_args());
	
		if ($this->cache_enabled) $data = $this->cache->memcached->get($cacheKey);
		if(!$data){
			$CI =& get_instance();
			if (!$CI->bbs_v3 ) $CI->load->model('bbs/bbs_v3');
				
			$config = array(
					'mid' => 'china_teacher_review',
					'search_priority' => 2,
					'limit' => 10
			);
			
			$best_review_list = $CI->bbs_v3->document_lists($config);
			$banner_ref = array(
					222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,245,246,247
			);
	
			
			$data = array();
			$data['top6_basic'] = $this->get_top6('BASIC');
			$data['top6_hsk'] = $this->get_top6('HSK');
			$data['best_review_list'] = $best_review_list;
			$data['banners'] = $this->get_banners_by_sub_category_id($banner_ref);
			$data['main_popup'] = $this-> main_popup();
				
	
			$this->cache->memcached->save($cacheKey, $data, 1 * 60);
		}
	
		return $data;
	}
	
	//인기강좌 TOP6가져오는 부분
	public function get_top6($category)
	{
		$this->china_db->where("category", $category);
		$this->china_db->order_by("order", "ASC");
		$query = $this->china_db->get('DATA_INSTANT');
	
		return $query->result_array();
	}
	
	function get_banners_by_sub_category_id($a_sub_category_id = array()){
		$now = date('Y-m-d H:i:s');
			
		$this->china_db->where_in('sub_category_id', $a_sub_category_id);
		$this->china_db->where('use_yn', 'Y');
		$this->china_db->where('deleted_time', null);
		$this->china_db->where('start_time <',$now);
		$this->china_db->where('end_time >',$now);
			
		$this->china_db->order_by("display_order", "ASC");
		$query = $this->china_db->get('BANNER');

        $data = array();
        foreach ($a_sub_category_id as $key => $sub_category_id) {
            $data[$sub_category_id] = array();
        }
        $tmp = $query->result_array();
        foreach ($tmp as $key => $item) {
        	$item['created_time_dt'] = new Datetime($item['created_time']);
        	$item['end_time_dt'] = new Datetime($item['end_time']);
        	$tmp = $item['end_time_dt']->format('D');
            switch ($tmp) {
                case 'Mon':
                    $item['end_day'] = '월';
                    break;
                case 'Tue':
                    $item['end_day'] = '화';
                    break;
                case 'Wed':
                    $item['end_day'] = '수';
                    break;
                case 'Thu':
                    $item['end_day'] = '목';
                    break;
                case 'Fri':
                    $item['end_day'] = '금';
                    break;
                case 'Sat':
                    $item['end_day'] = '토';
                    break;
                case 'Sun':
                    $item['end_day'] = '일';
                    break;
                default:
                    # code...
                    break;
            }
            $item['dday_0'] = new Datetime($item['end_time']);
            $item['dday_1'] = new Datetime($item['end_time']);
            $item['dday_1'] = $item['dday_1']->modify('-1 day')->format("Y-m-d");
            // $item['new_expire_dt'] = new Datetime($item['start_time']);
            // $item['new_expire_dt'] = $item['new_expire_dt']->modify('+10 day');
            
            $item['remain_days'] = $this->validate->get_remain_days($item['end_time']);

            $data[$item['sub_category_id']][] = $item;
        }

        return $data;



		$now = date('Y-m-d H:i:s');
		 
		$this->china_db->where_in('sub_category_id', $a_sub_category_id);
		$this->china_db->where('use_yn', 'Y');
		$this->china_db->where('deleted_time', null);
		$this->china_db->where('start_time <',$now);
		$this->china_db->where('end_time >',$now);
		 
		$this->china_db->order_by("display_order", "ASC");
		$query = $this->china_db->get('BANNER');
		$banner_list = $query->result_array();
		 
		if($banner_list){
			foreach($banner_list as $key => $value){
				$new_banner_list[$value['sub_category_id']][] = $value;
			}
		}else{
			$new_banner_list = array();
		}
		return $new_banner_list;
	}

	/****
	 * @Desc 	메인 페이지 팝업
	 * @param
	 *****/
	function main_popup()
	{
		/*$sql = "SELECT *
		 FROM china_dangicokr.BANNER where category_id = 1 and popup_yn = 'Y' and use_yn='Y'
		 order by banner_id desc limit 1
			";*/
	
		$now = date('Y-m-d H:i:s');
	
		$this->china_db->where('category_id', '167');
		$this->china_db->where('sub_category_id', '184');
		$this->china_db->where('use_yn', 'Y');
		$this->china_db->where('deleted_time', null);
		$this->china_db->where('start_time <',$now);
		$this->china_db->where('end_time >',$now);
	
		$this->china_db->order_by("banner_id", "DESC");
		$this->china_db->limit(1);
		$query = $this->china_db->get('BANNER');
	
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	
	}


}