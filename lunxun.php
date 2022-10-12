<?php

$hostname = ""; //数据库地址
$dbuser = ""; //数据库用户名
$dbpass = ""; //数据库密码
$dbname = ""; //数据库名


//get scene
$scene = $_GET["scene"];


// 创建连接
$conn = new mysqli($hostname, $dbuser, $dbpass, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

mysqli_query($conn , "set names utf8");

$sql = "SELECT * FROM users WHERE scene=".$scene;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nickname = $row["nickName"];
        $headimg = $row["avatarUrl"];
    }
    echo json_encode(array('result'=>'success','nickName'=>$nickname,'avatarUrl'=>$headimg),true);
}else{
    echo json_encode(array('result'=>'loading'),true);
}

$conn->close();

?>
