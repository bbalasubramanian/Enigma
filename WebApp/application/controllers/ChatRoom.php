<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatRoom extends CI_Controller {

	private $openweathermap = 'hai';
	

	public function index()
	{
		$this->load->view('chatroom_view');
	}

	public function talktobot()
	{
		$weather_api_url_const = 'http://api.openweathermap.org/data/2.5/weather?appid=4489db322dd9e0497801ca954a2dd6eb&units=metric&q=';
		$wiki_url_const = 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=';
		$zomato_city_url_const = 'https://developers.zomato.com/api/v2.1/locations?query=';
		$zomato_search_url_const = 'https://developers.zomato.com/api/v2.1/search?entity_type=city&entity_id=';

		$req = $this->input->get('q');
		log_message('debug', 'Engima Req: '.$req);
		$req = urlencode ($req);
		log_message('debug', 'Engima URL Req: '.$req);

		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Authorization:Bearer 1db04e7e6ebd4f1695fb2b56ad31c9c0\r\n"
		  )
		);
		$context = stream_context_create($opts);
		$url = 'https://api.api.ai/api/query?v=20150910&query='.$req.'&lang=en&sessionId=a38929f8-d99a-47e7-bc9a-65d3a207e8ba&timezone=Asia/Calcutta';
		log_message('debug', 'Engima NLP URL: '.$url);
		
		$responseJson = file_get_contents($url, false, $context);
		$resp=json_decode($responseJson, false);

		//Parsing Json Parsing
		$action = $resp->result->action;
		$parameters = $resp->result->parameters;
		$speech = $resp->result->fulfillment->speech;
		log_message('debug', 'API Parameters: action :'.$action." speech: ".$speech);

		if($action == 'weather')
		{
			if($parameters != '' && isset($parameters->address->city))
			{
				$weather_api_url = $weather_api_url_const.urlencode($parameters->address->city);
				log_message('debug', 'Engima Weather URL: '.$weather_api_url);
				$responseJson = file_get_contents($weather_api_url, false, $context);
				$resp=json_decode($responseJson, false);


				$current_temp = $resp->main->temp;
				$weather = $resp->weather[0]->main;
				$icon = $resp->weather[0]->icon;
				$icon_url = "<img src='http://openweathermap.org/img/w/".$icon.".png'>";

				$data['current_temp'] = $current_temp;
				$data['weather'] = $weather;	
				$data['icon_url'] = $icon_url;
				log_message('debug', 'API Parameters: current_temp :'.$current_temp." weather: ".$weather." icon: ".$icon);
				
				log_message('debug', 'Weather icon_url :'.$icon_url);
				$this->load->view('card_climate_view',$data);
			}
			else
			{
				echo $speech;
			}
		}

		else if($action == 'web.search')
		{
			if($parameters != '' && isset($parameters->q))
			{
				$wiki_api_url = $wiki_url_const.urlencode($parameters->q);
				log_message('debug', 'Engima Wiki URL: '.$wiki_api_url);
				$responseJson = file_get_contents($wiki_api_url, false, $context);
				$resp=json_decode($responseJson, false);

				$pages = $resp->query->pages;

				foreach($pages as $obj){
				   $title = $obj->title;
				   $content = $obj->extract;
				}

				$data['title'] = $title;
				$content = substr($content,0,250).'...';
				if($content =='...'){
					$data['content'] = "No content available. Try later.";
				} else {
					$data['content'] = $content;
				}				
				log_message('debug', 'API Parameters: title :'.$title." content: ".$content);
			
				$this->load->view('card_wiki_view',$data);
			}
			else
			{
				echo $speech;
			}
		}
		else if($action == 'zomato.search')
		{
			if($parameters != '' && $parameters->cuisine!='' && isset($parameters->location->city))
			{
				$zomato_city_url = $zomato_city_url_const.urlencode($parameters->location->city);
				log_message('debug', 'Engima Zomato URL: '.$zomato_city_url);

				$opts = array(
				  'http'=>array(
				    'method'=>"GET",
				    'header'=>"user-key: b5541b579f69a498054baa498f851818\r\n".
				    "Accept: application/json\r\n"
				  )
				);
				$context = stream_context_create($opts);

				$responseJson = file_get_contents($zomato_city_url, false, $context);
				$resp=json_decode($responseJson, false);
				$entity_id = $resp->location_suggestions[0]->entity_id;

				$zomato_search_url = $zomato_search_url_const.urlencode($entity_id)."&cuisines=".urlencode($parameters->cuisine);
				log_message('debug', 'Engima Zomato Search URL: '.$zomato_search_url);
				$responseJson = file_get_contents($zomato_search_url, false, $context);

				$resp=json_decode($responseJson, false);

				$hotel_0['name'] = $resp->restaurants[0]->restaurant->name;
				$hotel_0['url'] = $resp->restaurants[0]->restaurant->url;
				$hotel_0['address'] = $resp->restaurants[0]->restaurant->location->address;
				$hotel_0['thumb'] = $resp->restaurants[0]->restaurant->thumb;
				$data['hotel_0'] = $hotel_0;

				$hotel_1['name'] = $resp->restaurants[1]->restaurant->name;
				$hotel_1['url'] = $resp->restaurants[1]->restaurant->url;
				$hotel_1['address'] = $resp->restaurants[1]->restaurant->location->address;
				$hotel_1['thumb'] = $resp->restaurants[1]->restaurant->thumb;
				$data['hotel_1'] = $hotel_1;

				$hotel_2['name'] = $resp->restaurants[2]->restaurant->name;
				$hotel_2['url'] = $resp->restaurants[2]->restaurant->url;
				$hotel_2['address'] = $resp->restaurants[2]->restaurant->location->address;
				$hotel_2['thumb'] = $resp->restaurants[2]->restaurant->thumb;
				$data['hotel_2'] = $hotel_2;

				$hotel_3['name'] = $resp->restaurants[3]->restaurant->name;
				$hotel_3['url'] = $resp->restaurants[3]->restaurant->url;
				$hotel_3['address'] = $resp->restaurants[3]->restaurant->location->address;
				$hotel_3['thumb'] = $resp->restaurants[3]->restaurant->thumb;
				$data['hotel_3'] = $hotel_3;

				$this->load->view('card_zomato_view',$data);
			}
			else
			{
				echo $speech;
			}
		}
	
		else if($action == 'restaurant.book')
		{
			echo $speech;			
		}
		else if($action == 'smarthome.switch')
		{
			echo $speech;
		}
		else {
			log_message('debug', 'Engima Resp: '.$speech);
			echo $speech;	
		}
	}
}

/* sample api
	weather = http://api.openweathermap.org/data/2.5/weather?appid=4489db322dd9e0497801ca954a2dd6eb&units=metric&q=Chennai
	wiki = https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=chennai
*/
