<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('layout');
    }
    
    public function index(){
        $this->layout->view('index/index');
    }
}
