<?php
require_once dirname(__FILE__).'/CloudCrawler/Domain/Crawler/CrawlDocument.php';
require_once dirname(__FILE__).'/CloudCrawler/System/Http/Client.php';
require_once dirname(__FILE__).'/CloudCrawler/System/Url/LinkUnifier.php';

require_once dirname(__FILE__).'/CloudCrawler/MapReduce/Emitor.php';
require_once dirname(__FILE__).'/CloudCrawler/MapReduce/StreamMapper.php';
require_once dirname(__FILE__).'/CloudCrawler/MapReduce/StreamReducer.php';

if($argv[1] == "map") {
	$mapper = new StreamMapper();
	$mapper->map();
} elseif ($argv[1] == "reduce") {
	$reducer = new StreamReducer();
	$reducer->reduce();
}