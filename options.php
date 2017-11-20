<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\String;

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}
