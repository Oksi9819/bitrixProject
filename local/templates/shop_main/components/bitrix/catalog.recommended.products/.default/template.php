<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

if (!empty($arResult['RECOMMENDED_IDS'])) {
    ?>
    <div class="recommended_items"><!--recommended_items-->
        <h2 class="title text-center"><?= GetMessage('CATALOG_RECOMMENDED_PRODUCTS_HREF_TITLE') ?></h2>
        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <?
                    for ($i = 0; $i < count($arResult['RECOMMENDED_IDS']); $i++) {
                        if ($i < 3) {
                            ?>
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PICTURE']['SRC'] ?>"
                                                 alt="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PICTURE']['ALT'] ?>"/>
                                            <h2>
                                                <?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['PRICES']['BASE']['CURRENCY'] ?> <?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['PRICES']['BASE']['VALUE'] ?></h2>
                                            <p class="detail-ref"
                                               href="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PAGE_URL'] ?>"><?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['NAME'] ?></p>
                                            <?
                                            //PR($arResult['RECOMMENDED_IDS'][$i]);
                                            //PR($arResult['IN_BASKET']);
                                            if (in_array($arResult['RECOMMENDED_IDS'][$i], $arResult['IN_BASKET'])) {
                                                ?>
                                                <button type="button" class="btn btn-default add-to-cart"
                                                        id="recItem<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ID'] ?>"
                                                        data-cart-status="Y"><?= GetMessage('CATALOG_ITEM_IN_CART') ?>
                                                </button>
                                                <?
                                            } else {
                                                ?>
                                                <button type="button" class="btn btn-default add-to-cart"
                                                        id="recItem<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ID'] ?>"
                                                        data-cart-status="N"
                                                        data-href="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ADD_URL'] ?>"><?= GetMessage('CATALOG_ITEM_ADD_TO_CART') ?>
                                                </button>
                                                <?
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                    }
                    ?>
                </div>
                <div class="item">
                    <?
                    for ($i = 0; $i < count($arResult['RECOMMENDED_IDS']); $i++) {
                        if ($i > 2) {
                            ?>
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PICTURE']['SRC'] ?>"
                                                 alt="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PICTURE']['ALT'] ?>"/>
                                            <h2>
                                                <?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['PRICES']['BASE']['CURRENCY'] ?> <?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['PRICES']['BASE']['VALUE'] ?></h2>
                                            <p class="detail-ref"
                                               href="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['DETAIL_PAGE_URL'] ?>"><?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['NAME'] ?></p>
                                            <?
                                            if (in_array($arResult['RECOMMENDED_IDS'][$i], $arResult['IN_BASKET'])) {
                                                ?>
                                                <button type="button" class="btn btn-default add-to-cart"
                                                        id="recItem<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ID'] ?>"
                                                        data-cart-status="Y"><?= GetMessage('CATALOG_ITEM_IN_CART') ?>
                                                </button>
                                                <?
                                            } else {
                                                ?>
                                                <button type="button" class="btn btn-default add-to-cart"
                                                        id="recItem<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ID'] ?>"
                                                        data-cart-status="N"
                                                        data-href="<?= $arResult['ITEMS'][$arResult['RECOMMENDED_IDS'][$i]]['ADD_URL'] ?>"><?= GetMessage('CATALOG_ITEM_ADD_TO_CART') ?>
                                                </button>
                                                <?
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                    }
                    ?>
                </div>
            </div>
            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div><!--/recommended_items-->
    <script>
        $(document).ready(function () {
            $('.add-to-cart[data-cart-status = N]').on('click', function () {
                let id = $(this).attr('id');
                let href = $(this).attr('data-href');
                $.ajax({
                    url: href,
                    method: 'get',
                    success: function (data) {
                        alert('Товар добавлен в корзину');
                        location.reload();
                        location.href = ' #' + id;
                    },
                    error: function () {
                        alert('Товар добавить в корзину не удалось');
                    }
                });
            });
        });
    </script>
    <?php
}