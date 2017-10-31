<?php
//카테고리 조회
class M_inquiry extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
		$this->load->library('billing/ST_inquiry'); //선생님 관련 API
	}

	/**
	 * getSaleinfosByStoreCategoryIds : 상점 진열 카테고리에 연결된 상품 조회
	 *
	 * @param mixed $store_category_ids 	: 상점진열카테고리리스트 구분자(,) ex) 1,2,3
	 * @param mixed $biz_code 		: 단기코드 ex) ENGDANGI, JOBDANGI, NOMUDANGI
	 * @param mixed $saleinfo_types 		: 상품유형타입 리스트 구분자(,) ex) ONE,FREE,REFP,REPK
	 * @param mixed $product_types 		: 제품유형타입 리스트 구분자(,) ex) COUS,BOOK
	 * @param mixed $page 			: 페이지 번호
	 * @param mixed $offset 			: 가져올 데이터 수
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function getSaleinfosByStoreCategoryIds($store_category_ids, $biz_code, $saleinfo_types=null, $product_types=null, $page=1, $offset=100, $order_type=null, $extra_infos="CATEGORY,OPTION,BOOK", $appi=null){
		$excu_list = $this->st_inquiry->getSaleinfosByStoreCategoryIds($store_category_ids, $biz_code, $saleinfo_types, $product_types, $page, $offset, $order_type, $extra_infos, $appi);	
		$result = null;
		
		 //정상일 경우.
		 if($excu_list["status"] == "200"){
		 	if(count($excu_list["result"]["list"])> 0){
	        	//primary key를 array key로  
	        	foreach ($excu_list["result"]["list"] as $key => $value) {
	        		$result[$value["saleinfo_id"]] =  $value;
	        	}
	        }
        
		}
       
		return $result;
	}

	/**
	 * getTeacherCourseSaleinfosByStoreCategoryIds : 상점 진열 카테고리에 강좌로 연결된 선생님별 강좌 상품 조회 (+ 연관 상품 정보 ) *페이징은 선생님별로 상품단위로 적용됨.
	 *
	 * @param mixed $store_category_ids 	: 상점진열카테고리리스트 구분자(,) ex) 1,2,3
	 * @param mixed $biz_code 		: 단기코드 ex) ENGDANGI, JOBDANGI, NOMUDANGI
	 * @param mixed $saleinfo_types 		: 상품유형타입 리스트 구분자(,) ex) ONE,FREE,REFP,REPK
	 * @param mixed $product_types 		: 제품유형타입 리스트 구분자(,) ex) COUS,BOOK
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function getTeacherCourseSaleinfosByStoreCategoryIds($store_category_ids, $biz_code, $saleinfo_types=null, $product_types=null, $page=1, $offset=100, $extra_infos="OPTION,CATEGORY"){
		$excu_list = $this->st_inquiry->getTeacherCourseSaleinfosByStoreCategoryIds($store_category_ids, $biz_code, $saleinfo_types, $product_types, $page, $offset, $extra_infos);	
		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"])> 0){
        		//primary key를 array key로  
	        	foreach ($excu_list["result"] as $key => $value) {
	        		$best_cnt = 0;
	        		$gener_cnt = 0;
	        		unset($best_saleinfoList);
	        		unset($gener_saleinfoList);
	        		
	        		$result[$value["teacher_id"]]["teacher_id"]   = $value["teacher_id"];
	        		$result[$value["teacher_id"]]["teacher_name"] = $value["teacher_name"];
					
	        		//대표강좌 /일반강좌 분리 
	        		$best_cnt = 0;
	        		$gener_cnt = 0;
	        		foreach ($value["saleinfoList"]["list"] as $key => $value2) {
	        			if($value2["icon_best_yn"] == "Y"){
	        				//대표강좌	
	        				$best_saleinfoList[$key] = $value2;
	        				$best_cnt ++;
	        			}else{
	        				//일반강좌	
	        				$gener_saleinfoList[$key] = $value2;
	        				$gener_cnt ++;
	        			}	        			
	        		}
					$result[$value["teacher_id"]]["best_saleinfoList"]["list"] = $best_saleinfoList;
					$result[$value["teacher_id"]]["best_saleinfoList"]["totalCount"] = $best_cnt;
					$result[$value["teacher_id"]]["saleinfoList"]["list"] 	  = $gener_saleinfoList;
					$result[$value["teacher_id"]]["saleinfoList"]["totalCount"] 	= $gener_cnt;

	        	}
        	}	
        }
        
		return $result;
	}	

	/**
	 * saleinfo_by_saleinfo_ids : 상품 조회 by saleinfo_ids
	 *
	 * @param mixed $saleinfo_ids       : 상품id(복수)
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */

	function saleinfo_by_saleinfo_id($saleinfo_ids, $extra_infos=null, $appi=null){
		$excu_list = $this->st_inquiry->saleinfo_by_saleinfo_id($saleinfo_ids, $extra_infos, $appi);

		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"])> 0){
        		//primary key를 array key로  
	        	foreach ($excu_list["result"] as $key => $value) {
	        		$result[$value["saleinfo_id"]] = $value;
	        	}
        	}	
        }
        
		return $result;
	}

	/**
	 * getSaleinfosByTeacherIds : 상품의 상세 설명 조회
	 *
	 * @param mixed $saleinfo_id 	: 상품id
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function getSaleinfosByTeacherIds($saleinfo_id, $appi=null){
		$excu_list = $this->st_inquiry->getSaleinfosByTeacherIds($saleinfo_id, $appi);

		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"])> 0){
        		//primary key를 array key로  
	        	foreach ($excu_list["result"] as $key => $value) {
	        		$result[$value["descr_type"]] = $value;
	        	}
        	}	
        }
        
		return $result;
	}

	 /**
     * getCourseSaleinfosByTeacherIds : 상점 진열 카테고리에 강좌로 연결된 선생님의 강좌 상품 조회 (+ 연관 상품 정보 )
     *
     * @param $teacher_id : 선생님id
     * @param $store_category_ids : 상점진열카테고리리스트 구분자
     * @param $biz_code : 단기코드
     * @param $saleinfo_types : 상품유형타입 리스트 구분자
     * @param $product_types : 제품유형타입 리스트 구분자(COUS,BOOK)
     * @param $page
     * @param $offset
     * @access public
     *
     * @return mixed Value.
     */

     function getCourseSaleinfos_by_teacher_ids($teacher_id, $store_category_ids=null, $biz_code=null, $saleinfo_types=null, $product_types='COUS', $saleinfo_level_type=null, $page, $offset, $order_type=null, $extra_infos="OPTION"){
     	$excu_list = $this->st_inquiry->getCourseSaleinfos_by_teacher_ids($teacher_id, $store_category_ids, $biz_code, $saleinfo_types, $product_types, $saleinfo_level_type, $page, $offset, $order_type, $extra_infos);

		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"]["list"])> 0){
        		//primary key를 array key로  
	        	foreach ($excu_list["result"]["list"] as $key => $value) {
	        		$result[$value["saleinfo_id"]] = $value;
	        	}
        	}	
        }
        
		return $result;
     }

     /**
     * getBookSaleinfosByTeacherIds : 상점 진열 카테고리에 교재로 연결된 선생님의 교재 상품 조회
     *
     * @param $teacher_id : 선생님id
     * @param $store_category_ids : 상점진열카테고리리스트 구분자
     * @param $biz_code : 단기코드
     * @param $saleinfo_types : 상품유형타입 리스트 구분자
     * @param $product_types : 제품유형타입 리스트 구분자(COUS,BOOK)
     * @param $page
     * @param $offset
     * @access public
     *
     * @return mixed Value.
     */

     function getBookSaleinfos_by_teacher_ids($teacher_id, $store_category_ids=null, $biz_code=null, $saleinfo_types=null, $product_types='BOOK', $extra_infos='OPTION', $page, $offset, $order_type=null){
     	$excu_list = $this->st_inquiry->getBookSaleinfos_by_teacher_ids($teacher_id, $store_category_ids, $biz_code, $saleinfo_types, $product_types, $extra_infos, $page, $offset, $order_type);

		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"]["list"])> 0){
        		//primary key를 array key로  
	        	foreach ($excu_list["result"]["list"] as $key => $value) {
	        		$result[$value["saleinfo_id"]] = $value;
	        	}
        	}	
        }
        
		return $result;
     }

    /**
	 * course_info : 강의 조회
	 *
	 * @param mixed $course_id           : 강의id
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function course_info($course_id, $extra_info=null){ 
		$excu_list = $this->st_inquiry->course_info($course_id, $extra_info);

		$result = null;

		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	if(count($excu_list["result"])> 0){
        		$result = $excu_list["result"];
        	}	
        }
        
        

		return $result;
	}

	/****
		* @Desc 	
		* @param	
		상품리스트 조회
		inquiry: /api/store_category/{store_category_ids}/saleinfos 사용
			store_category_ids: 
			page:
			offset:
	*****/
	public function store_category($store_category_ids, $option = array()){
        $config = array(
            'url'    => BILLING_API_HOST."/api/store_category/".$store_category_ids."/saleinfos"
            ,'method' => 'GET'
            ,'data'   => array(
                'store_category_ids' 		=> $store_category_ids
                // ,'extra_infos'	=> 'ROPTION,DESC,COURSE,CATEGORY,OPTION,BOOK,COURSE_LECT,OPTION_BOOK,COURSE_TEACHER'
                ,'extra_infos'	=> 'BOOK'
                ,'page'             		=> 1
                ,'offset'            		=> 20
            )
        );

        if (isset($option['extra_infos'])) {
        	$config['data']['extra_infos'] = $option['extra_infos'];
        }
        if (isset($option['page'])) {
            $config['data']['page'] = $option['page'];
        }
        if (isset($option['offset'])) {
            $config['data']['offset'] = $option['offset'];
        }
        if (isset($option['order_type'])) {
            $config['data']['order_type'] = $option['order_type'];
        }
        if (isset($option['tag'])) {
        	$config['data']['tag'] = $option['tag'];
        }
        
        // return $config;

        $this->strestclient->setData($config['url'], $config['method'], $config['data']);
        $data = $this->strestclient->execute();

        //정상일 경우.
        $data_to = array();
		if($data['status'] == "200"){
			if(count($data["result"]['list'])> 0){
				$result = $data["result"]['list'];

        		// 데이타 조정
        		foreach ($result as $key => &$item) {
        			$item['discount_amt'] = $item['display_sale_amt'] - $item['sale_amt'];
                    if ($item['display_sale_amt']>0) {
                        $item['discount_pct'] = (int) $item['discount_amt'] * 100 / $item['display_sale_amt'];
                    } else {
                        $item['discount_pct'] = 0;
                    }
        			$item['saleinfo_display_icons_a'] = explode('|', $item['saleinfo_display_icons']);

                    if ($item['end_period_type'] == 'MON') $item['end_period_type_name'] = $item['end_period'].' 개월'; 
                    elseif ($item['end_period_type'] == 'DAY') $item['end_period_type_name'] = $item['end_period'].' 일';
                    else $item['end_period_type_name'] = '';

        			$flag = false;
        			if (isset($option['teacher_id'])){
        				foreach ($item['courseTeacherList'] as $key => $teacher) {
        					if ($teacher['teacher_id'] == $option['teacher_id']) {
        						$flag = true;
        					}
        				}
        			}
        			else {
        				$flag = true;
        			}

        			if ($flag) {
        				$data_to[] = $item;
        			}
        		}
            }
        }

		// return array();
        return $data_to;
    }

    public function teacher_call($saleinfo_id, $start_time){
    	$config = array(
    			'url'    => BILLING_API_HOST."/api/saleinfos/".$saleinfo_id."/phone_china_teacher"
    			,'method' => 'GET'
    			,'data'   => array(
    					'saleinfo_id'	=> $saleinfo_id
    					,'start_time'	=> $start_time
    			)
    	);

    	$this->strestclient->setData($config['url'], $config['method'], $config['data']);
    	$data = $this->strestclient->execute();
    	
    	return $data;
    }
    public function teacher_course($teacher_id, $option = array()){
        $config = array(
            'url'    => BILLING_API_HOST."/api/teacher/".$teacher_id."/course/saleinfos"
            ,'method' => 'GET'
            ,'data'   => array(
                'teacher_id'        => $teacher_id
                // ,'extra_infos'   => 'ROPTION,DESC,COURSE,CATEGORY,OPTION,BOOK,COURSE_LECT,OPTION_BOOK,COURSE_TEACHER'
                ,'extra_infos'  => 'OPTION'
                ,'page'                     => 1
                ,'offset'                   => 100
            )
        );

        if (isset($option['store_category_ids'])) {
            $config['data']['store_category_ids'] = $option['store_category_ids'];
        }
        if (isset($option['extra_infos'])) {
            $config['data']['extra_infos'] = $option['extra_infos'];
        }
        if (isset($option['page'])) {
            $config['data']['page'] = $option['page'];
        }
        if (isset($option['offset'])) {
            $config['data']['offset'] = $option['offset'];
        }
        if (isset($option['order_type'])) {
            $config['data']['order_type'] = $option['order_type'];
        }
        if (isset($option['saleinfo_level_type'])) {
            $config['data']['saleinfo_level_type'] = $option['saleinfo_level_type'];
        }
        
        // return $config;

        $this->strestclient->setData($config['url'], $config['method'], $config['data']);
        $data = $this->strestclient->execute();

        // return $data;

        //정상일 경우.
        $data_to = array();
        if($data['status'] == "200"){
            if(count($data["result"]['list'])> 0){
                $result = $data["result"]['list'];

                // 데이타 조정
                foreach ($result as $key => &$item) {
                    $item['discount_amt'] = $item['display_sale_amt'] - $item['sale_amt'];
                    if ($item['display_sale_amt']>0) {
                        $item['discount_pct'] = (int) $item['discount_amt'] * 100 / $item['display_sale_amt'];
                    } else {
                        $item['discount_pct'] = 0;
                    }
                    $item['saleinfo_display_icons_a'] = explode('|', $item['saleinfo_display_icons']);

                    if ($item['end_period_type'] == 'MON') $item['end_period_type_name'] = $item['end_period'].' 개월'; 
                    elseif ($item['end_period_type'] == 'DAY') $item['end_period_type_name'] = $item['end_period'].' 일';
                    else $item['end_period_type_name'] = '';

                    $flag = false;
                    if (isset($option['teacher_id'])){
                        foreach ($item['courseTeacherList'] as $key => $teacher) {
                            if ($teacher['teacher_id'] == $option['teacher_id']) {
                                $flag = true;
                            }
                        }
                    }
                    else {
                        $flag = true;
                    }

                    if ($flag) {
                        $data_to[] = $item;
                    }
                }
            }
        }

        // return array();
        return $data_to;
    }

    /****
		* @Desc 	상품리스트 조회 (Group By teacher_id)
		* @param	
	*****/
	public function lectures_groupby_teacher($store_category_ids, $option = array('extra_infos'=>'OPTION')){
        $config = array(
            'url'    => BILLING_API_HOST."/api/store_category/".$store_category_ids."/course/teachers/saleinfos"
            ,'method' => 'GET'
            ,'data'   => array(
                'store_category_ids' 		=> $store_category_ids
                // ,'extra_infos'	=> 'ROPTION,DESC,COURSE,CATEGORY,OPTION,BOOK,COURSE_LECT,OPTION_BOOK,COURSE_TEACHER'
                ,'extra_infos'	=> $option['extra_infos']
                ,'page'             		=> 1
                ,'offset'            		=> 100
            )
        );
        if (isset($option['extra_infos'])) {
            $config['data']['extra_infos'] = $option['extra_infos'];
        }
        if (isset($option['saleinfo_level_type']) && strlen($option['saleinfo_level_type'])>0) {
            $config['data']['saleinfo_level_type'] = $option['saleinfo_level_type'];
        }

        $this->strestclient->setData($config['url'], $config['method'], $config['data']);
        $data = $this->strestclient->execute();

        //정상일 경우.
        $result = array();
		if($data["status"] == "200"){
			if(count($data["result"])> 0){
				foreach ($data['result'] as $key => &$item) {

					foreach ($item['saleinfoList']['list'] as $key => &$product) {
						$product['discount_amt'] = $product['display_sale_amt'] - $product['sale_amt'];
                        if ($product['display_sale_amt']>0)
                            $product['discount_pct'] = (int) $product['discount_amt'] * 100 / $product['display_sale_amt'];
                        else 
                            $product['discount_pct'] = 0;
						$product['saleinfo_display_icons_a'] = explode('|', $product['saleinfo_display_icons']);
					}

					$result[$item['teacher_id']] = $item;
				}
				// $result = $data["result"];
			}
		}

		return $result;
    }

    /*
     * 카테고리 번호로 강좌 추출
     */
    public function category_saleinfos($category_id)
    {
    	$config = array(
			'url'    => BILLING_API_HOST."/api/store_category/".$category_id."/course/saleinfos"
			,'method' => 'GET'
			,'data'   => array(
				'store_category_ids' 		=> $category_id
				,'extra_infos'	=> 'OPTION,DESC,COURSE_LECT,COURSE_TEACHER,CATEGORY,OPTION_BOOK'
				,'biz_code' => BIZ_CODE
			)
    	);
    
    	$this->strestclient->setData($config['url'], $config['method'], $config['data']);
    	$data = $this->strestclient->execute();

    	// return $config;
    	 
    	//정상일 경우.
    	if($data["status"] == "200"){
    		if(count($data["result"]['list'])> 0){
    			foreach ($data["result"]['list'] as $key => &$product) {
    				$product['discount_amt'] = $product['display_sale_amt'] - $product['sale_amt'];
    				if ($product['display_sale_amt']>0){
    					$product['discount_pct'] = (int) $product['discount_amt'] * 100 / $product['display_sale_amt'];
    				}else{
    					$product['discount_pct'] = 0;
    				}
    				$product['saleinfo_display_icons_a'] = explode('|', $product['saleinfo_display_icons']);
    			}
    		}
    	}
    
    	return $data;
    }
}

