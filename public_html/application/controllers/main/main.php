<?php
class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('validate');

		$this->dataset = array();
        $this->dataset['head'] = array(
            'is_top' => TRUE, // 상단 메뉴 여부
            'css' => array(),
            'js' => array(),
            'title' => '커넥츠 중단기 :: 중국어 1위의 근거 있는 자신감',
            'meta-keywords' => '',
            'meta-description' => '중국어 1위, 기초 중국어, 중국어회화, 신HSK 3급 4급 5급 6급, 중국어 교육 전문사이트',
        );

        $this->dataset['tail'] = array(
            'is_left' => TRUE, // 레프트 메뉴 여부
            'is_footer' => TRUE, //footer 내용 표시 여부
            'js' => array(),
        );
    }

    public function index()
    {
    	$this->main();
    }

    public function main(){
        $data = array();
        $data['os'] = check_browser();

        if (in_array($data['os'], array('ios','android'))) {
            $this->main_mobile();
        } else {
            $this->main_pc();
        }
    }

    public function main_pc(){
        
        $data = array();
        $params = array();

        $data['params'] = &$params;

        $data = $this->commondata->setHeaderData($this->dataset['head']);

        if ($params['type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $this->load->view('common/header', $data);
            $this->load->view('main/main', $data);
            $this->load->view('common/footer', $data);
        }

    }

    public function main_mobile(){
        $head = array (
            'meta_description' => '',
            'meta_keywords' => '',
        );

        $head = $this->commondata->setHeaderData($this->dataset['head']);
        // $tail = $this->commondata->setFooterData($this->dataset['tail']);
        
        $this->load->view('m/common/header', $head);
        $this->load->view('m/main/main', $data);
        $this->load->view('m/common/footer', $tail);
        return;
    }
}
