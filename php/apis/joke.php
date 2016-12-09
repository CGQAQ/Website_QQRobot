<?php
  $AppKey = 'ef1a4183806ef98c643d6112097a2c43';
  $arr = range(1,20000);
  shuffle($arr);
  $page = $arr[0];
  $arr = range(1,20);
  shuffle($arr);
  $pagesize = $arr[0];
  $arr = range(0,$pagesize-1);
  shuffle($arr);
  $index = $arr[0];

  $api = 'http://japi.juhe.cn/joke/content/list.from?' . 'key=' . $AppKey . '&sort=desc&time=' . time() . '&page=' . $page . '&pagesize=' . $pagesize;//-$ms;
  $c = curl_init($api);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $b = curl_exec($c);
  curl_close($c);

  $json = json_decode($b);
  if (isset($json->result)) {
    echo $json->result->data[$index]->content;
  }
  else {
    echo "joke gets something wrong!";
  }


?>
