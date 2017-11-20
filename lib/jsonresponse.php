<?php

namespace Webpractik\Api;

/**
 * Class JsonResponse
 *
 * Решает
 * @package Webpractik\Main
 */
class JsonResponse extends Singleton
{
	private $response = [
		'status' => false,
		'errors'=> []
	];

	public function getResponse() {
		return $this->response;
	}

	public function getErrors() {
		return $this->response['errors'];
	}

	public function haveErrors() {
		return count($this->response['errors']) > 0;
	}

	public function addError($strError) {
		$this->response['errors'][] = $strError;
	}

	public function clearErrors() {
		$this->response['errors'] = [];
	}

	public function addParam($name, $value) {
		$this->response[$name] = $value;
	}

	public function setSuccess() {
		$this->response['status'] = true;
	}

	public function setFail() {
		$this->response['status'] = false;
	}

	public function send() {
		header('Content-Type: application/json');
		die(json_encode($this->response));
	}

	public function sendSuccess() {
		$this->setSuccess();
		$this->send();
	}
}