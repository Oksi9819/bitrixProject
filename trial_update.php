<?

function BitrixExpireDate($date, $key){
    $outCode = '';
    $x = 0;
    for ($i = 0; $i < strlen($date); $i++) {
        $outCode .= chr(ord($date[$i]) ^ ord($key[$x]));
        if ($x == strlen($key) - 1)
            $x = 0;
        else
            $x = $x + 1;
    }
    return $outCode;
}

$key1 = 'DO_NOT_STEAL_OUR_BUS'; // OLDSITEEXPIREDATE
$key2 = 'thRH4u67fhw87V7Hyr12Hwy0rFr'; // SITEEXPIREDATE

$nowDate = date('mdY', time() + 60*60*24*30); // сегодня 07242015

$codeDate1 = 'XX'.$nowDate[3].$nowDate[7].'XX'.$nowDate[0].$nowDate[5].'X'.$nowDate[2].'XX'.$nowDate[4].'X'.$nowDate[6].'X'.$nowDate[1].'X'; // OLDSITEEXPIREDATE
$codeDate2 = 'X'.$nowDate[2].'X'.$nowDate[1].'XX'.$nowDate[0].$nowDate[6].'XX'.$nowDate[4].'X'.$nowDate[7].'X'.$nowDate[3].'XXX'.$nowDate[5]; // SITEEXPIREDATE

echo $outCode1 = base64_encode(BitrixExpireDate($codeDate1, $key1)); // OLDSITEEXPIREDATE
echo '<br>';
echo $outCode2 = base64_encode(BitrixExpireDate($codeDate2, $key2)); // SITEEXPIREDATE


/*
то, что выведет этот код - 
1) первой строкой заменить значение в /bitrix/modules/main/admin/define.php
2) в БД в таблице  b_option в поле где NAME=admin_passwordh заменить VALUE на то, что во второй строкой заменить
3) очистить папку /bitrix/managed_cache

*/