<?php
  $AppKey = '2aafdefcd3a10559be9364619cb6e286';
  $api = 'http://op.juhe.cn/robot/index?key=' . $AppKey . '&info=' .rawurldecode($info);

  $c = curl_init($api);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $b = curl_exec($c);
  curl_close($c);

  $json = json_decode($b);

  if (isset($json->result)) {
    echo $json->result->text;
  }

?>
