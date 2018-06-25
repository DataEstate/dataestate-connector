<?php
/**
* DE API Caller Class
* @author Rolf Chen
* @version 1.0
*/
class De_api {
	private $_apiKey;
	private $_apiUrl;
	private $_estate_id;

	private static $_instance=null;
	public function __construct($api_key=null, $api_url=null, $estate_id=null) {
		if (!is_null($api_key)) {
			$this->_apiKey = $api_key;
		}
		if (!is_null($api_url)) {
			$this->_apiUrl = $api_url;
		}
		if (!is_null($estate_id)) {
			$this->_estate_id = $estate_id;
		}
	}
	public function get_api_key() {
		return $this->_apiKey;
	}

	public function get_estate_id() {
		return $this->_estate_id;
	}

	public function estates($params=[], $id=null, $command="data") {
		$endpoint="/estates/$command";
		$url = $this->_apiUrl.$endpoint;
		if (!is_null($id)) {
			$url.='/'.$id;
		}
		if (!is_null($this->_apiKey)) {
			$url.='?api_key='.$this->_apiKey;
			if (count($params) > 0) {
				$url.='&'.http_build_query($params);
			}
		}
		else {
			if (count($params) > 0) {
				$url.='?'.http_build_query($params);
			}
		}
		//echo $url;
		$request = wp_remote_get($url);
		$response = wp_remote_retrieve_body($request);
		return json_decode($response);
	}

	public function create_asset($params=[], $estate_id) {
		$endpoint='/assets/data';
		$url = $this->_apiUrl.$endpoint;

		if (!is_null($this->_apiKey)) {
			$url.='?api_key='.$this->_apiKey;
			if (count($params) > 0) {
				$url.='&'.http_build_query($params);
			}
		}
		else {
			if (count($params) > 0) {
				$url.='?'.http_build_query($params);
			}
		}
		$url.= "&estate_id=".$estate_id;

		return $url;
	}


	public function assets($params=[], $id=null, $header=false) {
		$endpoint='/assets/data';
		$url = $this->_apiUrl.$endpoint;
		if (!is_null($id)) {
			$url.='/'.$id;
		}
		if (!is_null($this->_apiKey)) {
			$url.='?api_key='.$this->_apiKey;
			if (count($params) > 0) {
				$url.='&'.http_build_query($params);
			}
		}
		else {
			if (count($params) > 0) {
				$url.='?'.http_build_query($params);
			}
		}
		$request = wp_remote_get($url);
		if ($header == true) {
			$response['header'] = $request['headers'];
			$response['body'] = wp_remote_retrieve_body($request);
			return $response;
		} else {
			$response = wp_remote_retrieve_body($request);
			return json_decode($response);
		}

	}
	public static function get_instance($api_key=null, $api_url=null, $estate_id = null) {
		if (null==self::$_instance)  {
			self::$_instance = new self($api_key, $api_url, $estate_id);
		}
		return self::$_instance;
	}
}
