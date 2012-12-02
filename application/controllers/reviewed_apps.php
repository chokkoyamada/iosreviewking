<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reviewed_apps extends CI_Controller {
    public $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->library('layout');
        $this->data['title'] = 'iOS Review King';
    }
    
    public function index(){
        //$this->layout->view('index/reviewed_apps', $this->data);
		$this->reviewed_apps();
    }

    public function reviewed_apps(){
        $limit = 10;
        $offset = $this->uri->segment(3);
        if(!$offset){$offset = 0;}
        $this->load->library('pagination');
        $config['base_url'] = 'http://www.iosreviewking.com/reviewed_apps/';
        $config['total_rows'] = 300;
        $config['per_page'] = $limit;
        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $this->load->database();
        $sql = <<<STR
            select app_id, name, icon_url_60 as icon_url, sum(helpful_rated_num) as num from reviews
			inner join apps on apps.id = app_id
			group by app_id
			order by num desc
			LIMIT $offset, $limit
STR;
		error_log($sql);
        $this->data['app_list'] = $this->db->query($sql)->result_array();
        $this->layout->view('index/reviewed_apps', $this->data);
    }

    public function app_detail($app_id){
        $this->load->database();
        $this->data['app'] = $this->db->where("id", $app_id)->get('apps')->row_array();
        $this->layout->view('index/reviewed_apps', $this->data);
    }

	public function top_reviewers()
	{

	}
}
