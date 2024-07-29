<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
?>
    <div class="container">
        <div class="row">
            <? $APPLICATION->IncludeComponent(
                "bitrix:system.auth.form",
                "",
                array(
                    "FORGOT_PASSWORD_URL" => "/profile",
                    "PROFILE_URL" => "/profile",
                    "REGISTER_URL" => "/profile",
                    "SHOW_ERRORS" => "Y"
                ),
                false
            ); ?>
            <div class="col-sm-1">
                <h2 class="or"></h2>
            </div>
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.register",
                "",
                array(
                    "AUTH" => "Y",
                    "REQUIRED_FIELDS" => array("EMAIL", "NAME"),
                    "SET_TITLE" => "Y",
                    "SHOW_FIELDS" => array("EMAIL", "NAME", "SECOND_NAME", "LAST_NAME"),
                    "SUCCESS_PAGE" => "/profile",
                    "USER_PROPERTY" => array(),
                    "USER_PROPERTY_NAME" => "PROPERTY",
                    "USE_BACKURL" => "Y"
                )
            ); ?>
        </div>
    </div>
    </section><!--/form-->
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>