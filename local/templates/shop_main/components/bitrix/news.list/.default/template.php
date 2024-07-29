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

?>

<? foreach ($GLOBALS['SELECTED_COMMENTS'] as $arComment): ?>
    <ul>
        <li><a href="##"><i class="fa fa-user"></i><?= $arComment['PROPERTY']['VALUE']['USER_NAME'] ?></a></li>
        <li><a href="##"><i class="fa fa-calendar-o"></i><?= $arComment['DATE_ACTIVE_FROM'] ?></a></li>
        <li class="rating-area comment-rating"
            data-value="<?= $arComment['PROPERTY']['VALUE']['RATING'] ?>">
        </li>
    </ul>
    <p><?= $arComment['PROPERTY']['VALUE']['COMMENT'] ?></p>
<? endforeach; ?>

<p><b><?= GetMessage('ADD_REVIEW_HEADER') ?></b></p>

<form action="#">
    <span>
        <input id="review_user_name" type="text" placeholder="Your Name"
               value="<?= $arResult['USER'] ? $arResult['USER']['NAME'] : ''; ?>"
               data-user-id="<?= $arResult['USER'] ? $arResult['USER']['ID'] : ''; ?>"
               required/>
        <input id="review_user_email" type="email" placeholder="Email Address"
               value="<?= $arResult['USER'] ? $arResult['USER']['EMAIL'] : ''; ?>"
               required/>
    </span>
    <textarea id="review_user_text" name="" class="comment-text" placeholder="<?= GetMessage('ADD_REVIEW_EXAMPLE') ?>"
              required></textarea>
    <div class="rating-area">
        <b><?= GetMessage('ADD_REVIEW_RATING') ?>: </b>
        <ul class="ratings">
            <li class="rate-this"><?= GetMessage('ADD_REVIEW_RATING_HEADER') ?>:</li>
            <li class="stars-block-form" data-id="<?= $arResult['ID'] ?>"
                data-vote="<?= $GLOBALS['VOTES'] ?>"
                data-value="<?= $GLOBALS['VOTES_VALUE'] ?>">
            </li>
            <li class="color">
                <? if (!empty($GLOBALS['VOTES'])) {
                    if ($GLOBALS['VOTES'] == 1) {
                        echo '(' . $GLOBALS['VOTES'] . ' ' . GetMessage('ADD_REVIEW_RATING_VOTE') . ')';
                    } elseif ($GLOBALS['VOTES'] == 2 or $GLOBALS['VOTES'] == 3 or $GLOBALS['VOTES'] == 4) {
                        echo '(' . $GLOBALS['VOTES'] . ' ' . GetMessage('ADD_REVIEW_RATING_VOTES_2') . ')';
                    } else {
                        echo '(0 ' . GetMessage('ADD_REVIEW_RATING_VOTES_3') . ')';
                    }
                } else {
                    echo '(0 ' . GetMessage('ADD_REVIEW_RATING_VOTES_3') . ')';
                } ?>
            </li>
        </ul>
    </div>
    <button type="button"
            class="btn btn-default pull-right submit-send-review"><?= GetMessage('ADD_REVIEW_SUBMIT') ?></button>
</form>
