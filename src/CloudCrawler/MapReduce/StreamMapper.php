<?php

namespace CloudCrawler\MapReduce;

class StreamMapper extends Emitor {


	public function map() {
		$this->onStartEmit();
		$counter=0;

		while(($line = fgets(STDIN)) !== false){
			$counter++;
			@list($url, $value) = explode(chr(9), $line);

			echo "Handle url: ".$url.PHP_EOL;
			if(isset($url)) {

				if(!isset($value) || $value === 0  || trim($value) == '') {
					$crawlData = new \CloudCrawler\Domain\Crawler\CrawlingDocument();
				} else {
						/** @var $crawlData  \CloudCrawler\Domain\Crawler\CrawlingDocument */
					$crawlData = $this->wakeup($value);
				}

				try {
					if($crawlData !== false) {
						if(!$crawlData->getWasCrawled()) {
							$httpClient = new \CloudCrawler\System\Http\Client();
							$content = $httpClient->post($url);
							$crawlData->setUrl($url);
							$crawlData->setRawContent($content);
							$crawlData->incrementCrawlingCount();
						}

						$this->emits[$url] = $this->persist($crawlData);
					} else {
						$this->emits[$url] = false;
					}

				} catch (\Exception $e) {}
			}

			$this->indicateProgress();
		}

		ksort($this->emits);
		$this->onEndEmit();
	}
}
