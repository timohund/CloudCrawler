<?php

namespace CloudCrawler\System\Url;

/**
 * Parser class to parser an url into an array.
 *
 * @author Timo Schmidt <timo-schmidt@gmx.net>
 */
class Parser {

	/**
	 * Parses an url to an array as parser_url but utf8 complined.
	 *
	 * @param string $url
	 * @return \CloudCrawler\System\Url\Url
	 */
	public function parse($url) {
		$result 	= new \CloudCrawler\System\Url\Url();
		$matches	= array();

		if (!is_string($url)  || !preg_match(
			'~^((?P<scheme>[^:/?#]+):(//))?((\\3|//)?(?:(?P<user>[^:]+):(?P<pass>[^@]+)@)?(?P<host>[^/?:#]*))(:(?P<port>\\d+))?' .
				'(?P<path>[^?#]*)(\\?(?P<query>[^#]*))?(#(?P<fragment>.*))?~u', $url, $matches)
		) {
			return array();
		}

		if(isset($matches['scheme'])) { 	$result->setScheme($matches['scheme']);	}
		if(isset($matches['user'])) { 		$result->setUser($matches['user']);	}
		if(isset($matches['pass'])) {	 	$result->setPassword($matches['pass']);	}
		if(isset($matches['host'])) { 		$result->setHost($matches['host']);	}
		if(isset($matches['port'])) { 		$result->setPort($matches['port']);	}
		if(isset($matches['path'])) { 		$result->setPath($matches['path']);	}
		if(isset($matches['query'])) { 		$result->setQuery($matches['query']);	}
		if(isset($matches['fragement'])) { 	$result->setFragment($matches['fragment']);	}

		return $result;
	}
}