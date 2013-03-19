<?php

namespace CloudCrawler\Domain\Extractor;

class XMLExtractor extends \CloudCrawler\Domain\Extractor\AbstractExtractor {

	/**
	 * @var \CloudCrawler\System\Url\LinkUnifier
	 */
	protected $linkUnifier;

	/**
	 * @var DOMDocument
	 */
	protected $domDocument = null;

	/**
	 * @var DOMXPath
	 */
	protected $domXPath = null;

	/**
	 * @return array
	 */
	public function getSupportedMimeTypes() {
		return array('text/xml');
	}

	/**
	 * @param \CloudCrawler\System\Url\LinkUnifier $linkUnifier
	 */
	public function injectLinkUnifier(\CloudCrawler\System\Url\LinkUnifier $linkUnifier) {
		$this->linkUnifier = $linkUnifier;
	}

	/**
	 * Method to get the DOMDocument for the passed content
	 * in initialize method.
	 *
	 * @return \DOMDocument
	 */
	protected function getInitializedDOMDocument() {
		if($this->domDocument === null) {
			$this->domDocument = new \DOMDocument(1.0,'UTF-8');
			$this->domDocument->loadXML($this->getSourceRawContent());
		}

		return $this->domDocument;
	}

	/**
	 * Returns the initialized DOMXPath object for
	 * the passed content.
	 *
	 * @return \DOMXPath
	 */
	protected function getInitializedDOMXPath() {
		if($this->domXPath === null) {
			$this->domXPath = new \DOMXPath($this->getInitializedDOMDocument());
		}

		return $this->domXPath;
	}
}