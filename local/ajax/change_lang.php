<?php
session_start();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

if ($_POST['equal_url'] == 'Y') {
    $newLang = trim($_POST['new_lang']);
    $_SESSION['LANG'] = $newLang;
}

