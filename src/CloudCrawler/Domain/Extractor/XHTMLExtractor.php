<?php

namespace CloudCrawler\Domain\Extractor;

class XHTMLExtractor extends \CloudCrawler\Domain\Extractor\XMLExtractor {

	/**
	 * @return array
	 */
	public function getSupportedMimeTypes() {
		return array('text/html');
	}

	/**
	 * Method to get the DOMDocument for the passed content
	 * in initialize method.
	 *
	 * @return DOMDocument
	 */
	protected function getInitializedDOMDocument() {
		if($this->domDocument === null) {
			$this->domDocument = new \DOMDocument(1.0,'UTF-8');
			$content = $this->getSourceRawContent();
			@$this->domDocument->loadHTML($content);
		}

		return $this->domDocument;
	}

	/**
	 * @return string
	 */
	public function getBaseHref() {
		$baseHref 	= '';
		$baseNodes 	= $this->getInitializedDOMXPath()->query('//base');

		foreach($baseNodes as $node) {
			if($node->hasAttribute('href')) {
				$baseHref = (string) $node->getAttribute('href');
			}
		}

		return $baseHref;
	}

	/**
	 * @return array
	 */
	public function getOutgoingLinks() {
		$links 		= array();
		$baseHref	= $this->getBaseHref();
		$linkNodes 	= $this->getInitializedDOMXPath()->query('//a');

		foreach($linkNodes as $linkNode) {
			$href 		= $linkNode->getAttribute('href');
			$linkTarget = $this->linkUnifier->getUnifiedUrl($href,$this->getSourceUrl(),$baseHref);
			$links[] 	= $linkTarget;
		}

		return $links;
	}
}