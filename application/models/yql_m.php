<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Yql_m extends CI_Model {

	private $api = 'http://query.yahooapis.com/v1/public/yql';
	private $key = 'b1365652301dcbd61e14c03beb7c090b';

	public function __construct()
	{
		parent::__construct();
	}

	function getAnswers($p_question){
		// $query .= urlencode('select * from answers.search where query="'.urldecode($question).'" and type="resolved" and sort="relevance"');


		// $question = str_replace('+', ' ', $question);

		// $url .= '&format=json';

		// echo "<pre>";

		// // var_dump($url);

		// $json = json_decode($this->curl_m->getContent($url), TRUE);

 	// 	// var_dump($json);

		// $answer = isset($json['query']['results']['Question']['ChosenAnswer']) ? $json['query']['results']['Question']['ChosenAnswer'] : $json['query']['results']['Question'][0]['ChosenAnswer'];
	}

	function getImages($p_keywords){

	}

}

/* End of file yql_m.php */
/* Location: ./application/models/yql_m.php */