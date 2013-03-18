#!/bin/bash
BASEDIR=$(dirname $0)

php "${BASEDIR}/CloudCrawler.phar" map 2>&1
