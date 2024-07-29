<?php
session_start();
CModule::IncludeModule('currency');
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/bootstrap.min.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/font-awesome.min.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/prettyPhoto.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/price-range.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/animate.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/main.css"); ?>
    <? $APPLICATION->SetAdditionalCss("/local/templates/shop_main/HTML/css/responsive.css"); ?>
    <!--[if lt IE 9]>
    <? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/html5shiv.js'); ?>
    <? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/respond.min.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/jquery.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/bootstrap.min.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/jquery.scrollUp.min.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/price-range.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/jquery.prettyPhoto.js'); ?>
<? $APPLICATION->AddHeadScript('/local/templates/shop_main/HTML/js/main.js'); ?>
    <![endif]-->
    <link rel="shortcut icon" href="/local/templates/shop_main/HTML/images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="/local/templates/shop_main/HTML/images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="/local/templates/shop_main/HTML/images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="/local/templates/shop_main/HTML/images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed"
          href="/local/templates/shop_main/HTML/images/ico/apple-touch-icon-57-precomposed.png">
    <? $APPLICATION->AddHeadScript('/local/catalog/shop_main/HTML/js/contact.js'); ?>
    <? $APPLICATION->ShowHead(); ?>
</head><!--/head-->

<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">
                        <ul class="nav nav-pills">
                            <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                            <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social-icons pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header_top-->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <a href="/local/templates/shop_main/HTML/index.html"><img
                                    src="/local/templates/shop_main/HTML/images/home/logo.png" alt=""/></a>
                    </div>
                    <div class="btn-group pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle language"
                                    data-toggle="dropdown" data-def-lang="<?= $GLOBALS["DEF_LANG"] ?>">
                                <? echo isset($_SESSION['LANG']) ? strtoupper($_SESSION['LANG']) : strtoupper(LANGUAGE_ID); ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <? foreach ($GLOBALS["LANG_LIST"] as $lang): ?>
                                    <li class="lang-menu" data-lang="<?= $lang ?>"><a href=""
                                                                                      class="switch-lang"><?= $lang ?></a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <?
                            if (isset($_SESSION['CURRENCY'])) {
                                echo CCurrency::SelectBox(
                                    "CURRENCY",
                                    $_SESSION['CURRENCY'],
                                    "",
                                    False,
                                    "",
                                    "class='dropdown-toggle switch-currency'"
                                );
                            } else {
                                echo CCurrency::SelectBox(
                                    "CURRENCY",
                                    CCurrency::GetBaseCurrency(),
                                    "",
                                    False,
                                    "",
                                    "class='dropdown-toggle switch-currency'"
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/profile/wishlist';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/profile/wishlist';
                                }
                                ?>"><i
                                            class="fa fa-star"></i> <?= GetMessage("HEADER_WISHLIST") ?></a></li>
                            <li><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/profile/compare';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/profile/compare';
                                }
                                ?>"><i class="fa fa-crosshairs"></i>
                                    <?= GetMessage("HEADER_COMPARE_LIST") ?></a></li>
                            <li><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/profile/cart';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/profile/cart';
                                }
                                ?>"><i
                                            class="fa fa-shopping-cart"></i> <?= GetMessage("HEADER_CART") ?></a></li>
                            <? if ($USER->IsAuthorized()) { ?>
                                <li><a href="<?
                                    if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                        echo '/profile';
                                    } else {
                                        echo '/' . LANGUAGE_ID . '/profile';
                                    }
                                    ?>"><i class="fa fa-user"
                                        ></i> <?= GetMessage("HEADER_ACCOUNT") ?>
                                    </a></li>
                            <? } else { ?>
                                <li><a href="<?
                                    if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                        echo '/profile';
                                    } else {
                                        echo '/' . LANGUAGE_ID . '/profile';
                                    }
                                    ?>"><i class="fa fa-lock"></i>
                                        <?= GetMessage("HEADER_LOGIN") ?></a></li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">
                            <li><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/';
                                } else {
                                    echo '/' . LANGUAGE_ID;
                                }
                                ?>"
                                   class="active"><?= GetMessage("HEADER_MAIN") ?></a></li>
                            <li class="dropdown"><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/catalog';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/catalog';
                                }
                                ?>"><?= GetMessage("HEADER_CATALOG") ?><i
                                            class="fa fa-angle-down"></i></a>
                                <ul role="menu" class="sub-menu">
                                    <li><a href="<?
                                        if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                            echo '/catalog';
                                        } else {
                                            echo '/' . LANGUAGE_ID . '/catalog';
                                        }
                                        ?>"><?= GetMessage("HEADER_CATALOG") ?></a></li>
                                    <li><a href="<?
                                        if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                            echo '/profile/cart';
                                        } else {
                                            echo '/' . LANGUAGE_ID . '/profile/cart';
                                        }
                                        ?>"><?= GetMessage("HEADER_CART") ?></a></li>
                                    <li>
                                        <a href="/local/templates/shop_main/HTML/login.html"><?= GetMessage("HEADER_LOGIN") ?></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/news';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/news';
                                }
                                ?>"><?= GetMessage("HEADER_NEWS") ?></a></li>
                            <li><a href="/local/templates/shop_main/HTML/404.html">404</a></li>
                            <li><a href="<?
                                if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                                    echo '/contacts';
                                } else {
                                    echo '/' . LANGUAGE_ID . '/contacts';
                                }
                                ?>"><?= GetMessage("HEADER_CONTACTS") ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="search_box pull-right">
                        <input type="text" placeholder="Search"/>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->

</header><!--/header-->

<script>
    $(document).ready(function () {
        let defLang = $('.language').attr('data-def-lang');
        let langList = $('.switch-lang');
        console.log(langList.length);
        let checkHrefArr = [];
        for (let j = 0; j < langList.length; j++) {
            let lang = langList[j];
            let val = lang.innerText;
            checkHrefArr.push(val.toLowerCase());
        }

        if (location.pathname.includes('checkout') || location.pathname.includes('order')) {

        }

        let consist;

        for (let m = 0; m < checkHrefArr.length; m++) {
            if (location.pathname.includes(checkHrefArr[m])) {
                consist = checkHrefArr[m];
            }
        }

        for (let i = 0; i < langList.length; i++) {
            let lang = langList[i];
            let val = lang.innerText;
            let pathname;
            if (consist !== '') {
                pathname = location.pathname.replace('/' + consist, '');
            } else {
                pathname = location.pathname;

            }

            let thisHref;
            if (val.toLowerCase() === defLang) {
                thisHref = location.origin + pathname;
            } else {
                thisHref = location.origin + '/' + val.toLowerCase() + pathname;
            }
            lang.setAttribute('href', thisHref);
        }

        $('.lang-menu').on('click', function () {
            let newLang = $(this).attr('data-lang').toLowerCase();
            if (location.pathname.includes('checkout') || location.pathname.includes('order')) {
                alert(newLang);
                $.ajax({
                    url: '/local/ajax/change_lang.php',
                    data: {
                        equal_url: 'Y',
                        new_lang: newLang
                    },
                    type: 'POST',
                    success: function (data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function () {
                        alert('CHANGING LANGUAGE NOT DONE');
                    }
                });
            }
        });

        $('.switch-currency').on('change', function () {
            let val = $(this).val();
            $.ajax({
                url: '/local/ajax/set_currency.php',
                data: {
                    action: 'SET_CURRENCY',
                    currency: val
                },
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function () {
                    alert('NOT DONE');
                }
            });
        });
    })
    ;
</script>