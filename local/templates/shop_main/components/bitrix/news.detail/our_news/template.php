<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>

                <div class="blog-post-area">
                    <h2 class="title text-center">Latest From our Blog</h2>
                    <div class="single-blog-post">
                        <h3><?=$arResult['NAME']?></h3>
                        <div class="post-meta">
                            <span class="stars-block" id = "stars_block<?=$arResult['ID']?>" data-vote = "<?=$arResult['PROPERTIES']['VOTES']['VALUE']?>"  data-value = "<?=$arResult['PROPERTIES']['ARR_VOTE_VALUE']['VALUE']?>">
                            </span>
                        </div>
                        <a><? if(isset($arResult["DETAIL_PICTURE"]["SRC"])) {
                                ?><img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"><?
                            } else {
                                ?><img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"><?
                            }?>
                        </a>
                        <p><?=$arResult['DETAIL_TEXT']?></p>
                        <div class="pager-area">
                            <ul class="pager pull-right">
                                <?if (isset($arResult['PREV'])) {
                                    ?><li><a href="<?=$arResult['PREV']['URL']?>">Pre</a></li><?
                                }?>
                                <?if (isset($arResult['NEXT'])) {
                                    ?><li><a href="<?=$arResult['NEXT']['URL']?>">Next</a></li><?
                                }?>
                            </ul>
                        </div>
                    </div>
                </div><!--/blog-post-area-->
<script>
    $(document).ready(function() {
        let starBlock = ($('.stars-block'))[0];
        //console.log(starBlock);
        //let idSB = starBlock.getAttribute('id');
        //console.log(idSB);
        let voteValue = starBlock.getAttribute('data-value');
        //console.log(voteValue);
        let mVV = Math.floor(voteValue);
        //console.log(mVV);
        let j = 0;
        let txt = '';
        for(j; j<mVV; j++) {
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

        let resultVote = $('.resultvote');
        let txtRes = '';
        let voteAmount = starBlock.getAttribute('data-vote');
        for(let i=0; i<mVV; i++) {
            if ($(resultVote).is(':empty')) {
                txtRes = '<i class="votestar fa fa-star color"></i>';
            } else {
                txtRes += '<i class="votestar fa fa-star color"></i>';
            }
        }
        if (voteValue < 5) {
            for(let s = 0; s < (5 - voteValue); s++) {
                txtRes += '<i class="votestar fa fa-star"></i>';
            }
        }
        $(resultVote).html(txtRes);
        $('.votes-amount').text('(' + voteAmount + ' votes)');
    });
</script>