<?php

namespace Webpractik\Api;


use Bitrix\Main\Context;

abstract class Response
{
	/**
	 * @var \Webpractik\Api\JsonResponse
	 */
	public $response;

	/**
	 * @var \Bitrix\Main\HttpRequest
	 */
	public $request;

	/**
	 * Метод для автоматической проверки необходимого метода запроса (GET|POST)
	 * @var string
	 */
	public $method;

	public function __construct($arParams) {
		$this->response = \Webpractik\Api\JsonResponse::getInstance();
		$this->request = Context::getCurrent()->getRequest();
	}

	/**
	 * Тело логики обработки запроса
	 * @return void
	 */
	abstract public function handler();

	/**
	 * Метод описания валидации входных параметров.
	 * @return bool
	 */
	public function validate(){
		return true;
	}

	/**
	 * Валидация соответствия метода запроса
	 * @return bool
	 */
	public function validateMethod(){
		if (!$this->isMethodCorrect()) {
		    $this->response->sendFail('Не корректный запрос. Ожидается ' . $this->method . '.');
		}
		return true;
	}

	/**
	 * Проверка соответствия метода запроса
	 * @return bool
	 */
	public function isMethodCorrect(){
		if (!$this->method) {
			return true;
		}
		return $this->request->getRequestMethod() === $this->method;
	}
}