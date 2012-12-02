<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller {
    public $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->library('layout');
        $this->data['title'] = 'iOS Review King';
    }
    
    public function index(){
        $this->layout->view('index/index', $this->data);
    }
}
