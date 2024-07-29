<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParam
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);


if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}
//PR($arResult);
?>
<div class="pagination-area"><?
    $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
    $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");

    if ($arResult["bDescPageNumbering"] === true):
        ?>
        <ul class="pagination">
            <?
            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                    if ($arResult["bSavePage"]):
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">1</a>
                        </li><?
                    else:
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">1</a>
                        </li><?
                    endif;

                    if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                        ?><a
                        href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= intval($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2) ?>">
                            ...</a><?
                    endif;
                endif;
            endif;

            do {
                $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;

                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    ?>
                    <li><a href="##" class="active"><?= $NavRecordGroupPrint ?></a></li><?
                elseif ($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $NavRecordGroupPrint ?></a>
                    </li><?
                else:
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $NavRecordGroupPrint ?></a>
                    </li><?
                endif;

                $arResult["nStartPage"]--;
            } while ($arResult["nStartPage"] >= $arResult["nEndPage"]);

            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["nEndPage"] > 1):
                    if ($arResult["nEndPage"] > 2):
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"] / 2) ?>">...</a>
                        </li><?
                    endif;
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"><?= $arResult["NavPageCount"] ?></a>
                    </li><?
                endif;
            endif;
            ?>
            <li>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"><i
                            class="fa fa-angle-double-right"></i></a></li>
        </ul>
    <?
    else:
        ?>
        <ul class="pagination">
            <?
            if ($arResult["NavPageNomer"] > 1):
                if ($arResult["nStartPage"] > 1):
                    if ($arResult["bSavePage"]):
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1">1</a>
                        </li><?
                    else:
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">1</a>
                        </li><?
                    endif;

                    if ($arResult["nStartPage"] > 2):
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nStartPage"] / 2) ?>">...</a>
                        </li><?
                    endif;
                endif;
            endif;

            do {
                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    ?>
                    <li><a href="##" class="active"><?= $arResult["nStartPage"] ?></a></li><?
                elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $arResult["nStartPage"] ?></a>
                    </li><?
                else:
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $arResult["nStartPage"] ?></a>
                    </li><?
                endif;
                $arResult["nStartPage"]++;
            } while ($arResult["nStartPage"] <= $arResult["nEndPage"]);

            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                        ?>
                        <li><a
                                href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2) ?>">...</a>
                        </li><?
                    endif;
                    ?>
                    <li><a
                            href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"><?= $arResult["NavPageCount"] ?></a>
                    </li><?
                endif;
            endif;

            ?>
            <li>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"><i
                            class="fa fa-angle-double-right"></i></a></li>
        </ul>
    <?
    endif;
    ?>
</div>