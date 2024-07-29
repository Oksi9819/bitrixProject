<?php

namespace General\System\Data;

use General\System\Config;

class Base
{
    public function getOptionsModule (): array
    {
        $MODULE_ID = Config::moduleId;
        return \Bitrix\Main\Config\Option::getForModule(
            $MODULE_ID
        );
    }


}