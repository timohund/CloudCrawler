<?php


namespace CloudCrawler\MapReduce;

/**
 * @package CloudCrawler\MapReduce
 * @author Timo Schmidt <timo-schmidt@gmx.net>
 */
class StreamReducer extends Emitor {

	/**
	 * This emitOrMerge method is needed because there is maybe a linkTarget
	 * document created in the stream before the allready crawled document i the
	 * stream gets processed. In this case the crawled document should
	 * resist and get the incoming links from the temporary created document.
	 *
	 * @param $key
	 * @param $crawlData
	 */
	protected function emitOrMerge($key, \CloudCrawler\Domain\Crawler\CrawlingDocument $crawlData) {
			/** @var $currentStoredCrawlData \CloudCrawler\Domain\Crawler\CrawlingDocument */

		if(isset($this->emits[$key])) {
			$currentStoredCrawlData = $this->wakeup($this->emits[$key]);

			if($currentStoredCrawlData instanceof \CloudCrawler\Domain\Crawler\CrawlingDocument && $currentStoredCrawlData->getWasCrawled()) {
				$masterDocument = $currentStoredCrawlData;
				$slaveDocument = $crawlData;
			} else {
				$masterDocument = $crawlData;
				$slaveDocument = $currentStoredCrawlData;
			}

			if($slaveDocument instanceof \CloudCrawler\Domain\Crawler\CrawlingDocument) {
				$incomingLinks = $slaveDocument->getIncomingLinks();
				foreach($incomingLinks as $incomingLink) {
					$masterDocument->addIncomingLink($incomingLink);
				}
			}
		} else {
			$masterDocument = $crawlData;
		}

		$this->emits[$key] = $this->persist($masterDocument);
	}

	/**
	 * The reduce method processes a stream of input data.
	 * The input data has a key,value pair.
	 *
	 * The key is the url and the value is an serializes, base64_encoded
	 * Crawling object.
	 */
	public function reduce() {
		$this->onStartEmit();

		while( ($line = fgets(STDIN)) != false) {
			$line = trim($line);
			if($line != '') {
				@list($url, $serializedCrawData) = explode(chr(9), $line);

				if(isset($url) && isset($serializedCrawData)) {
					/** @var $crawlData \CloudCrawler\Domain\Crawler\CrawlingDocument */

					$crawlData = $this->wakeup($serializedCrawData);
					if($crawlData->getLinkAnalyzeCount() == 0) {

							//todo chose the correct extractor automatically
						$extractor = new \CloudCrawler\Domain\Extractor\XHTMLExtractor();
						$extractor->injectLinkUnifier(new \CloudCrawler\System\Url\Unifier(
							new \CloudCrawler\System\Url\Parser()
						));
						$extractor->initialize(
							$crawlData->getRawContent(),
							$url,
							'text/html'
						);

						$links = $extractor->getOutgoingLinks();
						foreach($links as $link) {
								//todo temporary only follow de links
							if(strpos($link,".de") !== false ) {
									//temporary strip the querystring
								$link = preg_replace('/\?.*/', '', $link);
								if(isset($this->emits[$link])) {
									/** @var $targetCrawlData \CloudCrawler\Domain\Crawler\CrawlingDocument */
									$targetCrawlData = $this->wakeup($this->emits[$link]);
									//we have a crawled document as emit
									$targetCrawlData->addIncomingLink($url);
									$this->emitOrMerge($link, $targetCrawlData);

								} else {
									$newCrawlData = new \CloudCrawler\Domain\Crawler\CrawlingDocument();
									$newCrawlData->setUrl($link);
									$newCrawlData->addIncomingLink($url);
									$this->emitOrMerge($link, $newCrawlData);
								}

							}
						}

						//we don't need the content anymore
						$crawlData->setRawContent('');

						$crawlData->incrementLinkAnalyzeCount();
						$this->emitOrMerge($url, $crawlData);
					} else {
						$this->emitOrMerge($url, $crawlData);
					}
				}
			}

			$this->indicateProgress();
		}

		arsort($this->emits);
		$this->onEndEmit();
	}
}
