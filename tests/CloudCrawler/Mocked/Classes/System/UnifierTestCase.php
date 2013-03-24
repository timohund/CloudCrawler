<?php

namespace CloudCrawler\Tests\Mocked\Classes\System;

class LinkUnifierTestCase extends \CloudCrawler\Tests\CloudCrawlerTestCase {

	/**
	 * @return array
	 */
	public function getUnifiedUrlDataProvider() {
		return array(
			 'simple test '=> array(
				'href' => 'one.html',
				'itemUrl' => 'http://www.test.de/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			'should handle ./' => array(
				'href' => './one.html',
				'itemUrl' => 'http://www.test.de/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			'should handle wrong ../' => array(
				'href' => '../one.html',
				'itemUrl' => 'http://www.test.de/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			'should handle multiple wrong ../' => array(
				'href' => '../one.html',
				'itemUrl' => 'http://www.test.de/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			'should handle ./ in folder' => array(
				'href' => './one.html',
				'itemUrl' => 'http://www.test.de/foo/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/one.html'
			),
			'should handle ../ in folder' => array(
				'href' => '../one.html',
				'itemUrl' => 'http://www.test.de/foo/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			'should handle normal folder link' => array(
				'href' => 'one',
				'itemUrl' => 'http://www.test.de/foo/',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/one'
			),
			'should handle query fragement' => array(
				'href' => 'pizza',
				'itemUrl' => 'http://www.test.de/foo/bar/d;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/bar/pizza'
			),
			'should handle query fragement with ./target' => array(
				'href' => './pizza',
				'itemUrl' => 'http://www.test.de/foo/bar/d;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/bar/pizza'
			),
			'should handle query fragement with target/ (trailing slash)' => array(
				'href' => 'pizza/',
				'itemUrl' => 'http://www.test.de/foo/bar/d;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/bar/pizza/'
			),
			' /one.html is linking to domain toplevel (when no baseurl is set )' => array(
				'href' => '/one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/d;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/one.html'
			),
			' /one.html is linking to correct target when base href was set' => array(
				'href' => '/one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.test.de/foo/',
					//should NOT got to http://www.test.de/foo/one.html
				'expectedResult' => 'http://www.test.de/one.html'
			),
			' one.html is linking to correct target when base href was set' => array(
				'href' => 'one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.test.de/foo/',
				'expectedResult' => 'http://www.test.de/foo/one.html'
			),
			' one.html is linking to correct target when base href was set to foreign domain' => array(
				'href' => 'one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.google.de/test/',
				'expectedResult' => 'http://www.google.de/test/one.html'
			),
			' ../one.html is linking to correct target when base href was set to foreign domain' => array(
				'href' => '../one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.google.de/test/',
				'expectedResult' => 'http://www.google.de/one.html'
			),
			' ../one.html is linking to correct target when base href was set to foreign domain complex' => array(
				'href' => '.././.././../../one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.google.de/test/two/',
				'expectedResult' => 'http://www.google.de/one.html'
			),
			' handle foreign href ' => array(
				'href' => 'http://www.heise.de/',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.google.de/test/',
				'expectedResult' => 'http://www.heise.de/'
			),
			' handle foreign href is adding trailing / ' => array(
				'href' => 'http://www.heise.de',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.google.de/test/',
				'expectedResult' => 'http://www.heise.de/'
			),
			' handle foreign href without schema and https baseurl ' => array(
				'href' => '//www.heise.de',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'https://www.google.de/test/',
				'expectedResult' => 'https://www.heise.de/'
			),
			' handle foreign href 2' => array(
				'href' => '//www.heise.de/foo?bar=bar@test',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => '',
				'expectedResult' => 'http://www.heise.de/foo?bar=bar@test'
			),
			'Semikolon' => array(
				'href' => ';x',
				'itemUrl' => 'http://www.test.de/foo/bar/bla;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://www.test.de/foo/bar/;x'
			),
			'Same target' => array(
				'href' => '././',
				'itemUrl' => 'http://one/two/three/four;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://one/two/three/'
			),
			'Use umlauts' => array(
				'href' => 'staßenfest.html',
				'itemUrl' => 'http://one/two/three/four;p?q',
				'baseHref' => '',
				'expectedResult' => 'http://one/two/three/staßenfest.html'
			),
			'Only href' => array(
				'href' => 'http://www.customer.de/Ohrstecker+"SMILEY"+925er+Sterlingsilber+24k+vergoldet/f4259fce60f4988c9fd9c30da0414e2af0bb0d8f,de_DE,pd.html',
				'itemUrl' => '',
				'baseHref' => '',
				'expectedResult' => 'http://www.customer.de/Ohrstecker+"SMILEY"+925er+Sterlingsilber+24k+vergoldet/f4259fce60f4988c9fd9c30da0414e2af0bb0d8f,de_DE,pd.html'
			),
			'Only href with complex path is corrected' => array(
				'href' => 'http://www.heise.de/../../test.html',
				'itemUrl' => '',
				'baseHref' => '',
				'expectedResult' => 'http://www.heise.de/test.html'
			),
			' /one.html is linking to correct target when base href was set and ../ in href' => array(
				'href' => '/foo/bar/../one.html',
				'itemUrl' => 'http://www.test.de/foo/bar/cola',
				'baseHref' => 'http://www.test.de/foo/',
				'expectedResult' => 'http://www.test.de/foo/one.html'
			),

		);
	}

	/**
	 * @param string $targetHref
	 * @param string $sourceUrl
	 * @param string $sourceBaseHref
	 * @param string $expectedResult
	 * @dataProvider getUnifiedUrlDataProvider
	 * @test
	 */
	public function getUnifiedUrl($targetHref, $sourceUrl, $sourceBaseHref, $expectedResult) {
		$unifier = new \CloudCrawler\System\Url\Unifier(new \CloudCrawler\System\Url\Parser());
		$result = $unifier->getUnifiedUrl($targetHref, $sourceUrl, $sourceBaseHref);
		$this->assertEquals($result, $expectedResult, 'Could not unify url '.$targetHref.' '.$sourceUrl.' '.$sourceBaseHref);
	}
}