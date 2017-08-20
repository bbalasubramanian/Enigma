<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatRoomPlain extends CI_Controller {

	public function index()
	{	
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
		print_r($resp);
		echo $resp->result->fulfillment->speech;
	}

	public function card_climate()
	{	
		$this->load->view('card_climate_view');
	}
}
