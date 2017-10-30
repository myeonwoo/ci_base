<?php
//카테고리 조회
class M_staca_inquiry extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('staca/ACA_inquiry'); 

		$this->load->driver('cache');
        $this->cache_enabled = (_IS_DEV_QA) ? false : true;
	}

	public function common_academy_subject($org_id = '10') {
		$result = $this->aca_inquiry->common_academy_subject($org_id);

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			$tmp[$item['subject_id']] = $item;
		}
		return $tmp;

		return $result['result'];
    }

	public function common_subject($biz_code = 'ENGDANGI') {
		$result = $this->aca_inquiry->common_subject($biz_code);

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			$tmp[$item['subject_id']] = $item;
		}
		return $tmp;

		return $result['result'];
    }
    public function common_level($subject_id = null, $store_category_ids = 10) {

    	// 토익, 강남학원이면 
    	if ($subject_id == 11 && $store_category_ids == 10) {
    		return array(
    			'68' => array('level_nm'=>'650+기초', 'level_id'=>68)
    			,'69' => array('level_nm'=>'750+중급', 'level_id'=>69)
    			,'70' => array('level_nm'=>'850+정규', 'level_id'=>70)
    			,'71' => array('level_nm'=>'900+실전', 'level_id'=>71)
    		);
    	}
    	// 토익, 부산학원이면
    	else if ($subject_id == 11 && $store_category_ids == 15) {
    		return array(
    			'126' => array('level_nm'=>'600+목표', 'level_id'=>126)
    			,'127' => array('level_nm'=>'700+목표', 'level_id'=>127)
    			,'128' => array('level_nm'=>'750+목표', 'level_id'=>128)
    			,'129' => array('level_nm'=>'850+목표', 'level_id'=>129)
    			,'71' => array('level_nm'=>'900+실전', 'level_id'=>71)
    		);
    	}

		$result = $this->aca_inquiry->common_level($subject_id);

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			$tmp[$item['level_id']] = $item;
		}
		return $tmp;

		return $result['result'];
    }
    public function common_category($subject_id = null) {
		$result = $this->aca_inquiry->common_category($subject_id);

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			$tmp[$item['cate_id']] = $item;
		}
		return $tmp;

		return $result['result'];
    }
    
    public function record_buy($params){
    	$result = $this->aca_inquiry->record_buy($params);
    	
    	/* $tmp = array();
    	if($result['result']['list']){
	    	foreach($result['result']['list'] as $key => $value){
				$tmp[$value['saleinfo_id']] = $value;
	    	}
    	}
    	return $tmp; */
    	return $result['result']['list'];
    }
    
    public function teacher_bizcode($biz_code = null, $params = array()) {
		$result = $this->aca_inquiry->teacher_bizcode($biz_code, $params);
		// return $params;
		// return $result;

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			if ($item['team_yn']=='Y') {
				$names = array();
				foreach ($item['team_detail'] as $key => $team_member) {
					$names[] = $team_member['tec_nm'];
				}
				$item['team_member_name'] = implode(', ', $names);
			}
			$tmp[$item['on_tec_id']] = $item;
		}
		return $tmp;

    }
    public function teacher_subject($subject_id = null, $params = array()) {
		$result = $this->aca_inquiry->teacher_subject($subject_id, $params);
		// return $params;
		// return $result;

		$tmp = array();
		foreach ($result['result'] as $key => $item) {
			if ($item['team_yn']=='Y') {
				$names = array();
				foreach ($item['team_detail'] as $key => $team_member) {
					$names[] = $team_member['tec_nm'];
				}
				$item['team_member_name'] = implode(', ', $names);
			}
			$tmp[$item['on_tec_id']] = $item;
		}
		return $tmp;

    }
    public function api_saleinfos_state_count($biz_codes = null, $org_id = null, $subject_id = null) {
		$result = $this->aca_inquiry->api_saleinfos_state_count($biz_codes, $org_id, $subject_id);
		return $result['result'];
    }

    // 주어진 각각 subject_id 에 상품 하나씩 리턴
    public function saleinfos_by_subject_id($subject_ids=array(), $config=array())
    {
    	$params = array();
    	if (isset($config['store_category_ids'])) $params['store_category_ids'] = $config['store_category_ids'];
        else $params['store_category_ids'] = '10'; // (강남영단기)

    	if (isset($config['cls_id'])) $params['cls_id'] = $config['cls_id'];
        else $params['cls_id'] = 11; // 11:단과, 12:종합반

    	if (isset($config['biz_code'])) $params['biz_code'] = $config['biz_code'];
        else $params['biz_code'] = 'ENGDANGI';

    	if (isset($config['course_lecture'])) $params['course_lecture'] = $config['course_lecture'];
        else $params['course_lecture'] = '11';   // 분류값
        
        $params['offset'] = 1;
        $params['extra_infos'] = 'amt';

        $tmp = array();
        foreach ($subject_ids as $subject_id => $item) {
        	$params['course_lecture'] = $subject_id;
        	$result = $this->store_category_course_saleinfos($params);
        	if (count($result)>0) {
        		$tmp[] = $result[0];
        	}
        	// return $tmp;
        }

        return $tmp;
    }

	// 상품 리스트
	// public function saleinfo_by_store_category_ids($config = array()){
	public function store_category_course_saleinfos($config = array()){
		
		$cacheKey = $this->cache->memcached->get_cache_key(__CLASS__, __FUNCTION__, func_get_args());

		$result = null;
        if ($this->cache_enabled) $result = $this->cache->memcached->get($cacheKey);
		if ($result) return $result;

		if (!isset($config['store_category_ids']) || !$config['store_category_ids']) {
			$config['store_category_ids'] = '10';	// 강남영단기
		}
		if (!isset($config['cls_id']) || !$config['cls_id']) {
			$config['cls_id'] = '11';	// // 11:단과, 12:종합반
		}
		// if (!isset($config['biz_code']) || !$config['biz_code']) {
		// 	$config['biz_code'] = 'ENGDANGI';
		// }
		if (!isset($config['extra_infos']) || !$config['extra_infos']) {
			$config['extra_infos'] = 'amt';
		}

		$result = $this->aca_inquiry->store_category_course_saleinfos($config);

		if ($result['result']['totalCount'] > 0) {
			$dataset = array();
			foreach ($result['result']['list'] as $key => $item) {
				if (isset($config['search_title']) && $config['search_title'] && strpos($item['sale_name'], $config['search_title'])===false) {
					// noop
				}
				else if(isset($config['search_content']) && $config['search_content'] && strpos($item['sale_name'], $config['search_content'])===false && strpos($item['teacher_name'], $config['search_content'])===false){
					// noop
				}
				else if(isset($config['except_string']) && $config['except_string'] && ( preg_match( $config['except_string'], $item['sale_name'], $matches ) || preg_match( $config['except_string'], $item['teacher_name'], $matches ) )){
					
				}
				else {
					$item['saleinfo_display_icons_list'] = explode('|', $item['saleinfo_display_icons']);
					$dataset[] = $item;
				}
			}
			$result = $dataset;
		} else {
			$result = array();
		}
		
		if ($this->cache_enabled) $this->cache->memcached->save($cacheKey, $result, 1 * 60);
		return $result;
	}

	// 상품 조회
	public function saleinfos($config = array()){

		if (!isset($config['saleinfo_ids'])) {
			return array();
		}
		if (!isset($config['extra_infos'])) {
			$config['extra_infos'] = '';
		}

		$result = $this->aca_inquiry->saleinfos(
			$saleinfo_ids = $config['saleinfo_ids']		// $saleinfo_ids = '3'
			, $extra_infos  = $config['extra_infos']
		);

		if ($result['resultCode'] == "0000" && count($result['result']) == 1) {
			$item = $result['result'][0];
			$item['saleinfo_display_icons_list'] = explode('|', $item['saleinfo_display_icons']);
			return $item;
		} elseif ($result['resultCode'] == "0000" && count($result['result'])>1) {
			foreach ($result['result'] as $key => &$item) {
				$item['saleinfo_display_icons_list'] = explode('|', $item['saleinfo_display_icons']);
			}
			return $result['result'];
		} else {
			return null;
		}
	}

	// 상품의 상세 설명 조회 : 
	public function saleinfo_saleinfo_descr($config = array()){

		if (!isset($config['saleinfo_id'])) {
			return array();
		}

		$result = $this->aca_inquiry->saleinfo_saleinfo_descr(
			$saleinfo_id = $config['saleinfo_id']
		);
		// return $result;

		$tmp = array(
			'content' => ''
			,'target' => ''
			,'main' => ''
			,'effect' => ''
		);
		if ($result['resultCode'] == "0000") {
			foreach ($result['result'] as $key => $item) {
				$tmp[$item['descr_type']] = $item;
			}
		}
		return $tmp;
	}

	// 
	public function getCourseSaleinfos_by_teacher_ids($config = array()){

		if (!isset($config['teacher_id'])) {
			return array();
		}

		$result = $this->aca_inquiry->getCourseSaleinfos_by_teacher_ids(
			$teacher_id = $config['teacher_id']		// $teacher_id = '3'
			, $biz_code=null
			, $course_lecture=null
			, $course_domain=null
			, $course_category=null
			, $course_level=null
			, $extra_infos=null
			, $page=1
			, $offset=20
		);

		return $result;


		if ($result['totalCount'] > 0) {
			return $result['list'];
		} else {
			return array();
		}
	}

	// 상품 리스트
	/****
		* @Desc 	sale_status 순으로 정렬된 상품 리스트
		* @param	
		

		sale_status
			HD: 마감
			AP: 마감임박
			DO: 수강신청
			CA: 마감주의
			AD: 앵콜모집
	*****/
	public function products_oderby_sale_status($howmany, $config){

		$cacheKey = $this->cache->memcached->get_cache_key(__CLASS__, __FUNCTION__, func_get_args());

		$result = null;
        if ($this->cache_enabled) $result = $this->cache->memcached->get($cacheKey);
		if ($result) return $result;

		$result = $this->aca_inquiry->store_category_course_saleinfos($config);

		
		if ($result['result']['totalCount'] > 0) {
			$dataset = array();
			foreach ($result['result']['list'] as $key => $item) {
				if (isset($config['search_title']) && $config['search_title'] && strpos($item['sale_name'], $config['search_title'])===false) {
					// noop
				}
				else if(isset($config['search_content']) && $config['search_content'] && strpos($item['sale_name'], $config['search_content'])===false && strpos($item['teacher_name'], $config['search_content'])===false){
					// noop
				}
				else {
					$item['saleinfo_display_icons_list'] = explode('|', $item['saleinfo_display_icons']);
					$dataset[] = $item;
				}
			}
		} else {
			$dataset = array();
		}
		
		usort($dataset, function($a, $b){
			$ref = array(
				'HD' => 1,
				'AP' => 5,
			);
			if (!isset($ref[$a['sale_status']])) {
				$a1 = rand(10,15);
			} else {
				$a1 = $ref[$a['sale_status']] + rand(0,3);
			}
			if (!isset($ref[$b['sale_status']])) {
				$b1 = rand(10,15);
			} else {
				$b1 = $ref[$b['sale_status']] + rand(0,3);
			}
			if ($a1 == $b1) {
				return 0;
			}
			return ($a1 < $b1) ? -1 : 1;
		});
		
		$result = array_slice($dataset, 0, $howmany);

		if ($this->cache_enabled) $this->cache->memcached->save($cacheKey, $result, 1 * 60);
		return $result;
	}
}

