<?php

namespace CloudCrawler\System\Http;

class Client {
	/**
	 * @param $url
	 * @param $data
	 */
	protected function doCurlPost($url, $data) {


		$header = curl_init();
		curl_setopt ($header, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($header, CURLOPT_URL, $url);
		curl_setopt ($header, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt ($header, CURLOPT_TIMEOUT, 10);

//		curl_setopt ($header, CURLOPT_USERAGENT, "Cloudcrawler");
		curl_setopt ($header, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($header, CURLOPT_HEADER, true);
		curl_setopt($header, CURLOPT_NOBODY, true);
		curl_setopt($header, CURLOPT_FAILONERROR, true);
		curl_setopt($header, CURLOPT_HTTP200ALIASES,array(404,503));
		curl_exec ($header);
		$contentType = curl_getinfo($header, CURLINFO_CONTENT_TYPE);
		curl_close($header);

		if(strpos($contentType,'text/html') === false) {
			throw new \Exception('Unallowed content');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_USERAGENT, "Cloudcrawler");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);

		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HTTP200ALIASES,array(404,503));

		curl_setopt($ch, CURLOPT_ENCODING,'gzip');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

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

