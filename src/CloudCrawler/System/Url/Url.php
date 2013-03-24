<?php

namespace CloudCrawler\System\Url;

class Url {

	/**
	 * @var string
	 */
	protected $rawUrlString = '';

	/**
	 * @var string
	 */
	protected $scheme = '';

	/**
	 * @var string
	 */
	protected $user = '';

	/**
	 * @var string
	 */
	protected $password = '';

	/**
	 * @var string
	 */
	protected $host = '';

	/**
	 * @var string
	 */
	protected $port = '';

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @var string
	 */
	protected $query = '';

	/**
	 * @var string
	 */
	protected $fragment = '';

	/**
	 * @param string $fragment
	 */
	public function setFragment($fragment) {
		$this->fragment = $fragment;
	}

	/**
	 * @return string
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $host
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param string $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @param string $query
	 */
	public function setQuery($query) {
		$this->query = $query;
	}

	/**
	 * @return string
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @param string $rawUrlString
	 */
	public function setRawUrlString($rawUrlString) {
		$this->rawUrlString = $rawUrlString;
	}

	/**
	 * @return string
	 */
	public function getRawUrlString() {
		return $this->rawUrlString;
	}

	/**
	 * @param string $scheme
	 */
	public function setScheme($scheme) {
		$this->scheme = $scheme;
	}

	/**
	 * @return string
	 */
	public function getScheme() {
		return $this->scheme;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return string
	 */
	public function getUser() {
		return $this->user;
	}


	/**
	 * @return string
	 */
	public function __toString() {
		$result 		= '';
		if($this->scheme != '') {
			$result .= $this->scheme.'://';
		}

		if($this->user != '' && $this->password != '') {
			$result .= $this->user.':'.$this->password.'@';
		}

		if($this->host != '') {
			$result .= $this->host;

			if($this->port != 0) {
				$result .= ':'.$this->port;
			}
		}

		if(substr($this->path,0,1) !== '/') {
			$result .= '/';
		}

		if($this->path != '') {		$result .= $this->path;}
		if($this->query != '') {	$result .= '?'.$this->query;}
		if($this->fragment != '') {	$result .= '#'.$this->fragment; }

		return $result;
	}
}