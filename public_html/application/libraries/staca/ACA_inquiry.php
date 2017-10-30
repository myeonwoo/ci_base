<?php 
// include("../../codeigniter/st/libraries/staca_api/ACA_inquiry.php");

// <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ACA_inquiry {
	private $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		if(!$this->CI->strestclient){
			$this->CI->load->library('STRestClient', array('encode'=>'UTF-8') );
		}

		$this->error_default = array(
  			"status" => "200",
			"resultCode" => "ERR-PARAM",
			"userMsg" => "ok good",
			"sysTemMsg" => "필수 파라메터 누락"
		);
	}
	
    public function common_academy_subject($org_id = '10') {

        $config = array(
            'url'    => ST_ACA_API_HOST."/api/common/academy/".$org_id."/subject"
            ,'method' => 'GET'
            ,'data'   => array()
        );

        if($org_id) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }

    }
	public function common_subject($biz_code = 'ENGDANGI') {

		$config = array(
			'url'    => ST_ACA_API_HOST."/api/common/".$biz_code."/subject"
			,'method' => 'GET'
			,'data'   => array()
		);

        if($biz_code) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }

    }
    public function common_level($subject_id = null) {

		$config = array(
			'url'    => ST_ACA_API_HOST."/api/common/".$subject_id."/level"
			,'method' => 'GET'
			,'data'   => array()
		);

        if($subject_id) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }
    }
    // 과목유형 리스트 획득
    public function common_category($subject_id = null) {

		$config = array(
			'url'    => ST_ACA_API_HOST."/api/common/".$subject_id."/category"
			,'method' => 'GET'
			,'data'   => array()
		);

        if($subject_id) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }
    }

    // 유저아이디에 대한 수강중인 강좌 조회
    public function record_buy($params){
    	$config = array(
    			'url'    => ST_ACA_API_HOST."/api/record/".$params['user_id']."/buy/record"
    			,'method' => 'GET'
    			,'data'   => array()
    	);
    	    	
    	$this->CI->strestclient->setData($config['url'], $config['method'], $params);
    	return $this->CI->strestclient->execute();
    }
    
    // 단기코드로 강사 리스트 획득
    public function teacher_bizcode($biz_code = null, $params = array()) {

		$config = array(
			'url'    => ST_ACA_API_HOST."/api/teacher/bizcode/" . $biz_code
			,'method' => 'GET'
			,'data'   => array()
		);

        if($biz_code) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $params);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }
    }
    // 과목아이디로 강사 리스트 획득
    public function teacher_subject($subject_id = null, $params = array()) {

        $config = array(
            'url'    => ST_ACA_API_HOST."/api/teacher/subject/" . $subject_id
            ,'method' => 'GET'
            ,'data'   => array()
        );

        if($subject_id) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $params);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }
    }

    // GET / 강좌상태별 카운트
    public function api_saleinfos_state_count($biz_codes = null, $org_id = null, $subject_id = null) {

		$config = array(
			'url'    => ST_ACA_API_HOST."/api/saleinfos/state/".$biz_codes ."/count"
			,'method' => 'GET'
			,'data'   => array()
		);
        

        if($org_id) {
            $config['data']['org_id'] = $org_id;
        }
        if($subject_id) {
            $config['data']['subject_id'] = $subject_id;
        }

        if($biz_codes) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else {
            return $this->error_default;
        }
    }






	/**
     * saleinfo_by_store_category_ids : 상품 조회 by store_category_ids
     *
     * @param $store_category_ids : 학원 ID (복수 O) 구분자(,) ex) 1,2,3
     * @param $biz_code           : 단기코드 (복수 O) ex) ENGDANGI, PEETDANGI, MDDANGI
     * @param $cls_id     		  : 강좌 타입 ex) 11 입력시 단과반, 12 입력시 종합반의 리스트만 가져옴
     * @param $course_lecture     : 과목 (복수 O) 구분자(,) ex) 50,52,54  종합반은 과목이 없음
     * @param $course_domain	  : 영역 (복수 O) 5,4
     * @param $course_category    : 유형 (복수 O) 2,5,8
     * @param $course_level       : 레벨 (복수 O) 1,5,6
     * @param $s_time       : 수업시작시간
     * @param $e_time       : 수업시작시간
     * @param $course_week        : 검색요일 (복수 O) YYYYYYY 문자열로 표기 월화수목금토일 순서, Y:해당 요일 포함, M:해당요일을 포함하지 않아도 됨, N:미구현
     * @param $teacher_ids        : 선생님id (복수 O) 구분자(,)  ex) 56,45,46
     * @param $extra_infos        : 추가정보 amt:가격정보리스트
     * @param $page               : 페이지 번호
     * @param $offset             : 가져올 데이터 수
     * 
     * @access public
     *
     * @return mixed Value.
     */
    // function saleinfo_by_store_category_ids($params = array()) {
    function store_category_course_saleinfos($params = array()) {

		// if (!isset($params['biz_code'])) return $this->error_default;
		if (!isset($params['cls_id'])) return $this->error_default;
		if (!isset($params['store_category_ids'])) return $this->error_default;

        $config = array(
             'url'    => ST_ACA_API_HOST."/api/store_category/".$params['store_category_ids']."/course/saleinfos"
            ,'method' => 'GET'
            ,'data'   => array(
				'store_category_ids' 	=> $params['store_category_ids']
				,'cls_id'         	 	=> $params['cls_id']
            )
        );       

        if(isset($params['course_lecture'])){
			$config['data']['course_lecture'] = $params['course_lecture'];        
        }
        if(isset($params['course_week'])){
        	$config['data']['course_week'] = $params['course_week'];
        }
        if(isset($params['page'])){
        	$config['data']['page'] = $params['page'];
        }
        if(isset($params['offset'])){
        	$config['data']['offset'] = $params['offset'];
        }
        
        $this->CI->strestclient->setData($config['url'], $config['method'], $params);
        return $this->CI->strestclient->execute();
    }


    /**
	 * saleinfos : 상품 조회 by saleinfo_ids
	 *
	 * @param $saleinfo_ids       : 상품id (복수 O)
	 * @param $extra_infos		  : amt
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function saleinfos($saleinfo_ids, $extra_infos=null) {
		$config = array(
			'url' => ST_ACA_API_HOST."/api/saleinfos/".$saleinfo_ids,
			'method' => 'GET',
			'data' => array(
				'saleinfo_ids'=>$saleinfo_ids
			)
		);

		if($extra_infos) {
			$config['data']['extra_infos'] = $extra_infos;
		}

		if($saleinfo_ids) {
			$this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
			return $this->CI->strestclient->execute();
		} else {
			return $this->error_default;
		}
	}


	/**
	 * getSaleinfos_By_Teacher_ids : 상품의 상세 설명 조회
	 *
	 * @param mixed $saleinfo_id   : 상품id (복수 X) ex) 2,3,4
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	function saleinfo_saleinfo_descr($saleinfo_id) {
		$config = array(
			'url' => ST_ACA_API_HOST."/api/saleinfo/".$saleinfo_id."/saleinfo_descr",
			'method' => 'GET',
			'data' => array(
				'saleinfo_id' => $saleinfo_id
			)
		);

		if($saleinfo_id) {
			$this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
			return $this->CI->strestclient->execute();
		} else {
			return $this->error_default;
		}
	}


	/**
     * getCourseSaleinfos_by_teacher_ids : 상점 진열 카테고리에 강좌로 연결된 선생님의 강좌 상품 조회 (+ 연관 상품 정보 )
     *
     * @param $teacher_id 			: 선생님id
     * @param $biz_code				: 단기코드
     * @param $course_lecture		: 과목
     * @param $course_domain		: 영역
     * @param $course_category		: 유형
     * @param $course_level			: 레벨
     * @param $extra_infos			: 추가정보
     * @param $page 				: 페이지번호
     * @param $offset 				: 가져올 데이터 수
     * @access public
     *
     * @return mixed Value.
     */
    function getCourseSaleinfos_by_teacher_ids($teacher_id, $biz_code=null, $course_lecture=null, $course_domain=null, $course_category=null, $course_level=null, $extra_infos=null, $page=1, $offset=20) {
        $config = array(
            'url' => ST_ACA_API_HOST."/api/teacher/".$teacher_id."/course/saleinfos",
            'method' => 'GET',
            'data' => array(
            	'teacher_id' => $teacher_id
            )
        );
        
        if($biz_code) {
        	$config['data']['biz_code'] = $biz_code;
        }
        
        if($course_lecture) {
        	$config['data']['course_lecture'] = $course_lecture;
        }
        
       	if($course_domain) {
        	$config['data']['course_domain'] = $course_domain;
        }
        
        if($course_category) {
        	$config['data']['course_category'] = $course_category;
        }
        
        if($course_level) {
        	$config['data']['course_level'] = $course_level;
        }
        
        if($extra_infos) {
        	$config['data']['extra_infos'] = $extra_infos;
        }

        if($page) {
        	$config['data']['page'] = $page;
        }

        if($offset) {
        	$config['data']['offset'] = $offset;
        }

        if($teacher_id) {
            $this->CI->strestclient->setData($config['url'], $config['method'], $config['data']);
            return $this->CI->strestclient->execute();
        } else{
            return $this->error_default;
        }

    }

}
