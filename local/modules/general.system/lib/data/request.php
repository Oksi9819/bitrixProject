<?php
namespace General\System\Data;


class Request extends Base
{

    private $TOKEN;
    private $LOGIN;
    private $PASSWORD;
    private $IS_AUTH = false;
    private $TYPE_HEADER = '';

    function __construct(string $login='', string $password='', string $token='')
    {
        $this->LOGIN = $login;
        $this->PASSWORD = $password;
        $this->TOKEN = $token;
    }

    public function SendRequest(string $host, string $method, $data = [], string $type = "POST", $ssl=false, $return_header=false){
        $URL = $host.$method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);


        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);


        if($ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 'true');
        }else{
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 'false');
        }

        if($type == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch,CURLOPT_HTTPHEADER, $this->GetHeader());

        if($return_header){
            curl_setopt($ch, CURLOPT_HEADER, 1);
            //curl_setopt($ch, CURLOPT_NOBODY, 1);
        }

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($result, $header_size);
        curl_close($ch);

//        pr($data);
//        pr($URL);
//        pr($info);
//        pr($result);

        if ($return_header) {
            $cHeaders = [];
            $cData = explode("\n", $result);
            $cHeaders['status'] = $cData[0];
            array_shift($cData);

            foreach ($cData as $part) {
                $middle = explode(":", $part);
                if (!empty(trim($middle[0]))) {
                    $cHeaders[trim($middle[0])] = trim($middle[1]);
                }
            }
            //pr($cHeaders);
            return $cHeaders;
        }
        return json_decode($result, true);
    }

    private function GetHeader (){


        switch ($this->TYPE_HEADER){
            case 'auth':
                return [
                    "Accept: application/json",
                    'Content-Type: application/json',
                    'Authorization: Basic '.base64_encode($this->LOGIN.":".$this->PASSWORD)
                ];
            case 'bank_url':
                return [
                    "Accept: application/json",
                    'Content-Type: application/json',
                    'Charset: UTF-8',
                    'Authorization: Basic '.base64_encode($this->LOGIN.":".$this->PASSWORD)
                ];
            case 'order':
                return [
                    "Accept: application/json",
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->TOKEN,
                ];
            case 'ticket':
                return [
                    "Accept: application/json",
                    'Content-Type: application/json',
                    'X-Auth-Token: '.$this->TOKEN,
                ];
            default:
                return [
                    "Accept: application/json",
                    'Content-Type: application/json',
                    'Charset: UTF-8',
                ];
        }
    }

    public function SetTypeHeader(string $type){
        $this->TYPE_HEADER = $type;
    }

}
