<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

if($argv[1] == "map") {
	$mapper = new \CloudCrawler\MapReduce\StreamMapper();
	$mapper->map();
} elseif ($argv[1] == "reduce") {
	$reducer = new \CloudCrawler\MapReduce\StreamReducer();
	$reducer->reduce();
}