<?php

$srcRoot 		= dirname(__FILE__).'/../src';
$vendorRoot		= dirname(__FILE__).'/../vendor';
$buildRoot 		= dirname(__FILE__).'/../build';
$baseDir 		= dirname(__FILE__).'/..';

$phar = new Phar(
	$buildRoot . "/artifacts/dist/bin/CloudCrawler.phar",
	FilesystemIterator::CURRENT_AS_FILEINFO |
	FilesystemIterator::KEY_AS_FILENAME, "CloudCrawler.phar"
);

$vendorFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($vendorRoot));
$phar->buildFromIterator($vendorFiles, $baseDir);

$srcFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcRoot));
$phar->buildFromIterator($srcFiles, $baseDir);

$phar->setStub($phar->createDefaultStub("src/index.php"));

