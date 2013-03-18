<?php
	$srcRoot 	= dirname(__FILE__);
	$buildRoot 	= dirname(__FILE__)."/../build";

	$phar = new Phar(
		$buildRoot . "/artifacts/dist/bin/CloudCrawler.phar",
		FilesystemIterator::CURRENT_AS_FILEINFO |
		FilesystemIterator::KEY_AS_FILENAME, "CloudCrawler.phar"
	);

	$phar["index.php"] = file_get_contents($srcRoot . "/index.php");
	$phar["CloudCrawler/Domain/Crawler/CrawlDocument.php"] = file_get_contents($srcRoot . "/CloudCrawler/Domain/Crawler/CrawlDocument.php");
	$phar["CloudCrawler/System/Http/Client.php"] = file_get_contents($srcRoot . "/CloudCrawler/System/Http/Client.php");
	$phar["CloudCrawler/System/Url/LinkUnifier.php"] = file_get_contents($srcRoot . "/CloudCrawler/System/Url/LinkUnifier.php");
	$phar["CloudCrawler/MapReduce/Emitor.php"] = file_get_contents($srcRoot . "/CloudCrawler/MapReduce/Emitor.php");
	$phar["CloudCrawler/MapReduce/StreamMapper.php"] = file_get_contents($srcRoot . "/CloudCrawler/MapReduce/StreamMapper.php");
	$phar["CloudCrawler/MapReduce/StreamReducer.php"] = file_get_contents($srcRoot . "/CloudCrawler/MapReduce/StreamReducer.php");

	$phar->setStub($phar->createDefaultStub("index.php"));

