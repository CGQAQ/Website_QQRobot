<?php
  $AppKey = '36bf4dbc312d31ff4fed9950b1d748ee';
  $api = 'http://op.juhe.cn/onebox/weather/query?' . 'key=' . $AppKey . '&cityname=' . rawurldecode($key);

  $c = curl_init($api);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $b = curl_exec($c);
  curl_close($c);
  echo $b;
?>
