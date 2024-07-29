<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CJSCore::Init(array("jquery"));
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="blog-post-area">
                    <h2 class="title text-center">Latest From our Blog</h2>
                    <? foreach ($arResult["ITEMS"] as $arItem): ?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <div class="single-blog-post" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <h3><?= $arItem['NAME'] ?></h3>
                            <div class="post-meta">
                            <span class="stars-block" id="stars_block<?= $arItem['ID'] ?>"
                                  data-vote="<?= $arItem['PROPERTIES']['VOTES']['VALUE'] ?>"
                                  data-value="<?= $arItem['PROPERTIES']['ARR_VOTE_VALUE']['VALUE'] ?>">
                            </span>
                            </div>
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <img src="<?= $arItem['PREVIEW_PICTURE']['SAFE_SRC'] ?>"
                                     alt="<?= $arItem['PREVIEW_PICTURE']['ALT'] ?>">
                            </a>
                            <p><?= $arItem['PREVIEW_TEXT'] ?></p>
                            <a class="btn btn-primary" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">Read More</a>
                        </div>
                    <? endforeach; ?>
                    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
                        <br/><?= $arResult["NAV_STRING"] ?>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function () {
        let starsBlocks = $('.stars-block');
        let starsBlockLength = starsBlocks.length;
        let i = 0;
        for (i; i < starsBlockLength; i++) {
            let starBlock = starsBlocks[i];
            //let idSB = starBlock.getAttribute('id');
            //console.log(idSB);
            let voteValue = starBlock.getAttribute('data-value');
            //console.log(voteValue);
            let mVV = Math.floor(voteValue);
            //console.log(mVV);
            let j = 0;
            let txt = '';
            for (j; j < mVV; j++) {
                if ($(starBlock).is(':empty')) {
                    txt = '<i class="fa fa-star"></i>';
                } else {
                    txt += '<i class="fa fa-star"></i>';
                }
            }
            if (voteValue > mVV) {
                txt += '<i class="fa fa-star-half-o"></i>';
            }
            $(starBlock).html(txt);
        }
    });
</script>
