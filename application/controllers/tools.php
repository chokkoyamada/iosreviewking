<?php
class Tools extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        if(!$this->input->is_cli_request()){
            exit("only cli access is allowed.");
        }
        $this->load->database();
    }

    public function index(){
        $app_id_list = $this->get_app_chart();
        foreach($app_id_list as $app_id){
            $result = $this->call_itunes_api($app_id);
            $this->insert($result['results'][0]);
        }
    }

    private function get_app_chart(){
        $contents = file_get_contents("http://www.apple.com/itunes/charts/paid-apps/");
        preg_match_all('/id(?P<app_id>\d{9})\?mt=/', $contents, $matches);
        $unique_id = array_unique($matches['app_id']);
        $app_id_list = array();
        foreach($unique_id as $val){
            $app_id_list[] = $val; 
        }
        return $app_id_list;
    }

    private function call_itunes_api($app_id){
        return json_decode(file_get_contents("https://itunes.apple.com/lookup?id=".$app_id), true);
    }

    private function insert($json){
        $data = array(
            "id" => $json['trackId'],
            "name" => $json['trackCensoredName'], 
            "developer_name" => $json['artistName'],
            "publisher_name" => $json['sellerName'],
            "icon_url_60" => $json['artworkUrl60'],
            "icon_url_100" => $json['artworkUrl100'],
            "icon_url_512" => $json['artworkUrl512'],
            "average_user_rating" => $json['averageUserRating'],
            "average_user_rating_for_current_version" => $json['averageUserRatingForCurrentVersion'],
            "bundle_id" => $json['bundleId'],
            "description" => $json['description'],
            "price" => $json['price'],
            "release_datetime" => $json['releaseDate'],
            "store_url" => $json['trackViewUrl'],
            "version" => $json['version'],
            "user_rating_count" => $json['userRatingCount'],
            "user_rating_count_for_current_version" => $json['userRatingCountForCurrentVersion']
        );
        $this->db->insert("apps", $data);
    }
}
