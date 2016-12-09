<?php

/**
 * 从响应头中获取 需要设置的cookie
 * @return cookie字典
 * @access true
 */
function getCookies($o)
{
    $key = array();
    $value = array();
    $data = array();
    $temp = array();
    foreach ($o as $item) {
        if (strpos($item, 'Cookie') !== false) {
            $pattern_key = "/(?<=Set-Cookie: ).*?(?==.*?;)/";
            $pattern_value = "/(?<==).*?(?=;)/";
            preg_match($pattern_key, $item, $temp);
            array_push($key, $temp[0]);
            preg_match($pattern_value, $item, $temp);
            array_push($value, $temp[0]);
        }
    }
    for ($i = 0; $i < count($key); $i++) {
        if ($value[$i] !== '') {
            $data[$key[$i]] = $value[$i];
        }
    }
    return $data;
}

/**
 * 设置cookie  v0.3  第二次修复bug
 * @access true
 */
function setCookies($a)
{
    foreach ($a as $item) {
        setcookie(array_keys($a, $item)[0], $item);
    }
    foreach (array_keys($a) as $item) {
        if ($item === 'uin') {
            setcookie($item, $a[$item]);
        }
        if ($item === 'p_uin') {
            setcookie($item, $a[$item]);
        }
    }
}

/**
 * 遍历_COOKIE数组
 * @return cookie字典
 * @access true
 */
function _cookies()
{
    $cookie = '';
    for ($i = 0; $i < count($_COOKIE); $i++) {
        $cookie .= array_keys($_COOKIE)[$i] . '=' . $_COOKIE[array_keys($_COOKIE)[$i]] . '; ';
    }
    return $cookie;
}

/**
 *发送http post请求
 * @param $url 请求地址
 * @param $data post数据
 * @param $referer referer
 * @return 请求结果
 */
function send_post($url, $data, $referer)
{
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_COOKIE, _cookies());
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Referer: ' . $referer));
    $b = curl_exec($c);
    curl_close($c);
    return $b;
}

/**
 *发送http get请求
 * @param $url 请求地址
 * @param $referer referer
 * @return 请求结果
 */
function send_get($url, $referer)
{
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_COOKIE, _cookies());
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Referer: ' . $referer));
    $b = curl_exec($c);
    curl_close($c);
    return $b;
}

/**
 * 获取二维码
 * @return 二维码图片
 * @access true
 */
function getQR()
{

    //header('Location:https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=0&l=M&s=5&d=72&v=4&t=0.9279511128552258');    header('Access-Control-Allow-Origin:*');
    header('Content-Type:image/png');


    $data = file_get_contents("https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=0&l=M&s=5&d=72&v=4&t=0.9279511128552258");

//    $qrsig = '';
//    foreach ($http_response_header as $item) {
//        if (strpos($item, 'Cookie') !== false) {
//            $pattern = "/(?<=Set-Cookie: qrsig=).*?(?=;)/";
//            $array = array();
//            preg_match($pattern, $item, $array);
//            $qrsig = $array[0];
//        }
//    }
    $a = getCookies($http_response_header);
    setcookie('qrsig', $a['qrsig']);
    echo $data;
}

/**
 *依然是获取cookie
 */
function cookie()
{

    file_get_contents("https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001");
    $a = getCookies($http_response_header);
    setCookies($a);
}

/**
 *获取二维码状态
 * @return 状态等
 */
function getQRState()
{
    //https://ssl.ptlogin2.qq.com/ptqrlogin?webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-2-90518&mibao_css=m_webqq&t=undefined&g=1&js_type=0&js_ver=10162&login_sig=&pt_randsalt=0
    //eas_sid=W1t4H6E5H685l6m0m460I6s0O3; pgv_pvi=4909930496; pgv_info=ssid=s5946320445; pgv_pvid=2872218776; qrsig=bLbZJVCGkLVj44ZdKA-Ckdu-wCNHqzcwR8SzJWsEQfL-*NW8kTQmZ37Hqa-LZmPM; pt_login_sig=8gUyvvy-Lv0yt0K8TF*OnloRNw*RMdmGdSLnitisyEV7T6MF9-E5fqW4ph00rdRi; pt_clientip=794b0a3171e8153e; pt_serverip=39bb0a8f8261e5c2

    $qrsig = $_COOKIE['qrsig'];
    $opts = array('http' => array('method' => 'GET',
        'header' => "Cookie:eas_sid=W1t4H6E5H685l6m0m460I6s0O3; pgv_pvi=4909930496; pgv_info=ssid=s5946320445; pgv_pvid=2872218776; pt_login_sig=Hgd8wMogY8*ST6daG*Noj9DdJ4AAqjHRisMfkddaAkPmt0Gly-ReKktDyjC4G-sg; pt_clientip=f2d60a317210b364; pt_serverip=b54a0aa693da5839; qrsig=" . $qrsig . "\r\n"
            . 'Host:ssl.ptlogin2.qq.com'));

    $url = "https://ssl.ptlogin2.qq.com/ptqrlogin?webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-2-90518&mibao_css=m_webqq&t=undefined&g=1&js_type=0&js_ver=10162&login_sig=&pt_randsalt=0";
    $qrState = file_get_contents($url, false, stream_context_create($opts));
    $a = getCookies($http_response_header);
    setCookies($a);
    echo $qrState;
}

