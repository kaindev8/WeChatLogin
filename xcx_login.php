<?php


$hostname = ""; //数据库地址
$dbuser = ""; //数据库用户名
$dbpass = ""; //数据库密码
$dbname = ""; //数据库名


$nickName = $_GET["nickName"];
$avatarUrl = $_GET["avatarUrl"];
$scene = $_GET["scene"];
$time = date("Y-m-d H:i:s");
$openid = $_GET["openid"];

if (!empty($nickName) && !empty($avatarUrl) && !empty($scene) && !empty($openid)) {
    // 创建连接
    $conn = mysqli_connect($hostname, $dbuser, $dbpass, $dbname);
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    function Query($conn, $dbname, $nickName, $avatarUrl, $time, $openid, $scene)
    {
        mysqli_query($conn, "set names utf8");
        $sql = 'SELECT * FROM users';
        mysqli_select_db($conn, $dbname);
        $retval = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)) {
            if($row['openid'] == $openid){
                $res = 200;
            }
        }
        if ($res == 200) {
            $sql2 = 'UPDATE users SET scene="'.$scene.'",nickName="' .$nickName . '",avatarUrl="' . $avatarUrl .'"WHERE openid="' . $openid . '"';
            mysqli_select_db($conn, $dbname);
            mysqli_query($conn, $sql2);
        } else {
            $sql3 = "INSERT INTO users (nickName, avatarUrl, scene, create_time, openid) VALUES ('$nickName', '$avatarUrl', '$scene', '$time', '$openid')";
            mysqli_select_db($conn, $dbname);
            mysqli_query($conn, $sql3);
        }
    }
    Query($conn, $dbname, $nickName, $avatarUrl, $time, $openid, $scene);
    mysqli_close($conn);
} else {
    echo '缺少参数';
}
