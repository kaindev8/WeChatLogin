<?php

//*请求参数
$parame = [
    'appid' => '', //小程序ID
    'secret' => '', //小程序secret
    'code' => $_GET['code'],
    'type' => 'authorization_code'
];
$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $parame['appid'] . "&secret=" . $parame['secret'] . "&js_code=" . $parame['code'] . "&grant_type=" . $parame['type'];

function GetOpenId($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result, true);
    return $result['openid'];
}
echo json_encode(['code' => 200, 'openid' => GetOpenId($url)]);
