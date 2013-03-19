<?php


namespace CloudCrawler\Domain\Crawler;

/**
 * A document that is used to store crawling document.
 *
 * @package CloudCrawler\Domain\Crawler
 * @author Timo Schmidt <timo-schmidt@gmx.net>
 */
class CrawlingDocument {

	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * @var string
	 */
	protected $rawContent = '';

	/**
	 * @var string
	 */
	protected $mimeType = '';

	/**
	 * @var int
	 */
	protected $incomingLinkCount = 0;

	/**
	 * @var int
	 */
	protected $crawlingCount = 0;

	/**
	 * @var int
	 */
	protected $linkAnalyzeCount = 0;

	/**
	 * @var array
	 */
	protected $incomingLinks = array();

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
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @return int
	 */
	public function getIncomingLinkCount() {
		return count($this->getIncomingLinks());
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
	public function setCrawlingCount($crawlingCount) {
		$this->crawlingCount = $crawlingCount;
	}

	/**
	 * @return int
	 */
	public function getCrawlingCount() {
		return $this->crawlingCount;
	}

	/**
	 * @return void
	 */
	public function incrementCrawlingCount() {
		$this->crawlingCount++;
	}

	/**
	 * @return boolean
	 */
	public function getWasCrawled() {
		return $this->getCrawlingCount() > 0;
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

	/**
	 * @return void
	 */
	public function incrementLinkAnalyzeCount() {
		$this->linkAnalyzeCount++;
	}

}