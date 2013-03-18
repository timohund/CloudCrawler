<?php 

class StreamReducer extends Emitor {

	protected $linkUnifier = null;

	public function __construct() {
		$this->linkUnifier = new \CloudCrawler\System\Url\LinkUnifier();
	}

	/**
	 * This emitOrMerge method is needed because there is maybe a linkTarget
	 * document created in the stream before the allready crawled document i the
	 * stream gets processed. In this case the crawled document should
	 * resist and get the incomming links from the temporary created document.
	 *
	 * @param $key
	 * @param $crawlData
	 */
	protected function emitOrMerge($key, \CloudCrawler\Domain\Crawler\CrawlDocument $crawlData) {
			/** @var $currentStoredCrawlData \CloudCrawler\Domain\Crawler\CrawlDocument */

		if(isset($this->emits[$key])) {
			$currentStoredCrawlData = $this->wakeup($this->emits[$key]);

			if($currentStoredCrawlData instanceof \CloudCrawler\Domain\Crawler\CrawlDocument && $currentStoredCrawlData->getWasVisited()) {
				$masterDocument = $currentStoredCrawlData;
				$slaveDocument = $crawlData;
			} else {
				$masterDocument = $crawlData;
				$slaveDocument = $currentStoredCrawlData;
			}

			if($slaveDocument instanceof \CloudCrawler\Domain\Crawler\CrawlDocument) {
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


	public function reduce() {
		$this->onStartEmit();

		$counter=0;
		while( ($line = fgets(STDIN)) != false) {
			$counter++;
			$line = trim($line);
			if($line != '') {
				@list($url, $serializedCrawData) = explode(chr(9), $line);

				if(isset($url) && isset($serializedCrawData)) {
					/** @var $crawlData \CloudCrawler\Domain\Crawler\CrawlDocument */

					$crawlData = $this->wakeup($serializedCrawData);

					if($crawlData->getLinkAnalyzeCount() == 0) {
						$dom = new DOMDocument(1.0,'UTF-8');

						@$dom->loadHTML($crawlData->getRawContent());
						$domXPath = new DOMXPath($dom);
						$baseHref = '';
						$baseNodes = $domXPath->query('base');
						foreach($baseNodes as $node) {
							if($node->hasAttribute('href')) {
								$baseHref = (string) $node->getAttribute('href');
							}
						}

						$linkNodes = $domXPath->query('//a');
						foreach($linkNodes as $linkNode) {
							$href 		= $linkNode->getAttribute('href');
							$linkTarget = $this->linkUnifier->getUnifiedUrl($href,$url,$baseHref);

							if(isset($this->emits[$linkTarget])) {
									/** @var $targetCrawlData \CloudCrawler\Domain\Crawler\CrawlDocument */
								$targetCrawlData = $this->wakeup($this->emits[$linkTarget]);
									//we have a crawled document as emit
								$targetCrawlData->addIncomingLink($url);
								$this->emitOrMerge($linkTarget, $targetCrawlData);

							} else {
								$newCrawlData = new \CloudCrawler\Domain\Crawler\CrawlDocument();
								$newCrawlData->addIncomingLink($url);
								$this->emitOrMerge($linkTarget, $newCrawlData);
							}
						}

						//we don't need the content anymore
						$crawlData->setRawContent('');

						$crawlData->incrementAnalyzeCount();
						$this->emitOrMerge($url, $crawlData);
					} else {
						$this->emitOrMerge($url, $crawlData);
					}
				}
			}

			if($counter > 50) {
				arsort($this->emits);
				$this->onEndEmit();
				$counter=0;
			}
		}

		arsort($this->emits);
		$this->onEndEmit();
	}
}
