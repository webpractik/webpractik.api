<?php

namespace Webpractik\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;


class ApiRouter extends \CBitrixComponent
{
	/**
	 * @var array
	 */

	public $sefVariables = [];

	/**
	 * Массив роутов в формате:
	 * '\\ПутьКласса\\ИмяКласса' => 'адрес/роута/'
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
	 * Роутинг
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
			$reponse = new \Webpractik\Api\NotFoundRoute($this->sefVariables);
		} else {
			$reponse = new $className($this->sefVariables);
		}

		$reponse->handler();
		$reponse->response->send();
	}


}