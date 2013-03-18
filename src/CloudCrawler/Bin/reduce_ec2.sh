#!/bin/bash
BASEDIR=$(dirname $0)
${HADOOP_HOME}/bin/hadoop distcp  s3://i2ee/bin/CloudCrawler.phar ${BASEDIR}
php "${BASEDIR}/CloudCrawler.phar" reduce 2>&1