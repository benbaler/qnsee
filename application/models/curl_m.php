<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curl_m extends CI_Model {

	private $ch;

	function __construct(){
		parent::__construct();
		$this->ch = curl_init();
	}

	function __destruct(){
		curl_close($this->ch);
	}

	function getContent($p_url, $p_post = array()){

		curl_setopt($this->ch,CURLOPT_URL,$p_url);
		curl_setopt($this->ch,CURLOPT_POST, count($p_post));
		curl_setopt($this->ch,CURLOPT_POSTFIELDS, http_build_query($p_post));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		// curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);

		$result = curl_exec($this->ch);

		if(curl_errno($this->ch)){
			// die(curl_error($this->ch));
		}

		return $result;
	}

}

/* End of file curl_m.php */
/* Location: ./application/models/curl_m.php */