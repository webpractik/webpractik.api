<?php

namespace Webpractik\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;

/**
 * Class ApiRouter
 * @package Webpractik\Api
 */
abstract class ApiRouter extends \CBitrixComponent
{
	/**
	 * @var array
	 */

	public $sefVariables = [];

	/**
	 * Массив маршрутов в формате:
	 * '\\ПутьКласса\\ИмяКласса' => 'адрес/маршрута/'
	 * где адрес/маршрута без приставки /api/
	 *
	 * Пример:
	 * '\MySite\Lk\Response\Resubmit' => 'application/resubmit/',
	 * @var array
	 */
	public $arUrlTemplates = [];

	/**
	 * @var array
	 */
	public $arComponentVariables = [];

	/**
	 * Список модулей необходимых к загрузке
	 * @var array
	 * @todo придумать более элегантное решение
	 */
	public $arLoadModules = [];

	/**
	 * Execution component
	 */
	public function executeComponent() {
		Loader::includeModule('webpractik.api');
		foreach ($this->arLoadModules as $loadModule) {
			Loader::includeModule($loadModule);
		}
		$this->router();
	}

	/**
	 * Маршрутизация
	 */
	private function router() {
		$engine    = new \CComponentEngine($this);
		$className = $engine->ParseComponentPath(
			$this->arParams['SEF_FOLDER'],
			$this->arUrlTemplates,
			$this->sefVariables
		);

		if (!$className || strlen($className) <= 0) {
			$className = '\\Webpractik\\Api\\Error';
		}

		if (!class_exists($className)) {
			$response = new \Webpractik\Api\NotFoundRoute($this->sefVariables);
		} else {
			$response = new $className($this->sefVariables);
		}

		$response->validateMethod();
		if ($response->validate()) {
			$response->handler();
		} else {
			$response->response->sendFail('Невалидный запрос');
		}

		$response->response->send();
	}
}