<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller {
    public $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->library('layout');
        $this->data['title'] = 'iOS Review King - Your ultimate "App Finder"';
    }
    
    public function index(){
        $this->layout->view('index/index', $this->data);
    }

    public function app_ranking(){
        $this->load->database();
        $this->load->helper('rating_to_star');
        $limit = 30;
        $offset = $this->uri->segment(3);
        if(!$offset){$offset = 0;}
        $this->load->library('pagination');
        $config['base_url'] = 'http://www.iosreviewking.com/index/app_ranking/';
        $config['total_rows'] = 300;
        $config['per_page'] = $limit;
        $config['num_links'] = 1;
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
        $config['first_link'] = '<<';
        $config['last_link'] = '>>';

        $this->pagination->initialize($config);
        $sql = <<<STR
            SELECT t.id as rank, a.id as app_id, a.name, a.icon_url_60 as icon_url, a.store_url, a.average_user_rating_for_current_version, a.user_rating_count_for_current_version
            FROM apps a, top_grossing_apps t 
            WHERE a.id = t.app_id 
            ORDER BY t.id ASC 
            LIMIT $offset, $limit
STR;
        $this->data['app_list'] = $this->db->query($sql)->result_array();
        $this->layout->view('index/app_ranking', $this->data);
    }

    public function app_detail($app_id){
        $this->load->database();
        $this->data['app'] = $this->db->where("id", $app_id)->get('apps')->row_array();
        $this->data['reviews'] = $this->db->where("app_id", $app_id)->order_by('helpful_rated_num','desc')->limit(30)->get('reviews')->result_array();
        $this->layout->view('index/app_detail', $this->data);
    }
}
