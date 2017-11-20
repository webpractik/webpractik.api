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

	public function __construct($arParams) {
		$this->response = \Webpractik\Api\JsonResponse::getInstance();
		$this->request = Context::getCurrent()->getRequest();
	}

	abstract public function handler();
}