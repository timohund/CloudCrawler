<?php

namespace CloudCrawler\Domain\Crawler;

class CrawlDocument {

	/**
	 * @var
	 */
	protected $url = '';

	/**
	 * @var int
	 */
	protected $incomingLinkCount = 0;

	/**
	 * @var int
	 */
	protected $visitCount = 0;

	/**
	 * @var int
	 */
	protected $linkAnalyzeCount = 0;

	/**
	 * @var array
	 */
	protected $incomingLinks = array();

	/**
	 * @var
	 */
	protected $rawContent;

	/**
	 * @param int $incomingLinkCount
	 */
	public function setIncomingLinkCount($incomingLinkCount) {
		$this->incomingLinkCount = $incomingLinkCount;
	}

	/**
	 * @return int
	 */
	public function getIncomingLinkCount() {
		return $this->incomingLinkCount;
	}

	/**
	 * @param array $incomingLinks
	 */
	public function setIncomingLinks($incomingLinks) {
		$this->incomingLinks = $incomingLinks;
	}

	/**
	 * @return array
	 */
	public function getIncomingLinks() {
		return $this->incomingLinks;
	}

	/**
	 * @param int $visitCount
	 */
	public function setVisitCount($visitCount) {
		$this->visitCount = $visitCount;
	}

	/**
	 * @return int
	 */
	public function getVisitCount() {
		return $this->visitCount;
	}

	/**
	 * @return boolean
	 */
	public function getWasVisited() {
		return $this->getVisitCount() > 0;
	}

	/**
	 * @param  $rawContent
	 */
	public function setRawContent($rawContent) {
		$this->rawContent = $rawContent;
	}

	/**
	 * @return
	 */
	public function getRawContent() {
		return $this->rawContent;
	}

	/**
	 * @param int $linkAnalyzeCount
	 */
	public function setLinkAnalyzeCount($linkAnalyzeCount) {
		$this->linkAnalyzeCount = $linkAnalyzeCount;
	}

	/**
	 * @return int
	 */
	public function getLinkAnalyzeCount() {
		return $this->linkAnalyzeCount;
	}

	/**
	 * @param $url
	 */
	public function addIncomingLink($url) {
		$this->incomingLinks[$url] = $url;
	}

	public function incrementAnalyzeCount() {
		$this->linkAnalyzeCount++;
	}

	/**
	 * @param  $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return
	 */
	public function getUrl() {
		return $this->url;
	}

}