/**
 * 获取cookie
 */
function openUrl($o)
{
    file_get_contents($o);
    $a = getCookies($http_response_header);
    setCookies($a);
    echo 'bingo';
}

/**
 * 获取vfwebqq  并存到cookie
 * @access true
 * @return vfwebqq
 */
function getvfwebqq()
{
    $api = 'http://s.web2.qq.com/api/getvfwebqq?ptwebqq=' . $_COOKIE['ptwebqq'] . '&clientid=53999199&psessionid=&t=1470810410380';
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    $b = send_get($api, $referer);
    $vfwebqq = json_decode($b)->result->vfwebqq;
    setcookie('vfwebqq', $vfwebqq);
    echo 'bingo';
}

/**
 * 登录
 * @access true
 */
function doLogin()
{
    $api = "http://d1.web2.qq.com/channel/login2";
    $postData = array('r' => '{"ptwebqq":"' . $_COOKIE['ptwebqq'] . '","clientid":53999199,"psessionid":"","status":"online"}');
    $referer = 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2';

    $b = send_post($api, $postData, $referer);
    $psession = json_decode($b)->result->psessionid;
    setcookie('psessionid', $psession);
    echo $b;
}

function getUinAndPtwebqq()
{
    echo '{"uin":"' . $_COOKIE['uin'] . '", "ptwebqq":"' . $_COOKIE['ptwebqq'] . '"}';
}

/**
 * 通过uin获取自身信息
 * @access true
 * @param:uin 串码
 */
function getSelfInfo()
{
    $api = 'http://s.web2.qq.com/api/get_self_info2?t=1470937331556';//GET
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    echo send_get($api, $referer);
}

/**
 * 获取全部好友
 * @access true
 * @return json字符串
 */
function get_user_friends2($hash)
{
    $api = 'http://s.web2.qq.com/api/get_user_friends2';//post
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
    $postData = array('r' => '{"vfwebqq":"' . $_COOKIE["vfwebqq"] . '","hash":"' . $hash . '"}');

    echo send_post($api, $postData, $referer);
}

/**
 * 获取全部群
 * @access true
 * @return json字符串
 */
function get_group_name_list_mask2($hash)
{
    $api = 'http://s.web2.qq.com/api/get_group_name_list_mask2';
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';
    $postData = array('r' => '{"vfwebqq":"' . $_COOKIE["vfwebqq"] . '","hash":"' . $hash . '"}');

    echo send_post($api, $postData, $referer);
}

/**
 * 获取全部讨论组
 * @access true
 * @return json字符串
 */
function get_discus_list()
{
    $api = 'http://s.web2.qq.com/api/get_discus_list?clientid=53999199&psessionid=' . $_COOKIE['psessionid'] . '&vfwebqq=' . $_COOKIE['vfwebqq'] . '&t=1470994286593';
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    echo send_get($api, $referer);
}

/**
 * 通过uin获取用户真实qq账号
 * @access true
 * @param $uin 串码
 */
function get_friend_uin2($uin)
{
    $api = 'http://s.web2.qq.com/api/get_friend_uin2?tuin=' . $uin . '&type=1&vfwebqq=' . $_COOKIE['vfwebqq'] . '&t=1470980078889';//GET
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    echo send_get($api, $referer);
}

/**
 * 通过uin获取用户账号信息
 * @access true
 * @param $uin 串码
 */
function get_friend_info2($uin)
{
    $api = 'http://s.web2.qq.com/api/get_friend_info2?tuin=' . $uin . '&vfwebqq=' . $_COOKIE['vfwebqq'] . '&clientid=53999199&psessionid=' . $_COOKIE['psessionid'] . '&t=1470980078889';//GET
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    echo send_get($api, $referer);
}

/**
 * 发送群消息
 * @access true
 * @param $uin 串码
 * @return json字符串 包含个性签名
 */
function get_single_long_nick2($uin)
{
    $api = 'http://s.web2.qq.com/api/get_single_long_nick2?tuin=' . $uin . '&type=1&vfwebqq=' . $_COOKIE['vfwebqq'] . '&t=1470980078889';//GET
    $referer = 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1';

    echo send_get($api, $referer);
}

function get_online_buddies2(){
  $api = 'http://d1.web2.qq.com/channel/get_online_buddies2?vfwebqq='+ $_COOKIE['vfwebqq'] +'&clientid=53999199&psessionid=' + $_COOKIE['psessionid'] + '&t=1471542287051';//GET
  $referer = 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2';

  echo send_get($api, $referer);
}

/**
 * 发送群消息
 * @access true
 * @param $gid 群编码
 * @param $msg 要发送的消息
 * @return json字符串 状态码等
 */
function send_qun_msg2($gid, $msg)
{
    $api = 'http://d1.web2.qq.com/channel/send_qun_msg2';//POST
    $referer = 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2';
    $postData = array('r' => '{"group_uin":' . $gid . ',"content":"[\"' . $msg . '\",[\"font\",{\"name\":\"宋体\",\"size\":10,\"style\":[0,0,0],\"color\":\"000000\"}]]","face":0,"clientid":53999199,"msg_id":10000001,"psessionid":"' . $_COOKIE['psessionid'] . '"}');

    echo send_post($api, $postData, $referer);
}

/**
 * 发送群消息
 * @access true
 * @param $uin 用户编码
 * @param $msg 要发送的消息
 * @return json字符串 状态码等
 */
function send_buddy_msg2($uin, $msg)
{
    $api = 'http://d1.web2.qq.com/channel/send_buddy_msg2'; //POST
    $referer = 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2';
    $postData = array('r' => '{"to":' . $uin . ',"content":"[\"' . $msg . '\",[\"font\",{\"name\":\"宋体\",\"size\":10,\"style\":[0,0,0],\"color\":\"000000\"}]]","face":0,"clientid":53999199,"msg_id":10000001,"psessionid":"' . $_COOKIE['psessionid'] . '"}');

    echo send_post($api, $postData, $referer);
}

/**
 *拉取消息
 * @return json数据
 */
function pull_msg()
{
    $api = 'http://d1.web2.qq.com/channel/poll2';
    $referer = 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2';
    $postData = array('r' => '{"ptwebqq":"' . $_COOKIE['ptwebqq'] . '","clientid":53999199,"psessionid":"' . $_COOKIE['psessionid'] . '","key":""}');

    $c = curl_init($api);
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_COOKIE, _cookies());
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Referer: ' . $referer, 'Origin: http://d1.web2.qq.com', 'Content-Type:application/x-www-form-urlencoded', 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/51.0.2704.79 Chrome/51.0.2704.79 Safari/537.36'));
    //curl_setopt($c, CURLOPT_TIMEOUT, 240);
    $b = curl_exec($c);
    curl_close($c);

    echo $b;
}

if ($_GET["token"] === "getQR") {//获取二维码
    getQR();
} else if ($_GET["token"] === "getQRState") {//获取二维码状态
    getQRState();
} else if ($_GET["token"] === "doLogin") {//登录
    doLogin();
} else if ($_GET['token'] === "cookie") {//获取必需的cookie
    cookie();
} else if ($_GET["token"] === 'open') {//打开url  post
    openUrl($_POST['url']);
} else if ($_GET['token'] === 'getvfwebqq') {//获取vfwebqq
    getvfwebqq();
} else if ($_GET['token'] === 'getUinAndPtwebqq') {//获取uin和ptwebqq
    getUinAndPtwebqq();
} else if ($_GET['token'] === 'getSelfInfo') {//获取自己的信息
    getSelfInfo();
} else if ($_GET['token'] === 'getFriendUin2') {//通过uin获取真实qq号
    get_friend_uin2($_POST['uin']);
} else if ($_GET['token'] === 'getFriendInfo2') {//通过uin获取账号详细信息
    get_friend_info2($_POST['uin']);
} else if ($_GET['token'] === 'getSingleLongNick2') {//通过uin获取个性签名
    get_single_long_nick2($_POST['uin']);
} else if ($_GET['token'] === 'getOnlineBuddies2') {
    get_online_buddies2();
} else if ($_GET['token'] === 'sendBuddyMsg2') {//通过uin发送消息
    send_buddy_msg2($_POST['uin'], $_POST['msg']);
} else if ($_GET['token'] === 'sendQunMsg2') {//通过gid发送群消息
    send_qun_msg2($_POST['gid'], $_POST['msg']);
} else if ($_GET['token'] === 'pullMsg') {//拉取消息
    pull_msg();
} else if ($_GET['token'] === 'getFriends') {//获取所有好友信息 POST!
    get_user_friends2($_POST['hash']);
} else if ($_GET['token'] === 'getGroups') {//获取所有群信息    POST!
    get_group_name_list_mask2($_POST['hash']);
} else if ($_GET['token'] === 'getDiscus') {//获取所有讨论组信息
    get_discus_list();
}


require('apis/manager.php');
?>
