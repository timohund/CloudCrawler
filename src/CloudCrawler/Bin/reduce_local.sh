#!/bin/bash
BASEDIR=$(dirname $0)

php "${BASEDIR}/CloudCrawler.phar" reduce 2>&1
