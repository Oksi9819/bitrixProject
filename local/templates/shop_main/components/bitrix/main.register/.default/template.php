<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arResult["SHOW_SMS_FIELD"] == true) {
    CJSCore::Init('phone_auth');
}
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/main.register/.default/script.js');
?>
<div class="col-sm-4">
    <div class="signup-form">
        <!--sign up form-->
        <h2><?= GetMessage('AUTH_REGISTER') ?></h2>
        <form action="#" id="register">
            <input type="text" name="reg_name" id="reg_name" placeholder="<?= GetMessage('REGISTER_FIELD_NAME') ?>">
            <input type="text" name="reg_lastname" id="reg_lastname" placeholder="<?= GetMessage('REGISTER_FIELD_LAST_NAME') ?>">
            <input type="email" name="reg_email" id="reg_email" placeholder="<?= GetMessage('REGISTER_FIELD_EMAIL') ?>">
            <input type="password" name="reg_pass" id="reg_pass" placeholder="<?= GetMessage('REGISTER_FIELD_PASSWORD') ?>">
            <input type="password" name="reg_pass_confirm" id="reg_pass_confirm"
                   placeholder="<?= GetMessage('REGISTER_FIELD_CONFIRM_PASSWORD') ?>">
            <button type="button" class="btn btn-default btn-reg"><?= GetMessage('AUTH_REGISTER_BTN') ?></button>
        </form>
    </div>
    <!--/sign up form-->
</div>