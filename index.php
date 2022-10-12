<!DOCTYPE html>
<html>

<head>
    <title>微信小程序扫码登录</title>
    <meta charset="utf-8">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.js"></script>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        #ewm {
            width: 200px;
            margin: 50px auto 10px;
        }

        #ewm img {
            width: 200px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        #shujuliu {
            width: 500px;
            height: 300px;
            background: #000;
            margin: 50px auto;
            padding: 20px 20px;
            border-top: 20px solid #ccc;
        }
    </style>
</head>

<body>

</body>

</html>
<?php

$appid = ''; //小程序ID
$appsecret =  ""; //小程序Secret


//获取access_token
function GetToken($appid, $appsecret)
{
    $file = file_get_contents("access_token.json", true); //读取access_token.json里面的数据
    $result = json_decode($file, true);
    //判断access_token是否在有效期内，如果在有效期则获取缓存的access_token
    //如果过期了则请求接口生成新的access_token并且缓存access_token.json
    if (time() > $result['expires']) {
        $data = array();
        $data['access_token'] = GetNweToken($appid, $appsecret);
        $data['expires'] = time() + 7000;
        $jsonStr =  json_encode($data);
        $fp = fopen("access_token.json", "w");
        fwrite($fp, $jsonStr);
        fclose($fp);
        return $data['access_token'];
    } else {
        return $result['access_token'];
    }
}

//获取新的access_token
function GetNweToken($appid, $appsecret)
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
    $access_token_Arr =  file_get_contents($url);
    $token_jsonarr = json_decode($access_token_Arr, true);
    return $token_jsonarr["access_token"];
}

$access_token = GetToken($appid, $appsecret);
//生成scene
$scene = rand(1111111111, 9999999999);
function GetQrCode($access_token, $scene)
{
    $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
    
    $data = [
        'page' => 'pages/index/index', //小程序页面路径
        'scene' => $scene
        // 'scene' => 'name=dzm#age=18'
    ];
    $jsondata = json_encode($data, true);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    //关闭请求
    curl_close($ch);
    file_put_contents("qrcode.png", $result);
    $base64_image = "data:image/jpeg;base64," . base64_encode($result);
    return $base64_image;
}
echo "<div id='ewm'>";
echo "<img src='" . GetQrCode($access_token, $scene) . "'/>";
echo "</div>";
echo "<h2 id=\"username\">请使用微信扫码授权登录</h2>";
echo "<h1 id=\"success\" style='color:#07c160;text-align:center;'></h1>";
echo "<input type='hidden' value=' " . $scene . "' id='scene'/>";
//实时数据流
echo "<div id='shujuliu'>";
echo "<div class='title' style='color:#07c160;font-size:14px;line-height:30px;'>实时数据流</div>";
echo "<div class='creatcode'></div>";
echo "<div class='loadingscan'></div>";
echo "<div class='sta'></div>";
echo "<div class='nickname'></div>";
echo "<div class='headimg'></div>";
echo "<div class='scansuccess'></div>";
echo "</div>";

?>
<script>
    var lunxun = setInterval("test()", 1000);
    

    function test() {
        var scene = $("#scene").val();
        $.ajax({
            type: "GET",
            url: "lunxun.php?scene=" + scene,
            dataType: "json",
            processData: false,
            success: function(data, textStatus) {
                if (data.result == "success") {
                    console.log("扫码完成")
                    clearInterval(lunxun);
                    $("#shujuliu .nickname").html("<p style='color:#07c160;font-size:14px;line-height:30px;'>微信昵称：" + data.nickName + "</p>");
                    $("#shujuliu .scansuccess").html("<p style='color:#07c160;font-size:14px;line-height:30px;'>扫码完成，登录成功</p>");
                    //获取头像
                    $("#ewm").html("<img src='" + data.avatarUrl + "' style='width:200px;border-radius:100px;'/>");
                    //获取昵称
                    $("#username").html(data.nickName);
                    //修改登录结果
                    $("#success").text("登录成功");
                } else if (data.result == "loading") {
                    console.log("正在监听扫码状态")
                    $("#shujuliu .creatcode").html("<p style='color:#07c160;font-size:14px;line-height:30px;'>创建小程序码</p><p style='color:#07c160;font-size:14px;line-height:30px;'>携带参数scene：" + scene + "</p>");
                    $("#shujuliu .loadingscan").html("<p style='color:#07c160;font-size:14px;line-height:30px;'>等待扫码...</p>");
                    $("#shujuliu .sta").html("<p style='color:#07c160;font-size:14px;line-height:30px;'>正在监听扫码状态...</p>");
                } else {
                    console.log("lunxun.php出现错误")
                }
            },
            error: function() {
                console.log("执行错误")
            }
        });
    }
</script>
