<?php

namespace Webpractik\Api;

/**
 * Class JsonResponse
 *
 * @package Webpractik\Main
 */
class JsonResponse extends Singleton
{
	private $response = [
		'status' => false,
		'errors'=> []
	];

	/**
	 * @return array
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->response['errors'];
	}

	/**
	 * @return bool
	 */
	public function haveErrors() {
		return count($this->response['errors']) > 0;
	}

	/**
	 * @param $strError
	 * @return $this
	 */
	public function addError($strError) {
		$this->response['errors'][] = $strError;
		return $this;
	}
    
    /**
     * Добавление ошибки по ключу
     * @param $key
     * @param $strError
     * @return $this
     */
    public function addErrorByKey($key, $strError)
    {
        $this->response['errors'][$key] = $strError;
        return $this;
    }
	
	/**
	 * Очистка ошибок
	 * @return $this
	 */
	public function clearErrors() {
		$this->response['errors'] = [];
		return $this;
	}

	/**
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function addParam($name, $value) {
		$this->response[$name] = $value;
		return $this;
	}

	/** @return $this */
	public function setSuccess() {
		$this->response['status'] = true;
		return $this;
	}

	/** @return $this */
	public function setFail() {
		$this->response['status'] = false;
		return $this;
	}

	/**
	 * Посылает запрос на сервер и умирает
	 */
	public function send() {
		header('Content-Type: application/json');
		die(json_encode($this->response));
	}


	// Быстрые обработчики
	// ===================

	public function sendSuccess() {
		$this->setSuccess();
		$this->send();
	}

	public function sendFail($errorText) {
		$this->addError($errorText);
		$this->send();
	}
}