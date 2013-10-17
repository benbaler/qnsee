<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dictionary_m extends CI_Model {

	private $api = "http://services.aonaware.com/DictService/DictService.asmx/Define";

	function __construct(){
		parent::__construct();

		$this->load->model('curl_m');
	}

	function getWordType($p_word){
		$xml = $this->curl_m->getContent($this->api, array('word' => $p_word));

		$simple = @simplexml_load_string($xml);
		$json = @json_encode($simple);
		$array = @json_decode($json,TRUE);

		if(isset($array['Definitions']['Definition'])){
			foreach ($array['Definitions']['Definition'] as $def) {
				if(@strpos($def['WordDefinition'], 'adj ') !== FALSE){
					return 'adj';
				}
			}
		}

		return '';
	}	

}

/* End of file dictionary_m.php */
/* Location: ./application/models/dictionary_m.php */