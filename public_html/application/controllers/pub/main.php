<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');
/**
 * pub Main Class
 *
 * @author   : jings
 * @date     : 2016. 12. 21.
 * @desc     :
 */
class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->head = array(
	        'is_top' => TRUE, // 상단 메뉴 여부
			'heatmap_yn' => TRUE,
			'css' => array(
			),
			'js' => array(
			),
			'title' => ' ',
			'meta-keywords' => '중국어,중국어회화,중국어인강,기초중국어,중국어공부,중국어독학,생활중국어,기초중국어회화,중국어배우기,중국어자격증,중국어기초,중국어교재,HSK,신HSK,HSK4급,HSK3급,HSK교재,HSK6급,HSK시험,HSK5급,HSK시험,중국어HSK,중국어학원,HSK인강,강남중국어학원,직장인중국어학원,강남중국어,강남역중국어,중국어발음,TSC,TSC인강,TSC학원,중국어인터넷강의,중국어강의,HSK정답,HSK답,무료중국어인강,중국어인강추천,HSK인강추천,중국어시험',
			'meta-description' => '중국어 1위, 기초 중국어, 중국어회화, 신HSK 3급 4급 5급 6급, 중국어 교육 전문사이트',
			'body_type' => 'wide',
			'main_active' => $main_active,
        );

        $this->tail = array(
			'is_left' => TRUE, // 레프트 메뉴 여부
             'is_footer' => TRUE, //footer 내용 표시 여부
             'js' => array(),
             //tgnb
             'my_class' => FALSE,
             'tgnb_open_tab_call' => '1',
             'tgnb_tab_open' => 'chia',
             'tgnb_focus_name' => 'lan'
             //tgnb
        );
    }

    public function index()
    {
    	$exception = $this->input->get('exception', true);
    	$path = (uri_string() === 'pub' OR uri_string() === 'pub/m')?'pub/tutorial':uri_string();
    	if (( ! empty($exception) && ($exception === 'head' OR $exception === 'all')) OR $path === 'pub/tutorial')
    	{
    		// do not include the header file.
    	}
    	else
    	{
	    	$head = $this->commondata->setHeaderData($this->head);
	    	$this->load->view('common/header', $head);
    	}

    	if ($path){$this->load->view($path);}

    	if (( ! empty($exception) && ($exception === 'foot' OR $exception === 'all')) OR $path === 'pub/tutorial')
    	{
    		// do not include the footer file.
    	}
    	else
    	{
	    	$tail = $this->commondata->setFooterData($this->tail);
	    	$this->load->view('common/footer', $tail);
    	}
    }

    public function m()
    {
    	$exception = $this->input->get('exception', true);
    	$path = uri_string();
    	if (( ! empty($exception) && ($exception === 'head' OR $exception === 'all')) OR $path === 'pub/tutorial')
    	{
    		// do not include the header file.
    	}
    	else
    	{
	    	widget::run('m/common/header');
    	}

    	if ($path){$this->load->view($path);}

    	if (( ! empty($exception) && ($exception === 'foot' OR $exception === 'all')) OR $path === 'pub/tutorial')
    	{
    		// do not include the footer file.
    	}
    	else
    	{
	    	widget::run('m/common/footer');
    	}
    }
}

/* End of file main.php */
/* Location: ./application/controllers/pub/main.php */