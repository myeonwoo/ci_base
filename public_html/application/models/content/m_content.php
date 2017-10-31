<?php
class M_content extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->default_db = $this->load->database("eng", TRUE);
	}

	public function get($content_id){
		$now = date('Y-m-d H:i:s');

		$this->default_db->where('content_id',$content_id);

		$query = $this->default_db->get('CONTENT_NEW');

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

		if (isset($options['yn_used'])) $this->default_db->where('yn_used', $options['yn_used']);
		if (isset($options['content_category_id'])) $this->default_db->where('content_category_id', $options['content_category_id']);
		if (isset($options['in_content_category_id'])) $this->default_db->where_in('content_category_id', $options['in_content_category_id']);
		if (isset($options['like_subject'])) $this->default_db->like('subject', $options['like_subject']);
		if (isset($options['yn_deleted'])) $this->default_db->where('yn_deleted', $options['yn_deleted']);

		if (isset($options['dt_start'])) $this->default_db->where('dt_start <', $options['dt_start']); 
		else $this->default_db->where('dt_start <',$now);
		if (isset($options['dt_end'])) $this->default_db->where('dt_end >', $options['dt_end']); 
		$this->default_db->where('dt_end >',$now);

		$query = $this->default_db->get('CONTENT_NEW');
		$data = $query->result_array();

		return $data;
	}
	//컨텐츠 수정
	public function update($content_id, $data){

		$this->default_db->where('content_id', $content_id);
		return $this->default_db->update('CONTENT_NEW', $data);
	}







	public function get_list_del($category_id=null, $sub_category_id=null,$type=null, $config=array()){
		$now = date('Y-m-d H:i:s');

		$this->default_db->select('BNN.*');
		$this->default_db->from('BANNER AS BNN');
		$this->default_db->join('BANNER_CATEGORY AS BCG','BNN.sub_category_id = BCG.category_id','inner');
		if($category_id)  $this->default_db->where('BNN.category_id',$category_id);
		if($sub_category_id) $this->default_db->where('BNN.sub_category_id',$sub_category_id);
		if($type) $this->default_db->where('BCG.category_type',$type);
		
		$this->default_db->where(" (BNN.deleted_time IS  NULL or BNN.deleted_time = '0000-00-00 00:00:00') ", NULL, FALSE );
		$this->default_db->where("BNN.end_time >= date_add(now(), interval -15 day) ", NULL, FALSE);
		
		if (isset($config['use_event_period'])) {
			$this->default_db->where('BNN.start_time <',$now);
			$this->default_db->where('BNN.end_time >',$now);
		}
		if (isset($config['use_yn'])) $this->default_db->where('BNN.use_yn', 'Y');

		$this->default_db->order_by("BNN.display_order", "ASC");

		$cursor = $this->default_db->get();
		
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

		$this->default_db->where('sub_category_id', $sub_category_id);
		$this->default_db->where('use_yn', 'Y');
		$this->default_db->where('deleted_time', null);
		$this->default_db->where('start_time <',$now);
		$this->default_db->where('end_time >',$now);

		$this->default_db->order_by("banner_id", "DESC");
		$this->default_db->limit(1);
		$query = $this->default_db->get('BANNER');
		
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
	}

	

	//배너 정보
	function get_info($banner_id){
		$this->default_db->select("*, SUBSTRING_INDEX(`d_day_color`,'_', 1 ) AS d_day_color1,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 2 ) ,'_', -1 ) AS d_day_color2,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 3 ) ,'_', -1 ) AS d_day_color3,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 4 ) ,'_', -1 ) AS d_day_color4,
								SUBSTRING_INDEX(`d_day_color`,'_', -1 ) AS d_day_opacity,
								SUBSTRING_INDEX(`d_day_position`,'_', 1 ) AS d_day_left,
								SUBSTRING_INDEX(`d_day_position`,'_', -1 ) AS d_day_top ", FALSE);
		$this->default_db->where('banner_id =', $banner_id);
		$cursor = $this->default_db->get('BANNER');
		
		$return = $cursor->row_array();
		
		return $return;
	}


	//배너 저장
	function banner_ins($data){
		$return = $this->default_db->insert('BANNER', $data);

		return $return;
		
	}
	
	//배너 수정
	function banner_mod($data, $banner_id){
	
		$this->default_db->where('banner_id', $banner_id);
		$return = $this->default_db->update('BANNER', $data);
		
		return $return;
	}
	

	//배너 리스트 - 서비스 페이지 사용
	function banner_list($category_id, $sub_category_id=null){
		$this->default_db->select("*, SUBSTRING_INDEX(`d_day_color`,'_', 1 ) AS d_day_color1,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 2 ) ,'_', -1 ) AS d_day_color2,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 3 ) ,'_', -1 ) AS d_day_color3,
								SUBSTRING_INDEX( SUBSTRING_INDEX(`d_day_color`,'_', 4 ) ,'_', -1 ) AS d_day_color4,
								SUBSTRING_INDEX(`d_day_color`,'_', -1 ) AS d_day_opacity,
								SUBSTRING_INDEX(`d_day_position`,'_', 1 ) AS d_day_left,
								SUBSTRING_INDEX(`d_day_position`,'_', -1 ) AS d_day_top ", FALSE);
		if($category_id)  $this->default_db->where('category_id', $category_id);
		if($sub_category_id) $this->default_db->where('sub_category_id', $sub_category_id);
		
		$this->default_db->where(" (deleted_time IS  NULL or deleted_time = '0000-00-00 00:00:00') ", NULL, FALSE );
		$this->default_db->where("use_yn", "Y");
		$this->default_db->where("popup_yn", "N");	//팝업 제외 

		//$this->default_db->where("end_time >= date_add(now(), interval -15 day) ", NULL, FALSE);
		$this->default_db->where("start_time <= now()", NULL, FALSE);
		$this->default_db->where("end_time >= now()", NULL, FALSE);

		$this->default_db->order_by("category_id", "ASC");
		$this->default_db->order_by("sub_category_id", "ASC");
		$this->default_db->order_by("display_order", "ASC");

		$cursor = $this->default_db->get('BANNER');
		
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

		$this->default_db->where('category_id', '1');
		$this->default_db->where('sub_category_id', '83');
		$this->default_db->where('use_yn', 'Y');
		$this->default_db->where('deleted_time', null);
		$this->default_db->where('start_time <',$now);
		$this->default_db->where('end_time >',$now);

		$this->default_db->order_by("banner_id", "DESC");
		$this->default_db->limit(1);
		$query = $this->default_db->get('BANNER');
		
		if ($query->num_rows() < 1) {
			return null;
		} else {
			return $query->row_array();
		}
		
	}
}