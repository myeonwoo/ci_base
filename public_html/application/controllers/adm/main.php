<?php 
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('vd');
	}

	function index() {
		$data = $this->commondata->get_adm_header_data($data);
        $this->load->view(ADM_F.'/head', $data);
        $this->load->view(ADM_F.'/main', $data);
        $this->load->view(ADM_F.'/tail', $data);
        return;
	}
    /**
     * [info description]
        1.     GIT
        - A.     Repo :  git@git.st-company.net:global_dangicokr.git
          - B.     Develop 브랜치 생성 완료
          - C.     QA서버로 post-update 설정 완료

        2.     QA-WEB
          - A.     IP : 61.255.238.197
          - B.     DocumentRoot : /var/www/global_dangicokr/public_html
        - /img의 경우 design 계정의 sftproot/global_dangicokr 로 alias.url 설정
          - C.     DNS : qa-global.dangi.co.kr, qa-global.conects.com

        3.     QA-DB
          - A.     IP : 61.255.238.203
          - B.     Port : 14521
          - C.     DB명 : global_dangicokr
          - D.     계정 : dangicokr
          - E.     패스워드 : 기존과 같음.
     */
	public function info()
	{
		$this->vd->dump($_SERVER);
	}

	public function info_constant()
    {
        $dataset = array();
        $dataset['SITE_DOMAIN'] = SITE_DOMAIN;
        $dataset['SERVER_DOMAIN'] = SERVER_DOMAIN;
        $dataset['SITE_DIR'] = SITE_DIR;
        $dataset['MSG_TITLE'] = MSG_TITLE;
        $dataset['SKIN_ROOT'] = SKIN_ROOT;
        $dataset['JS_ROOT'] = JS_ROOT;
        $dataset['CSS_ROOT'] = CSS_ROOT;
        $dataset['IMG_ROOT'] = IMG_ROOT;
        $dataset['SKIN_PATH'] = SKIN_PATH;
        $dataset['JS_DIR'] = JS_DIR;
        $dataset['CSS_DIR'] = CSS_DIR;
        $dataset['DATA_DIR'] = DATA_DIR;
        $dataset['DATA_PATH'] = DATA_PATH;
        $dataset['FILE_IMG_DIR'] = FILE_IMG_DIR;
        $dataset['DANGI'] = DANGI;
        $dataset['SITE_NAME'] = SITE_NAME;
        $dataset['BIZ_CODE'] = BIZ_CODE;
        $dataset['ADM_F'] = ADM_F;
        $dataset['IMG_DIR'] = IMG_DIR;
        $dataset['M_IMG_DIR'] = M_IMG_DIR;
        $dataset['UPDATE_ROOT_PATH'] = UPDATE_ROOT_PATH;

        $data = $this->commondata->get_adm_header_data($data);
        $data['dataset'] = $dataset;

        $this->load->view(ADM_F.'/head', $data);
        $this->load->view(ADM_F.'/info/constant', $data);
        $this->load->view(ADM_F.'/tail', $data);
    }
}
