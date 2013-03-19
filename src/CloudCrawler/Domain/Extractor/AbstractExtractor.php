<?php

namespace CloudCrawler\Domain\Extractor;

/**
 *
 * @package CloudCrawler\Domain\Extractor
 * @author Timo Schmidt <timo-schmidt@gmx.net>
 */
abstract class AbstractExtractor {

	/**
	 * @var string
	 */
	protected $sourceUrl;

	/**
	 * @var string
	 */
	protected $sourceRawContent;

	/**
	 * @var string
	 */
	protected $sourceMimeType;

	/**
	 * The implementation should return an array
	 * of mime types that are this extractor can und parse
	 *
	 * @return array<string>
	 */
	abstract function getSupportedMimeTypes();

	/**
	 * @return string
	 */
	public function getSourceMimeType() {
		return $this->sourceMimeType;
	}

	/**
	 * @return string
	 */
	public function getSourceRawContent() {
		return $this->sourceRawContent;
	}

	/**
	 * @return string
	 */
	public function getSourceUrl() {
		return $this->sourceUrl;
	}

	/**
	 * @param string $mimeType
	 * @return boolean
	 */
	public function getIsSupportedMimeType($mimeType) {
		$supportedMimeTypes = $this->getSupportedMimeTypes();
		if(!is_array($supportedMimeTypes)) {
			return false;
		}

		return in_array($mimeType, $supportedMimeTypes);
	}

	/**
	 * This method can be overwritten in a concrete extractor when
	 * the content should be filtered during the initialization.
	 *
	 * @param string $content
	 * @return bool
	 */
	protected function getIsSupportedContent($content) {
		return true;
	}

	/**
	 * Implement this method when something should be done
	 * after the initialization of the extractor.
	 *
	 * @param string $content
	 * @param string $mimeType
	 */
	protected function afterInitialize($content, $url, $mimeType) { }

	/**
	 * @param string $content
	 * @param string $mimeType
	 * @return boolean
	 */
	public function initialize($content, $url, $mimeType) {
		if(!$this->getIsSupportedMimeType($mimeType)) {
			return false;
		}

		$this->sourceRawContent = $content;
		$this->sourceUrl = $url;
		$this->sourceMimeType = $mimeType;

		$this->afterInitialize($content, $url, $mimeType);

		return true;
	}
}