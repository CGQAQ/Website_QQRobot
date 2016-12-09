<?php
if ($_GET["token"] === "baidubaike") {//获取二维码
    $key = $_POST['key'];
    require 'baidubaike.php';
}
if ($_GET['token'] === 'weather') {
  $key = $_POST['key'];
  require 'weather.php';
}
if ($_GET['token'] === 'joke'){
  require 'joke.php';
}
if ($_GET['token'] === 'tuling'){
  $info = $_POST['info'];
  require 'tuling.php';
}
?>
