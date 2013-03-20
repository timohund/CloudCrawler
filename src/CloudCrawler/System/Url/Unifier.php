<?php

namespace CloudCrawler\System\Url;

class LinkUnifier {

	/**
	 * @param $href
	 * @param $itemUrl
	 * @param $itemBaseUrl
	 */
	public function getUnifiedUrl($href, $itemUrl, $itemBaseUrl) {
		$result 			= '';
		$hrefIsCompleteUrl 	= $this->getIsCompleteUrlLink($href);
		$hrefIsAbsolute		= $this->getIsAbsoluteLink($href);

		$hrefParts 			= $this->parseUtf8Url($href);
		$itemUrlParts		= $this->parseUtf8Url($itemUrl);
		$itemBaseUrlParts	= $this->parseUtf8Url($itemBaseUrl);

		if($hrefIsCompleteUrl) {
				//simplest case href containing complete url with schema
			if($this->getIsUrlContainingSchema($href)) {
				return $this->getPrettyPrintedUrl($href);
			} else {
				//we have an absolute href without schema
				if(isset($itemBaseUrlParts['scheme'])) {
					$result = $itemBaseUrlParts['scheme'].':'.$href;
					return $this->getPrettyPrintedUrl($result);
				} elseif(isset($itemUrlParts)) {
					$result = $itemUrlParts['scheme'].':'.$href;
					return $this->getPrettyPrintedUrl($result);
				} else {
					throw new Exception('Could not get schema from base href or linking url for href with schema');
				}
			}
		} elseif($hrefIsAbsolute) {
			if($itemUrl != '' && $this->getIsCompleteUrlLink($itemUrl)) {
				if(isset($itemUrlParts['scheme'])) {
					$result .= $itemUrlParts['scheme'].'://';
				}

				if(isset($itemUrlParts['host'])) {
					$result .= $itemUrlParts['host'];
				}

				$result .= $href;
				return $this->getPrettyPrintedUrl($result);
			}
		} else {
			//href is relative
			if($itemBaseUrl !== '') {
				if(isset($itemBaseUrlParts['scheme'])) {
					$result .= $itemBaseUrlParts['scheme'] .'://';
				}

				if(isset($itemBaseUrlParts['host'])) {
					$result .= $itemBaseUrlParts['host'];
				}

				if(isset($itemBaseUrlParts['path'])) {
					$result .= $this->stripFileNameFromPath($itemBaseUrlParts['path']);
				}

				$result .= $href;
				return $this->getPrettyPrintedUrl($result);
			} elseif($itemUrl !== '') {
				if(isset($itemUrlParts['scheme'])) {
					$result .= $itemUrlParts['scheme'] .'://';
				}

				if(isset($itemUrlParts['host'])) {
					$result .= $itemUrlParts['host'];
				}

				if(isset($itemUrlParts['path'])) {
					$result .= $this->stripFileNameFromPath($itemUrlParts['path']);
				}

				$result .= $href;
				return $this->getPrettyPrintedUrl($result);
			} else {
				throw new Exception('Relative href and now url or base href present');
			}
		}
	}

	/**
	 * @param $url
	 */
	protected function getPrettyPrintedUrl($url) {
		$resultParts 	= $this->parseUtf8Url($url);
		$result 		= '';

		if(isset($resultParts['scheme'])) {
			$result .= $resultParts['scheme'].'://';
		}

		if(isset($resultParts['user']) && isset($resultParts['pass'])) {
			$result .= $resultParts['user'].':'.$resultParts['pass'].'@';
		}

		if(isset($resultParts['host'])) {
			$result .= $resultParts['host'];

			if(isset($resultParts['port'])) {
				$result .= ':'.$resultParts['port'];
			}
		}

		if(isset($resultParts['path'])) {
			$path = $resultParts['path'];
			$path = $this->getUnifiedPath($path);
			if(strpos($path,'/') !== 0) {
				$path = '/'.$path;
			}
		} else {
			$path = '/';
		}
		$result .= $path;

		if(isset($resultParts['query'])) {
			$result .= '?'.$resultParts['query'];
		}

		if(isset($resultParts['fragment'])) {
			$result .= '#'.$resultParts['fragment'];
		}

		return $result;
	}

	/**
	 * Removes everything behind the last slash in the path (the filename) from a given string.
	 *
	 * @param $path
	 */
	protected function stripFileNameFromPath($path) {
		return substr($path,0,strrpos($path,"/")+1);
	}

	/**
	 * Resolves .. and . in pathes.
	 *
	 * @param string $path
	 * @return string $path
	 */
	protected function getUnifiedPath($path) {

		$parts = explode('/',$path);
		$resultStack = array();
		foreach($parts as $part) {
			if($part == '.') {
				continue;
			} elseif($part == '..') {
				if(count($resultStack) > 0) {
					array_pop($resultStack);
				}
			} else {
				array_push($resultStack,$part);
			}
		}

		return implode("/",$resultStack);
	}

	/**
	* @param $url
	* @return mixed
	 */
	protected function parseUtf8Url($url) {
		static $keys = array('scheme'=>0,'user'=>0,'pass'=>0,'host'=>0,'port'=>0,'path'=>0,'query'=>0,'fragment'=>0);
		if (is_string($url) && preg_match(
			'~^((?P<scheme>[^:/?#]+):(//))?((\\3|//)?(?:(?P<user>[^:]+):(?P<pass>[^@]+)@)?(?P<host>[^/?:#]*))(:(?P<port>\\d+))?' .
				'(?P<path>[^?#]*)(\\?(?P<query>[^#]*))?(#(?P<fragment>.*))?~u', $url, $matches)) {
			foreach ($matches as $key => $value) {
				if (!isset($keys[$key]) || empty($value)) {
					unset($matches[$key]);

				}
			}

			return $matches;
		}
	}

	/**
	 * When the urls first / is followed by a second slash, it's an an absolute url.
	 *
	 * @param $url
	 * @return bool
	 */
	protected function getIsCompleteUrlLink($url) {
		$matches = array();
		preg_match('~[^/]*//~',$url,$matches);
		return count($matches) > 0;
	}

	/**
	 * This method is used to check if a link is an absolute link, starting mit / (but not // what
	 * referes to a domain)
	 *
	 * @param $url
	 * @return bool
	 */
	protected function getIsAbsoluteLink($url) {
		$url = trim($url);
		$isStaringWithSlash = strpos($url,'/') === 0;
		$isStartingWithDoubleSlash = strpos($url,'//') === true;

		return $isStaringWithSlash && !$isStartingWithDoubleSlash;
	}

	/**
	 * @param string $url
	 * @return bool
	 */
	protected function getIsUrlContainingSchema($url) {
		return strpos($url,'://') > 0;
	}
}