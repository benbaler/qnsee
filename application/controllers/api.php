<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/api
	 *	- or -  
	 * 		http://example.com/index.php/api/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/api/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index2()
	{
		$this->load->model('curl_m');
		$this->load->model('dictionary_m');
		$this->load->model('stemmer_m');

		echo '<form method="post"><input type="text" name="question" value="'.@$this->input->post("question").'" size="100"><input type="submit"></form>';

		// $this->load->view('api_message');
		$url = 'http://query.yahooapis.com/v1/public/yql?q=';

		// $question = 'how tall is the eiffel tower';
		$question = $this->input->post('question') ? str_replace(' ','+', $this->input->post('question')) : 'test';

		$stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount", "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as", "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the", "i", "did");


		// $q = implode(' ', array_diff(explode(' ',$question), $stopwords));


		$url .= urlencode('select * from answers.search where query="'.urldecode($question).'" and type="resolved" and sort="relevance"');


		$question = str_replace('+', ' ', $question);

		$url .= '&format=json&debug=true';

		echo "<pre>";

		var_dump($url);

		$json = json_decode($this->curl_m->getContent($url), TRUE);

 		// var_dump($json);

		$answer = isset($json['query']['results']['Question']['ChosenAnswer']) ? $json['query']['results']['Question']['ChosenAnswer'] : $json['query']['results']['Question'][0]['ChosenAnswer'];

		// var_dump($answer);

		// $arr = array();

		// if(count($answer) > 0){
		// 	foreach ($answer as $word) {
		// 		$arr[] = $this->stemmer_m->Stem($word);	
		// 	}

		// 	$answer = $arr;
		// 	$arr = array();
		// }

		// if(count($question) > 0){
		// 	foreach ($question as $word) {
		// 		$arr[] = $this->stemmer_m->Stem($word);	
		// 	}

		// 	$question = $arr;
		// }


		$answer_arr = explode(' ', strtolower($answer));
		foreach ($answer_arr as &$word) {
			// $word = $this->stemmer_m->Stem($word);			
		}

		$question_arr = explode(' ', strtolower($question));
		foreach ($question_arr as &$word) {
			// $word = $this->stemmer_m->Stem($word);			
		}


		$keywords_answer = array_diff($answer_arr, $stopwords);
		$keywords_question = array_diff($question_arr, $stopwords);


		// var_dump($question);
		// var_dump($answer);
		var_dump(implode(' ',$keywords_question));
		var_dump(implode(' ',$keywords_answer));

		$intersect = implode(',', array_intersect($keywords_question, $keywords_answer));


		if(strlen($intersect) == 0){
			$intersect = implode(',',$keywords_question);
		}

		$words = array();

		foreach (explode(',', $intersect) as $word) {
			if($this->dictionary_m->getWordType($word) != 'adj'){
				$words[] = $word;
			}
		}

		if(count($words) > 0){
			$intersect = implode(',',array_slice($words,0,2));
		}

		var_dump($intersect);

		$url = 'http://query.yahooapis.com/v1/public/yql?q=';

		$url .= urlencode('select * from flickr.photos.search where text="'.urldecode($intersect).'" and sort="relevance" and api_key="b1365652301dcbd61e14c03beb7c090b"');

		$url .= '&format=json&debug=true';

		var_dump($url);

		$result = $this->curl_m->getContent($url);

		$json = json_decode($result, TRUE);

		// var_dump($json);

		$img = 'http://www.tshirtdesignsnprint.com/img/not-found.png';

		if(isset($json['query']['results'])){
			if(isset($json['query']['results']['photo'][0])){
				$img = "http://farm".$json['query']['results']['photo'][0]['farm'].".staticflickr.com/".$json['query']['results']['photo'][0]['server']."/".$json['query']['results']['photo'][0]['id']."_".$json['query']['results']['photo'][0]['secret'].".jpg";
			} else{
				$img = "http://farm".$json['query']['results']['photo']['farm'].".staticflickr.com/".$json['query']['results']['photo']['server']."/".$json['query']['results']['photo']['id']."_".$json['query']['results']['photo']['secret'].".jpg";
			}

		}
		echo $img;

		echo "</pre>";

		echo '<img src="'.$img.'"/>';
	}

	public function index()
	{
		$this->load->model('curl_m');
		$this->load->model('dictionary_m');
		$this->load->model('stemmer_m');

		// echo '<form method="post"><input type="text" name="question" value="'.@$this->input->get("question").'" size="100"><input type="submit"></form>';

		// $this->load->view('api_message');
		$url = 'http://query.yahooapis.com/v1/public/yql?q=';

		// $question = 'how tall is the eiffel tower';
		$question = $this->input->get('q') ? str_replace(' ','+', $this->input->get('q')) : 'test';

		$stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount", "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as", "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the", "i", "did");


		// $q = implode(' ', array_diff(explode(' ',$question), $stopwords));


		$url .= urlencode('select * from answers.search where query="'.urldecode($question).'" and type="resolved" and sort="relevance"');

		$question = str_replace('+', ' ', $question);

		$url .= '&format=json';

		// echo "<pre>";

		// var_dump($url);

		$json = json_decode($this->curl_m->getContent($url), TRUE);

 		// var_dump($json);

		$answer = isset($json['query']['results']['Question']['ChosenAnswer']) ? $json['query']['results']['Question']['ChosenAnswer'] : $json['query']['results']['Question'][0]['ChosenAnswer'];
		
		$answers = array();

		if(isset($json['query']['results']['Question']['ChosenAnswer'])){
			$answers[]['text'] = $json['query']['results']['Question']['ChosenAnswer'];
		} else{
			if(isset($json['query']['results']['Question'])){
				foreach ($json['query']['results']['Question'] as $index => $answer1) {
					$answers[]['text'] = isset($answer1['ChosenAnswer']) ? $answer1['ChosenAnswer'] : 'Oops! No answers.';
				}
			} else{
				$answers[]['text'] = 'Oops! No answers.';
			}
		}

		// $arr = array();

		// if(count($answer) > 0){
		// 	foreach ($answer as $word) {
		// 		$arr[] = $this->stemmer_m->Stem($word);	
		// 	}

		// 	$answer = $arr;
		// 	$arr = array();
		// }

		// if(count($question) > 0){
		// 	foreach ($question as $word) {
		// 		$arr[] = $this->stemmer_m->Stem($word);	
		// 	}

		// 	$question = $arr;
		// }
		// 

		$answer_arr = explode(' ', strtolower($answer));
		foreach ($answer_arr as &$word) {
			// $word = $this->stemmer_m->Stem($word);			
		}

		$question_arr = explode(' ', strtolower($question));
		foreach ($question_arr as &$word) {
			// $word = $this->stemmer_m->Stem($word);			
		}


		$keywords_answer = array_diff($answer_arr, $stopwords);
		$keywords_question = array_diff($question_arr, $stopwords);

		// var_dump($question);
		// var_dump($answer);
		// var_dump(implode(' ',$keywords_question));
		// var_dump(implode(' ',$keywords_answer));

		$intersect = implode(',', array_intersect($keywords_question, $keywords_answer));


		if(strlen($intersect) == 0){
			$intersect = implode(',',$keywords_question);
		}

		$words = array();

		foreach (explode(',', $intersect) as $word) {
			if($this->dictionary_m->getWordType($word) != 'adj'){
				$words[] = $word;
			}
		}

		if(count($words) > 0){
			$intersect = implode(',',$words);
		}

		// var_dump($intersect);

		$url = 'http://query.yahooapis.com/v1/public/yql?q=';

		$url .= urlencode('select * from flickr.photos.search where text="'.$intersect.'" and sort="relevance" and api_key="b1365652301dcbd61e14c03beb7c090b"');

		$url .= '&format=json';

		// var_dump($url);

		$result = $this->curl_m->getContent($url);

		$json = json_decode($result, TRUE);

		// var_dump($json);

		$img = 'http://www.tshirtdesignsnprint.com/img/not-found.png';

		$imgs = array();

		if(isset($json['query']['results'])){
			if(isset($json['query']['results']['photo'][0])){
				foreach ($json['query']['results']['photo'] as $index => $photo) {
					$imgs[]['url'] = "http://farm".$json['query']['results']['photo'][$index]['farm'].".staticflickr.com/".$json['query']['results']['photo'][$index]['server']."/".$json['query']['results']['photo'][$index]['id']."_".$json['query']['results']['photo'][$index]['secret'].".jpg";
				}
				$img = "http://farm".$json['query']['results']['photo'][0]['farm'].".staticflickr.com/".$json['query']['results']['photo'][0]['server']."/".$json['query']['results']['photo'][0]['id']."_".$json['query']['results']['photo'][0]['secret'].".jpg";
			} else{
				$img = "http://farm".$json['query']['results']['photo']['farm'].".staticflickr.com/".$json['query']['results']['photo']['server']."/".$json['query']['results']['photo']['id']."_".$json['query']['results']['photo']['secret'].".jpg";
				$imgs[]['url'] = "http://farm".$json['query']['results']['photo']['farm'].".staticflickr.com/".$json['query']['results']['photo']['server']."/".$json['query']['results']['photo']['id']."_".$json['query']['results']['photo']['secret'].".jpg";
			}
		} else{
			$imgs[]['url'] = $img;
		}


		$object = array(
			'answer' => $answer ? $answer : 'Opps! No answer was found.',
			'image' => $img
			);

		$object = array(
			'answers' => $answers,
			'images' => $imgs
			);

		echo json_encode($object);
		// echo $img;

		// echo "</pre>";

		// echo '<img src="'.$img.'"/>';
	}

	function qnsee(){
		$this->load->view('qnsee_v');
	}
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */