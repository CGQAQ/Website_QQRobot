<?php
  $api = "http://baike.baidu.com/search/word?word=" . rawurlencode($key);
  file_get_contents($api);

  $url = '';
  foreach ($http_response_header as $item) {
    if (strpos($item, "Location") !== false) {
      # code...
      $url .=$item.'<br/>';
    }
  }
  $arr = explode('<br/>', $url);
  while (true) {
    # code...
    if (($s = array_pop($arr)) !== '') {
      # code...
      $url = explode(': ', $s)[1];
      break;
    }
  }
  $html = file_get_contents($url);
  $pattern = '/(?<=<meta name="description" content=").*?(?=">)/';
  $arr = array();
  preg_match($pattern, $html, $arr);
  if (strpos($url, "/search/none")){
    echo '百度百科没有收录词条: “' . $key .'”!';
    exit();
  }
  echo ($key. '\n' . $arr[0] . '\n详情请访问： ' . $url . '查看');
?>
