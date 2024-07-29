<?

namespace General\System;

use Bitrix\Main\Diag\Debug,
    Bitrix\Main\Application;


class Log
{
    public static function add($array, $subDir)
    {

        $MODULE_ID = Config::moduleId;

        $options =  \Bitrix\Main\Config\Option::getForModule(
            $MODULE_ID
        );
        if($options['log_active'] == 'Y'){

            $context = Application::getInstance()->getContext();
            $server = $context->getServer();

            $dirPath = $options['log_url'] . "{$subDir}";
            $fullDirPath = $server->getDocumentRoot() . $options['log_url'] . "{$subDir}";
            $filePath = $dirPath . '/' .date('Y-m-d:H') . '.log';

            if (!file_exists($fullDirPath))
                mkdir($fullDirPath, 0755, true);

            Debug::writeToFile(print_r($array, true), 'log on ' . date('d.m.Y H:i:s'), $filePath);
        }
    }
}