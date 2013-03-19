<?php

namespace CloudCrawler\Tests\Mocked\Classes\System;

class XHTMLExtractorTestCase extends \CloudCrawler\Tests\CloudCrawlerTestCase {

	/**
	 * @var \CloudCrawler\Domain\Extractor\XHTMLExtractor
	 */
	protected $extractor;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->extractor 	= new \CloudCrawler\Domain\Extractor\XHTMLExtractor();
		$this->extractor->injectLinkUnifier(new \CloudCrawler\System\Url\LinkUnifier());
	}

	/**
	 * @test
	 */
	public function getBaseHref() {
		$content 	= $this->getFixtureContent('www.congstar.de.html');
		if(!$this->extractor->initialize($content,'http://www.congstar.de','text/html')) {
			$this->fail('could not initialize extractor');
		}

		$this->assertEquals('http://www.congstar.de/', $this->extractor->getBaseHref());
	}

	/**
	 * @return array
	 */
	public function getOutgoingLinksDataProvider(){
		return array(
			'www.test.de.html' => array(
				'fixture' => 'www.test.de.html',
				'expectedOutgoingLinks' => array(
					'http://www.google.de/',
					'http://www.test.de/foo.html'
				)
			)
		);
	}

	/**
	 *
	 * @test
	 * @dataProvider getOutgoingLinksDataProvider
	 */
	public function getOutgoingLinks($fixture, $expectedOutgoingLinks) {
		$content = $this->getFixtureContent('www.test.de.html');
		if(!$this->extractor->initialize($content,'http://www.test.de/','text/html')) {
			$this->fail('could not initialize extractor');
		}

		$this->assertEquals($expectedOutgoingLinks, $this->extractor->getOutgoingLinks(),
		'Could not extract outgoing links from fixture file '.$fixture);

	}
}