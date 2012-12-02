<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appstore_review extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('phpquery');
		$this->load->database();
	}

	public function index()
	{
		$apps = $this->db->get('apps')->result_array();
		foreach($apps as $val)
		{
			//echo("{$val['id']}\n");
			if(strlen($val['id']) === 9)
			{
				$is_scraped = $this->db->where("app_id", $val['id'])
						 ->where("is_scraped", '1')
						 ->get("batch_log")
						 ->row_array();
				if(!$is_scraped)
				{
					$has = $this->db->where('app_id', $val['id'])
							 ->get('batch_log')->row_array();
					if($has)
					{
						$this->db->where('app_id', $val['id'])
								 ->update('batch_log', array('is_scraped' => '1'));
					}
					else
					{
						$this->db->insert('batch_log', array('app_id' => $val['id'], 'is_scraped' => '1'));
					}
					$this->scrape($val['id']);
				}
			}	
		}
	}

	public function scrape($appli_id)
	{
		$init_url = BASE_URL . "?id={$appli_id}&pageNumber=0&sortOrdering=1&type=Purple+Software&onlyLatestVersion=true";
		$res = $this->init_scrape($init_url);

		//$content = $response->View->ScrollView->VBoxView->View->MatrixView->VBoxView->VBoxView->VBoxView;
		$pages = $res->View->ScrollView->VBoxView->View->MatrixView->VBoxView->VBoxView->asXML();
		preg_match("/Page.*of (.*)/", $pages, $paging);
		//echo(strip_tags($paging[1]) . "\n");
		$p = 0;
		$cnt = 0;
		if(isset($paging[1]))
		{
			for($i=0; $i<(int)$paging[1]; $i++)
			{
				$p++;
				$base_url = BASE_URL . "?id={$appli_id}&pageNumber={$i}&sortOrdering=1&type=Purple+Software&onlyLatestVersion=true";
				$response = $this->init_scrape($base_url);

				$content = $response->View->ScrollView->VBoxView->View->MatrixView->VBoxView->VBoxView->VBoxView;
				//$cnt = 0;
				foreach($content as $val)
				{
					$raw_xml = $val->asXML();
					//echo($val->TextView->SetFontStyle[0] . ", ");
					//echo($val->HBoxView->TextView->SetFontStyle->b . ", ");
					//echo($raw_xml);
					$insert['app_id'] = $appli_id;
					$insert['content'] = strip_tags($val->TextView->SetFontStyle[0]->asXML());
					$insert['subject'] = strip_tags($val->HBoxView->TextView->SetFontStyle->b->asXML());
					//var_dump($val);
					preg_match("/userProfileId=(.*)\"/", $raw_xml, $profile_id);
					$insert['profile_id'] = isset($profile_id[1]) ? $profile_id[1] : '';

					preg_match("/b>(.*) out of (.*) customers/", $raw_xml, $review_num);
					if(isset($review_num[1]) && isset($review_num[2]))
					{
						$review_num[1] = str_replace(",", "", $review_num[1]);
						$review_num[2] = str_replace(",", "", $review_num[2]);
						$insert['total_rated_num'] = $review_num[2];
						$insert['helpful_rated_num'] = $review_num[1];
					}
					else
					{
						$insert['total_rated_num'] = 0;
						$insert['helpful_rated_num'] = 0;  
					}
					$no_break = str_replace("\n", "", $raw_xml);	
					preg_replace("/\s/", "", $no_break);
					preg_match("/userProfileId=(.*)GotoURL/", $no_break, $name);
					if(isset($name[1]))
					{
						preg_match("/b>(.*)<\/b/", $name[1], $user_name);
						$insert['user_name'] = isset($user_name[1]) ? trim($user_name[1]) : 'no name';
					}
					else
					{
						$insert['user_name'] = 'no name';
					}
					//helpful_num//echo($raw_xml);
					$all_insert[] = $insert;
					//exit();
					$this->db->insert('reviews', $insert);
					$cnt++;
				}
				echo("appli_id: {$appli_id}, page: {$p}, num: {$cnt}\n");
				//var_dump($all_insert);
				//exit();
				//$all_insert = array();
			}
		}
	}

	public function insert_batch()
	{
		$sql = <<<SQL
select * from reviews group by app_id
SQL;
		$apps = $this->db->query($sql)->result_array();
		foreach($apps as $val)
		{
			echo("app_id: {$val['app_id']}\n");
			$this->db->insert('batch_log', array('app_id' => $val['app_id'], 'is_scraped' => 1));
		}
	}
	
	public function phpq($appli_id = '343200656')
	{
		$base_url = BASE_URL . "?id={$appli_id}&pageNumber=0&sortOrdering=1&type=Purple+Software";
		$response = $this->raw_scrape($base_url);
		//var_dump($response->html());
		$content = $response['View']['ScrollView']['VBoxView']['View']['MatrixView']['VBoxView']['VBoxView']['VBoxView'];
		//var_dump($content);
		foreach($content as $val)
		{
			var_dump(pq($val)->find('GotoURL')->html());
			exit();
		}	
	}

	private function init_scrape($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($ch, CURLOPT_USERAGENT, AP_USER_AGENT);
		curl_setopt($ch, CURLOPT_HEADER, AP_HEADER);
		$response = curl_exec($ch);
		curl_close($ch);
		return simplexml_load_string($response);
	}


	private function raw_scrape($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($ch, CURLOPT_USERAGENT, AP_USER_AGENT);
		curl_setopt($ch, CURLOPT_HEADER, AP_HEADER);
		$response = curl_exec($ch);
		curl_close($ch);
		
		//return $response;
		return \phpQuery::newDocument($response);
	}
}

