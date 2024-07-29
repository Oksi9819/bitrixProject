<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
define('MODULE_ID', 'general.system');

use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Crm\Category\Entity\DealCategoryTable;
use Bitrix\Crm\Service\Container;

/**
 * @var $APPLICATION \CMain
 * @var $USER \CUser
 */

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm('');
}

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

try {
    if (!Loader::includeModule(MODULE_ID)) {
        throw new SystemException('Ошибка при подключении модуля');
    }

    $c = 1;
    $tabs = [
        [
            'DIV' => 'edit' . $c++,
            'TAB' => 'Общие настройки',
            'ICON' => 'main_settings',
            'TITLE' => 'Общие настройки',
            'OPTIONS' => [
                'Логи',
                [
                    'log_url',
                    'Путь логов',
                    '/log/general.system/',
                    [
                        'text',
                        20
                    ]
                ],
                [
                    'log_active',
                    'Включить логи',
                    'N',
                    [
                        "checkbox",
                        0
                    ],
                    'N',
                ],
            ],
        ],

    ];


    if ($request->isPost() && check_bitrix_sessid()) {
        foreach ($tabs as $tab) {
            foreach ($tab['OPTIONS'] as $option) {
                if (!is_array($option)) {
                    continue;
                }
                if (!empty($option['note'])) {
                    continue;
                }
                if ($request['save']) {
                    $optionValue = $request->getPost($option[0]);
                    Option::set(
                        MODULE_ID,
                        $option[0],
                        is_array($optionValue) ? implode(',', $optionValue) : $optionValue
                    );
                } elseif ($request['default']) {
                    Option::set(MODULE_ID, $option[0], $option[2]);
                }
            }


        }
    }
} catch (\Bitrix\Main\SystemException $exception) {
    \CAdminMessage::ShowMessage([
        'TYPE' => 'ERROR',
        'MESSAGE' => $exception->getMessage(),
    ]);
}

$tabControl = new \CAdminTabControl('tabControl', $tabs);
$tabControl->begin();
?>

    <form method="post" action="<?= sprintf(
        '%s?mid=%s&lang=%s&mid_menu=1',
        $request->getRequestedPage(),
        urlencode(MODULE_ID),
        LANGUAGE_ID
    ) ?>">
        <?
        echo bitrix_sessid_post();
        foreach ($tabs as $tab) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList(MODULE_ID, $tab['OPTIONS']);

            foreach ($tab['OPTIONS_CUSTOM'] as $customOption) {
                if (!is_array($customOption)) {
                    ?>
                    <tr class="heading">
                        <td colspan="2"><?= $customOption ?></td>
                    </tr>
                    <?
                    continue;
                }

                $val = COption::GetOptionString(MODULE_ID, $customOption[0], $customOption[2]);
                $type = $customOption[3];

                if ($type[0] == "fieldMap") {
                    if (isJson($val)) {
                        $valArray = json_decode($val, true);
                        foreach ($valArray['from'] as $key => $value) { ?>
                            <tr>
                                <td>
                                    <input type="text" style="width:200px" name="<?= $customOption[0] ?>[from][]"
                                           value="<?= $value ?>"/>
                                </td>
                                <td>
                                    =>
                                    <input type="text" style="width:200px" name="<?= $customOption[0] ?>[to][]"
                                           value="<?= $valArray['to'][$key] ?>"/>
                                </td>
                            </tr>

                            <?
                        }
                    }
                    ?>
                    <tr>
                        <td>
                            <input type="text" style="width:200px" name="<?= $customOption[0] ?>[from][]" value=""/>
                        </td>
                        <td>
                            =>
                            <input type="text" style="width:200px" name="<?= $customOption[0] ?>[to][]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <button type="button" onclick="copyRow(this)">Добавить</button>
                        </td>
                    </tr>
                    <?
                } else {
                    __AdmSettingsDrawRow(MODULE_ID, $customOption);
                }
                ?>
                <?
            }

            $tabControl->EndTab();
        }
        ?>

        <?
        $tabControl->Buttons(); ?>
        <input type="submit" name="save"
               value="<?= Loc::getMessage('MAIN_SAVE') ?>"
               title="<?= Loc::getMessage('MAIN_OPT_SAVE_TITLE') ?>"
               class="adm-btn-save"/>
        <?
        $tabControl->End(); ?>
    </form>

    <script>
        function copyRow(btn) {

            let btnRow = btn.closest('tr');
            let siblingRow = btnRow.previousElementSibling;
            let newRow = siblingRow.cloneNode(true);

            // Очистка значений колонок
            let inputs = newRow.getElementsByTagName("input");
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].value = "";
            }

            let selects = newRow.getElementsByTagName("select");
            for (let i = 0; i < selects.length; i++) {
                selects[i].selectedIndex = 0;
            }
            let parentElement = siblingRow.closest('tbody');
            parentElement.insertBefore(newRow, btnRow);
        }

        function removeFile(path) {
            BX.ajax.runAction(
                'general:Migration.controllers.main.removeFile',
                {
                    method: 'POST',
                    data: {
                        path: path
                    }
                })
                .then(function (response) {
                    }
                ).catch(
                function (response) {
                    console.error('response', response);
                });
        }

        function downloadFile(element) {
            if (element.dataset.path) {
                let path = element.dataset.path;

                BX.ajax.runAction(
                    'general:Migration.controllers.main.moveFile',
                    {
                        method: 'POST',
                        data: {
                            path: path
                        }
                    })
                    .then(function (response) {
                            let fileUrl = response.data.path;

                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = response.data.name;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            removeFile(fileUrl);
                        }
                    ).catch(
                    function (response) {
                        console.error('response', response);
                    });
            }


        }
    </script>

<?


function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
