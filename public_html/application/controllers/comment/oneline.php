<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oneline extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('validate');
		$this->load->model('comment/m_comment');
		$this->load->helper('array');

		$this->data = $this->commondata->setHeaderData(null);
		$this->data['admin_list'] = array('myeonwoo');
	}

	public function index()
	{
		$this->page();
	}

	/*
	*	한줄 게시판 
	*/
	public function page()
	{
		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
		$params['yn_reply'] = $this->validate->int($this->input->get_post('yn_reply', true), 0);
		$params['op_msg_cmt'] = $this->validate->string($this->input->get_post('op_msg_cmt', true), '댓글 작성해주세요.');
		$params['req_login'] = $this->validate->exist($this->input->get_post('req_login', true));
		$params['page'] = $this->validate->int($this->input->get_post('page', true), 1);
		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), $this->data['comment_config_id']);
        $params['limit'] = $this->validate->int($this->input->get_post('limit', true), 10);
        $params['offset'] = ($params['page']-1) * $params['limit'];

        // 데이타 조정
        if ($params['req_login'] && !$dataset['is_login']) {
        	$params['op_msg_cmt'] = '로그인 후 작성이 가능합니다 : D';
        }

        // 일반글
        $config = $params;
        $config['type'] = 3;
        $dataset['comments'] = $this->m_comment->get_list($config);
        $dataset['comments_total'] = $this->m_comment->get_list_total($config);
        $dataset['comments_id'] = array_column($dataset['comments'], 'comment_id');
        $dataset['comments_replies'] = $this->m_comment->get_comments_replies($dataset['comments_id']);

        // 공지글
        $config['type'] = 1;
        $config['limit'] = 10;
        $config['offset'] = 0;
        $dataset['notices'] = $this->m_comment->get_list($config);

		$pagination = array();
        $dataset['pagination'] = &$pagination;
        $pagination['tpage'] = ceil($dataset['comments_total']/$params['limit']);
        $pagination['cpage'] = $params['page'];
        $pagination['fpage'] = min(1, max(1, $pagination['cpage'] - 1));
        $pagination['ppage'] = max(1, $pagination['cpage'] - 1);
        $pagination['npage'] = min($pagination['tpage'], $pagination['cpage']+1);
        $pagination['lpage'] = $pagination['tpage'];
        $pagination['rf'] = max(1, $pagination['cpage']-2);   // range first
        $pagination['rl'] = min($pagination['tpage'], $pagination['rf'] + 9);  // range last

		if ($params['render_type'] == 'json') {
			$dataset['html'] = $this->load->view('/comment/oneline/page', $dataset, true);
			$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
		} else {
			$this->load->view('/comment/oneline/page', $dataset);
		}
	}
	// API
	public function page_list(){
		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'json');
		$params['op_msg_cmt'] = $this->validate->string($this->input->get_post('op_msg_cmt', true), '댓글 작성해주세요.');
		$params['req_login'] = $this->validate->exist($this->input->get_post('req_login', true));
		$params['page'] = $this->validate->int($this->input->get_post('page', true), 1);
		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), $this->data['comment_config_id']);
        $params['limit'] = $this->validate->int($this->input->get_post('limit', true), 10);
        $params['offset'] = ($params['page']-1) * $params['limit'];

        // 데이타 조정
        if ($params['req_login'] && !$dataset['is_login']) {
        	$params['op_msg_cmt'] = '로그인 후 작성이 가능합니다 : D';
        }

        // 일반글
        $config = $params;
        $config['type'] = 3;
        $dataset['comments'] = $this->m_comment->get_list($config);
        $dataset['comments_total'] = $this->m_comment->get_list_total($config);
        $dataset['comments_id'] = array_column($dataset['comments'], 'comment_id');
        $dataset['comments_replies'] = $this->m_comment->get_comments_replies($dataset['comments_id']);

        // 공지글
        $config['type'] = 1;
        $config['limit'] = 10;
        $config['offset'] = 0;
        $dataset['notices'] = $this->m_comment->get_list($config);

		$pagination = array();
        $dataset['pagination'] = &$pagination;
        $pagination['tpage'] = ceil($dataset['comments_total']/$params['limit']);
        $pagination['cpage'] = $params['page'];
        $pagination['fpage'] = min(1, max(1, $pagination['cpage'] - 1));
        $pagination['ppage'] = max(1, $pagination['cpage'] - 1);
        $pagination['npage'] = min($pagination['tpage'], $pagination['cpage']+1);
        $pagination['lpage'] = $pagination['tpage'];
        $pagination['rf'] = max(1, $pagination['cpage']-2);   // range first
        $pagination['rl'] = min($pagination['tpage'], $pagination['rf'] + 9);  // range last

		if ($params['render_type'] == 'html') {
			$this->load->view('/comment/oneline/page_list', $dataset);
		} else {
			$dataset['html'] = $this->load->view('/comment/oneline/page_list', $dataset, true);
			$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
		}
	}

	/************************
	 * API : 리스트
	 ************************/

	// API : 코멘트 입력
	public function insert_oneline_comment()
	{
		$this->load->model('comment/m_comment');

		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
		$params['type'] = $this->validate->int($this->input->get_post('type', true), 2);
		$params['author_id'] = $this->data['user']['user_id'];
		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), null);
		$params['nickname'] = $this->validate->string($this->input->get_post('nickname', true), null);
		$params['desc_content'] = $this->validate->string($this->input->get_post('desc_content', true), null);

		if ($params['comment_config_id'] && $params['nickname'] && $params['desc_content']) {
			$dataset['result'] = $this->m_comment->insert($params);
			$dataset['result_code'] = 1;
			$dataset['msg'] = '입력되었습니다.';
		} else {
			$dataset['result_code'] = 2;
			$dataset['msg'] = '올바르지 않은 요청입니다';
		}

		$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
	}
	public function delete_oneline_comment()
	{
		$this->load->model('comment/m_comment');

		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
		$params['comment_id'] = $this->validate->int($this->input->get_post('comment_id', true), null);

		if ($params['comment_id']) {
			$dataset['result'] = $this->m_comment->update($params['comment_id'], array('yn_deleted'=>1));
			$dataset['result_code'] = 1;
			$dataset['msg'] = '삭제되었습니다.';
		} else {
			$dataset['result_code'] = 2;
			$dataset['msg'] = '올바르지 않은 요청입니다.';
		}

		$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
	}

	// API: 답글 입력
	public function insert_oneline_comment_reply()
	{
		$this->load->model('comment/m_comment');

		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
 		$params['author_id'] = $this->data['user']['user_id'];
		$params['comment_id'] = $this->validate->int($this->input->get_post('comment_id', true), null);
		$params['nickname'] = $this->validate->string($this->input->get_post('nickname', true), null);
		$params['desc_content'] = $this->validate->string($this->input->get_post('desc_content', true), null);

		$dataset['item'] = $this->m_comment->get($params['comment_id']);

		if ($dataset['item'] && $params['comment_id']  && $params['nickname'] && $params['desc_content']) {
			$dataset['result'] = $this->m_comment->insert_reply($params);
			$dataset['result_code'] = 1;
			$dataset['msg'] = '입력되었습니다.';

			$dataset['comments_replies'] = $this->m_comment->get_comments_replies(array($params['comment_id']));
			$dataset['html'] = $this->load->view('/comment/oneline/page_comment_list', $dataset, true);
		} else {
			$dataset['result_code'] = 2;
			$dataset['msg'] = '올바르지 않은 요청입니다';
			$dataset['html'] = '';
		}

		$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
	}
	// API: 답글 삭제
	public function delete_oneline_comment_reply()
	{
		$this->load->model('comment/m_comment');

		$dataset = &$this->data;

		$params = array();
		$dataset['params'] = &$params;
 		$params['comment_id'] = $this->validate->int($this->input->get_post('comment_id', true), null);
		$params['comment_reply_id'] = $this->validate->int($this->input->get_post('comment_reply_id', true), null);

		$dataset['item'] = $this->m_comment->get_comment($params['comment_id']);

		if ($dataset['item'] && $params['comment_reply_id']) {
			$dataset['result'] = $this->m_comment->delete_comment_reply($params['comment_reply_id']);
			$dataset['result_code'] = 1;
			$dataset['msg'] = '삭제되었습니다.';

			$dataset['comments_replies'] = $this->m_comment->get_comments_replies(array($params['comment_id']));
			$dataset['html'] = $this->load->view('/comment/oneline/page_comment_list', $dataset, true);
		} else {
			$dataset['result_code'] = 2;
			$dataset['msg'] = '올바르지 않은 요청입니다';
			$dataset['html'] = '';
		}

		$this->output->set_content_type("application/json")->set_output(json_encode($dataset));return;
	}















}