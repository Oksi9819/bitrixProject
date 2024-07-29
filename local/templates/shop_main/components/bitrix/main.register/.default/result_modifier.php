<?php

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

if (isset($_POST)) {
    $reg_email = $_POST['reg_email'];
    $rsUser = CUser::GetByLogin($reg_email);
    if ($arUser = $rsUser->Fetch()) {
        echo 'Пользователь с логином "' . $reg_email . '" уже существует';
    } else {
        $reg_name = $_POST['reg_name'];
        $reg_lastname = $_POST['reg_lastname'];
        $reg_pass = $_POST['reg_pass'];
        $reg_pass_confirm = $_POST['reg_pass_confirm'];
        // Добавляем нового пользователя
        $user = new CUser;
        $arFields = array(
            "NAME" => $reg_name,
            "LAST_NAME" => $reg_lastname,
            "EMAIL" => $reg_email,
            "LOGIN" => $reg_email,
            "ACTIVE" => "Y",
            "GROUP_ID" => array(2, 3, 6),
            "PASSWORD" => $reg_pass,
            "CONFIRM_PASSWORD" => $reg_pass_confirm,
        );

        $ID = $user->Add($arFields);
        /*if (intval($ID) > 0) {
            $USER->Authorize($ID);
            LocalRedirect("/profile/");
        } else {
            return 'ОШИБКА: ' . $user->LAST_ERROR;
        }*/
    }
}






