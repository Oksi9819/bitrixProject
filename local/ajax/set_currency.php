<?php
session_start();

if ($_POST['action'] == 'SET_CURRENCY') {
    $chosenCurrency = (string)trim($_POST['currency']);
    $_SESSION['CURRENCY'] = $chosenCurrency;
    if ($_SESSION['CURRENCY'] == $chosenCurrency) {
        echo json_encode(array(
            'result' => 'Success',
            'msg' => 'Currency was changed'
        ));
        return;
    } else {
        echo json_encode(array(
            'result' => 'Error',
            'msg' => 'smth got wrong'
        ));
        return;
    }
}
