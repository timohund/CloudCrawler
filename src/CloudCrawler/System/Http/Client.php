<?php

class Client {
	/**
	 * @param $url
	 * @param $data
	 */
	protected function doCurlPost($url, $data) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_ENCODING,'gzip');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return $result;
	}

	public function post($url, $arguments = array()) {
		$segements = array();

		foreach($arguments as $key => $argument) {
			$segements[] = $key.'='.$argument;
		}

		$data = implode('&',$segements);
		return $this->doCurlPost($url, $data);
	}
}

