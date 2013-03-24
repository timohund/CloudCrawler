<?php

namespace CloudCrawler\System\Url;

/**
 * The Unifier is responsible to generate unified urls
 * from link target, document url and base url.
 *
 * This is needed to be able to compare urls
 *
 * Example Input: http://www.google.de/foo/bar/.././foo.html
 * Example Output: http://www.google.de/foo/foo.html
 *
 */
class Unifier {

	/**
	 * @var \CloudCrawler\System\Url\Parser
	 */
	protected $urlParser;

	/**
	 * @param Parser $parser
	 */
	public function __construct(\CloudCrawler\System\Url\Parser $parser) {
		$this->urlParser = $parser;
	}

	/**
	 * @param string $targetHrefString
	 * @param string $sourceUrlString
	 * @param string $sourceBaseHrefString
	 */
	public function getUnifiedUrl($targetHrefString, $sourceUrlString, $sourceBaseHrefString) {
		$hrefIsCompleteUrl 	= $this->getIsCompleteUrlLink($targetHrefString);
		$hrefIsAbsolute		= $this->getIsAbsoluteLink($targetHrefString);

		$sourceUrl			= $this->urlParser->parse($sourceUrlString);
		$itemBaseHref		= $this->urlParser->parse($sourceBaseHrefString);

		if($hrefIsCompleteUrl) {
				//simplest case href containing complete url with schema
			if($this->getIsUrlContainingSchema($targetHrefString)) {
				$targetHref 	= $this->urlParser->parse($targetHrefString);
				return (string) $this->unifyPath($targetHref);
			} else {
				//we have an absolute href without schema
				if($itemBaseHref->getScheme() != '') {
					$resultUrlString 	= $itemBaseHref->getScheme().':'.$targetHrefString;
					$result 			= $this->urlParser->parse($resultUrlString);
					return (string) $this->unifyPath($result);
				} elseif(isset($sourceUrl)) {
					$resultUrlString	= $sourceUrl->getScheme().':'.$targetHrefString;
					$result 			= $this->urlParser->parse($resultUrlString);

					return (string) $this->unifyPath($result);
				} else {
					throw new Exception('Could not get schema from base href or linking url for href with schema');
				}
			}
		} elseif($hrefIsAbsolute) {
			$result = $this->urlParser->parse($targetHrefString);
			if($sourceUrlString != '' && $this->getIsCompleteUrlLink($sourceUrlString)) {
				if($sourceUrl->getScheme() != '') {
					$result->setScheme($sourceUrl->getScheme());
				}

				if($sourceUrl->getHost() != '') {
					$result->setHost($sourceUrl->getHost());
				}

				return (string) $this->unifyPath($result);
			}
		} else {
			//href is relative
			if($sourceBaseHrefString !== '') {
				$result = $this->getHrefRelativeTo($itemBaseHref, $targetHrefString);
				return (string) $this->unifyPath($result);
			} elseif($sourceUrlString !== '') {
				$result = $this->getHrefRelativeTo($sourceUrl, $targetHrefString);
				return (string) $this->unifyPath($result);
			} else {
				throw new Exception('Relative href and now url or base href present');
			}
		}
	}

	/**
	 * @param \CloudCrawler\System\Url\Url $relativeHrefBase
	 * @param string $targetHrefString
	 */
	public function getHrefRelativeTo($relativeHrefBase, $targetHrefString) {
		$newTarget = '';
		if ($relativeHrefBase->getPath() !== '') {
			$newTarget .= $this->stripFileNameFromPath($relativeHrefBase->getPath());
		}
		$newTarget .= $targetHrefString;

		$result 	= $this->urlParser->parse($newTarget);
		if ($relativeHrefBase->getScheme() != '') {
			$result->setScheme($relativeHrefBase->getScheme());
		}

		if ($relativeHrefBase->getHost() !== '') {
			$result->setHost($relativeHrefBase->getHost());
		}

		return $result;
	}

	/**
	 * @param \CloudCrawler\System\Url\Url $result
	 * @return \CloudCrawler\System\Url\Url mixed
	 */
	public function unifyPath($result) {
		$path = $result->getPath();
		$unifiedPath = $this->getUnifiedPath($path);
		$result->setPath($unifiedPath);

		return $result;
	}

	/**
	 * Removes everything behind the last slash in the path (the filename) from a given string.
	 *
	 * @param string $path
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
	 * When the urls first / is followed by a second slash, it's an an absolute url.
	 *
	 * @param string $urlString
	 * @return bool
	 */
	protected function getIsCompleteUrlLink($urlString) {
		$matches = array();
		preg_match('~[^/]*//~',$urlString,$matches);
		return count($matches) > 0;
	}

	/**
	 * This method is used to check if a link is an absolute link, starting mit / (but not // what
	 * referes to a domain)
	 *
	 * @param string $urlString
	 * @return bool
	 */
	protected function getIsAbsoluteLink($urlString) {
		$urlString = trim($urlString);
		$isStaringWithSlash = strpos($urlString,'/') === 0;
		$isStartingWithDoubleSlash = strpos($urlString,'//') === true;

		return $isStaringWithSlash && !$isStartingWithDoubleSlash;
	}

	/**
	 * @param string $urlString
	 * @return bool
	 */
	protected function getIsUrlContainingSchema($urlString) {
		return strpos($urlString,'://') > 0;
	}
}