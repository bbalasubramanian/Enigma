<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebHook extends CI_Controller {

	public function index()
	{
		$this->load->view('chatroom_view');
	}

	public function climate()
	{
		$req = $this->input->get('city');
		log_message('debug', 'Webhook Climate Req: '.$req);
		$req = urlencode ($req);
		log_message('debug', 'Webhook URL climate Req: '.$req);

		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept-language: en\r\n" .
		              "Cookie: engima=bot\r\n" .
		              "User-agent: Mozilla /5.0 (Compatible MSIE 9.0;Windows NT 6.1;WOW64; Trident/5.0)\r\n"
		  )
		);
		$context = stream_context_create($opts);
		$responseJson = file_get_contents('http://api.openweathermap.org/data/2.5/weather?appid=4489db322dd9e0497801ca954a2dd6eb&units=metric&q='.$req, false, $context);
		$resp=json_decode($responseJson);
$resp1 = print_r($resp);
log_message('debug', 'Webhook climate Resp: '.$botResp);
		$botResp = $resp->main->temp;
		log_message('debug', 'Webhook climate Resp: '.$botResp);
		echo $botResp;

	}
}

