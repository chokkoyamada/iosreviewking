<?php
class Tools extends CI_Controller {

    const BASE_URL = "https://itunes.apple.com/us/rss/";
    const LIMIT = 300;
    
    public function __construct(){
        parent::__construct();
        if(!$this->input->is_cli_request()){
            exit("only cli access is allowed.");
        }
        $this->load->database();
    }

    public function index(){
        $this->_update_app_chart("topfreeapplications", "top_free_apps");
        $this->_update_app_chart("toppaidapplications", "top_paid_apps");
        $this->_update_app_chart("topgrossingapplications", "top_grossing_apps");
    }

    /*
     * 指定したランキングフィードからランク情報を取得してデータをアップデート
     */
    private function _update_app_chart($url_parts, $table_name){
        $contents = json_decode(file_get_contents(self::BASE_URL . $url_parts . "/limit=" . self::LIMIT . "/json"), true);
        $result = array();
        $this->db->truncate($table_name);
        foreach($contents['feed']['entry'] as $key => $val){
            $tmp['id'] = $key + 1; 
            $tmp['app_id'] = $val['id']['attributes']['im:id'];
            $this->db->insert($table_name, $tmp);

            //appsテーブルにデータがない場合Insert
            if($this->db->where('id', $tmp['app_id'])->count_all_results('apps') === 0){
                $result = $this->_call_itunes_api($tmp['app_id']);
                $this->_insert_app($result['results'][0]);
            }
        }
    }

    /*
     * アプリIDを指定してAPIから情報を取得する
     */
    private function _call_itunes_api($app_id){
        return json_decode(file_get_contents("https://itunes.apple.com/lookup?id=".$app_id), true);
    }

    /*
     * appsテーブルにデータをInsert
     */
    private function _insert_app($json){
        if(!isset($json['averageUserRatingForCurrentVersion'])){
            $json['averageUserRatingForCurrentVersion'] = 0;
        }
        if(!isset($json['userRatingCountForCurrentVersion'])){
            $json['userRatingCountForCurrentVersion'] = 0;
        }
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
