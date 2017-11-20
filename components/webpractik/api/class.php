<?php

namespace Webpractik\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;

Loader::includeModule('webpractik.api');

class ApiRouterExtended extends ApiRouter
{
	public $sefVariables = [];
	public $arUrlTemplates = [];
}