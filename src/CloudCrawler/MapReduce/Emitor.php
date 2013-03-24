<?php

namespace CloudCrawler\MapReduce;

class Emitor {

	/**
	* @var array
	*/
	protected $emits;

	protected function indicateProgress() {
		echo chr(0).PHP_EOL;
	}

	protected function emit($key, $value) {
		echo $key,chr(9),$value,PHP_EOL;
	}

	protected function onStartEmit() {
		$this->emits = array();
	}

	protected function onEndEmit() {
		foreach($this->emits as $key => $value) {
			$this->emit($key, $value);
		}

		$this->emits = array();
	}

	protected function persist($object) {
		return base64_encode(serialize($object));
	}

	protected function wakeup($sting) {
		$result = @unserialize(base64_decode($sting));

		if(is_object($result)) {
			return $result;
		} else {
			return false;
		}
	}
}


