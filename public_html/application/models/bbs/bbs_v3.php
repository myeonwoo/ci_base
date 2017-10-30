<?php
//
class Bbs_v3 extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('ST_bbs_v3');
	}

    /****
    	* @Desc 	
    	* @param	
    		mid
			category_srl
			search_user_id
			search_title
			search_content
			search_title_content
			search_priority
			search_answer_yn
			search_teacher_id
			search_saleinfo_id
			search_refund_year
			search_refund_category
			search_extra_category
			offset
			limit
			sort_index
			order_type
			document_srl
			extra_param1
			extra_param2
			extra_param3
			extra_param4
			extra_param5
			extra_param6
			extra_param7
			extra_param8
			extra_param9
			extra_param10

    *****/
    public function document_lists($config){
    	return $this->st_bbs_v3->document_lists($config);
    }

   /**
	 * document_info       : 문서 보기
	 *
	 * @param mixed $param : 문서 정보
	 *
	 * @access public
	 *
	 * @return article data.
	 */
    public function document_info($param){
    	return $this->st_bbs_v3->document_info($param);
    }
	
	/**
	 * comment_lists        : 댓글 목록
	 *
	 * @param mixed $document_srl : 게시판 PK
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function comment_lists($document_srl){
		return $this->st_bbs_v3->comment_lists($document_srl);
	}
	
	/**
	 * document_insert     : 문서 저장
	 *
	 * @param mixed $param : 문서 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function document_insert($param){
		return $this->st_bbs_v3->document_insert($param);
	}
	
	/**
	 * files_upload     : 파일 업로드
	 *
	 * @param mixed $param : 파일 업로드 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function files_upload($param){
		return $this->st_bbs_v3->files_upload($param);
	}
	
	/**
	 * files_delete     : 파일 삭제
	 *
	 * @param mixed $param : 삭제할 파일 정보
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function files_delete($param){
		return $this->st_bbs_v3->files_delete($param);
	}
	
	
	/**
	 * document_update     : 문서 수정
	 *
	 * @param mixed $param : 문서 수정 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function document_update($param){
		return $this->st_bbs_v3->document_update($param);
	}
	
	/**
	 * document_delete     : 문서 삭제
	 *
	 * @param mixed $param : 문서 삭제 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function document_delete($param){
		return $this->st_bbs_v3->document_delete($param);
	}
	
	/**
	 * comment_insert     : 댓글 등록
	 *
	 * @param mixed $param : 댓글 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function comment_insert($param){
		return $this->st_bbs_v3->comment_insert($param);
	}
	
	/**
	 * comment_insert     : 댓글 수정
	 *
	 * @param mixed $param : 댓글 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function comment_update($param){
		return $this->st_bbs_v3->comment_update($param);
	}
	
	/**
	 * comment_insert     : 댓글 삭제
	 *
	 * @param mixed $param : 댓글 데이터
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function comment_delete($param){
		return $this->st_bbs_v3->comment_delete($param);
	}
	
	/**
	 * comment_insert     : 카테고리 목록
	 *
	 * @param mixed $mid : 게시판 mid
	 *
	 * @access public
	 *
	 * @return article data.
	 */
	function category_lists($mid){
		return $this->st_bbs_v3->category_lists($mid);
	}
	 /****
        * @Desc     최근 글 
        * @param    
    *****/
    public function articles_latest($mid=null, $limit=10, $extra_config = array())
    {
        if (!$mid) {
            return array();
        }
        $config = array();
        $config['mid'] = $mid;
        $config['offset'] = 0;
        $config['limit'] = $limit;
        $config['sort_index'] = 'regdate';
        $config['order_type'] = 'desc';

        if (isset($extra_config['search_teacher_id'])) $config['search_teacher_id'] = $extra_config['search_teacher_id'];
        if (isset($extra_config['search_priority'])) $config['search_priority'] = $extra_config['search_priority'];
        if (isset($extra_config['search_saleinfo_id'])) $config['search_saleinfo_id'] = $extra_config['search_saleinfo_id'];

        // 데이타 추출
        $bbs_result = $this->st_bbs_v3->document_lists($config);

        // 등록일 DT 입력
        foreach ($bbs_result['result']['result_list'] as $key => &$item) {
            try {
                $item['regdate_dt'] = new Datetime($item['regdate']);
            } catch(Exception $e) {
                $item['regdate_dt'] = new Datetime();
            }
        }

        // 분류값 입력
        $categories = $this->category_lists($config['mid'])['result']['category_list'];
        $tmp = array();
        foreach ($categories as $key => $value) {
            $category_srl = $value['category_srl'];
            $tmp[$category_srl] = $value;
        }
        $categories = $tmp;
        foreach ($bbs_result['result']['result_list'] as $key => &$article) {
            $category_srl = trim($bbs_result['result']['result_list'][$key]['category_srl']);
            if (isset($tmp[$category_srl])) {
                $bbs_result['result']['result_list'][$key]['category'] = $tmp[$category_srl];
            } else {
                $bbs_result['result']['result_list'][$key]['category'] = array();
            }
        }
        return $bbs_result['result']['result_list'];
    }
}

