<?php

class StreamMapper extends Emitor {


	public function map() {
		$this->onStartEmit();
		$counter=0;

		while(($line = fgets(STDIN)) !== false){
			$counter++;
			@list($url, $value) = explode(chr(9), $line);

			if(isset($url)) {

				if(!isset($value) || $value === 0  || trim($value) == '') {
					$crawlData = new \CloudCrawler\Domain\Crawler\CrawlDocument();
				} else {
						/** @var $crawlData  \CloudCrawler\Domain\Crawler\CrawlDocument */
					$crawlData = $this->wakeup($value);
				}

				if(!$crawlData->getWasVisited()) {
					$httpClient = new Client();
					$content = $httpClient->post($url);
					$crawlData->setUrl($url);
					$crawlData->setRawContent($content);
					$crawlData->setIncomingLinkCount(1);
				}

				$this->emits[$url] = $this->persist($crawlData);
			}

			if($counter > 50) {
				ksort($this->emits);
				$this->onEndEmit();
				$counter = 0;
			}
		}

		ksort($this->emits);
		$this->onEndEmit();
	}
}
