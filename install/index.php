<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Loader;

use Webpractik\Budget\Orm\PaymentTable;
use Webpractik\Budget\Orm\PaymentToTaskTable;

Loc::loadMessages(__FILE__);

if (class_exists('webpractik_api')) {
	return;
}

Class webpractik_api extends CModule
{
	/** @var string */
	public $MODULE_ID;
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_GROUP_RIGHTS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	function __construct() {
		$arModuleVersion = [];
		include(dirname(__FILE__) . "/version.php");

		$this->MODULE_NAME         = "Вебпрактик.API";
		$this->MODULE_DESCRIPTION  = "Модуль для удобного напсиания api";
		$this->MODULE_ID           = 'webpractik.api';
		$this->MODULE_VERSION      = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_NAME        = 'Webpractik';
		$this->PARTNER_URI         = 'http://webpractik.ru';
	}

	function DoInstall() {
		ModuleManager::registerModule($this->MODULE_ID);
		if (Loader::includeModule($this->MODULE_ID)) {

			$this->installFiles();
			$this->installRewrite();
		}
	}

	function DoUninstall() {
		$this->uninstallFiles();
		$this->uninstallRewrite();

		ModuleManager::unregisterModule($this->MODULE_ID);
		return true;
	}

	// FILES
	// =====
	function installFiles() {
		// установка роутера
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/api-router.php',
			'<' . '?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/' . $this->MODULE_ID . '/api-router.php");'
		);

		// установка компонента
		$content = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/' . $this->MODULE_ID . '/components/webpractik/api/class.php');

		\Bitrix\Main\IO\Directory::createDirectory($_SERVER["DOCUMENT_ROOT"] . '/local/components/webpractik/api/');
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/local/components/webpractik/api/class.php',
			$content
		);

		return true;
	}

	function unInstallFiles() {
		// удаление роутера
		unlink($_SERVER['DOCUMENT_ROOT'] . '/api-router.php');

		// удаление компонента
		unlink($_SERVER['DOCUMENT_ROOT'] . '/local/components/webpractik/api/class.php');
		rmdir($_SERVER['DOCUMENT_ROOT'] . '/local/components/webpractik/api/');
		return true;
	}


	// ROUTES

	/**
	 * Install SEF rewrite rule
	 */
	function installRewrite() {
		CUrlRewriter::Add([
			"SITE_ID"   => SITE_ID,
			"CONDITION" => "#^/api/#",
			"ID"        => "webpractik:api",
			"PATH"      => "/api-router.php",
			"RULE"      => ""
		]);
	}

	/**
	 * Remove SEF rewrite rule
	 */
	function uninstallRewrite() {
		CUrlRewriter::Delete(["CONDITION" => "#^/api/#"]);
	}
}