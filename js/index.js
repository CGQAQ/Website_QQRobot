/**
 * Created by CG on 2016/6/5.
 */
$(function () {

    function getQR() {
        var url = "http://localhost:8085/qq/php/proxy.php?token=getQR&s=" + Math.random();
        $(".qrcode img").attr("src", url);
    }

    /**
     *计算hash
     * @param x qq账号
     * @param I ptwebqq
     * @return hash值
     */
    function u(x, I) {
        x += "";
        for (var N = [], T = 0; T < I.length; T++)N[T % 4] ^= I.charCodeAt(T);
        var U = ["EC", "OK"], V = [];
        V[0] = x >> 24 & 255 ^ U[0].charCodeAt(0);
        V[1] = x >> 16 & 255 ^ U[0].charCodeAt(1);
        V[2] = x >> 8 & 255 ^ U[1].charCodeAt(0);
        V[3] = x & 255 ^ U[1].charCodeAt(1);
        U = [];
        for (T = 0; T < 8; T++)U[T] = T % 2 == 0 ? N[T >> 1] : V[T >> 1];
        N = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"];
        V = "";
        for (T = 0; T < U.length; T++) {
            V += N[U[T] >> 4 & 15];
            V += N[U[T] & 15]
        }
        return V
    }

    function calcHash(o) {
        var json = eval('(' + o + ')');
        return u(json.uin.substr(1), json.ptwebqq);
    }

    /**
     * 通过键获取cookie值
     * @param name cookie 的键
     * @return {null}cookie的值
     */
    function getCookie(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
        if (arr = document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }

    function getQRState() {
        var url = "http://localhost:8085/qq/php/proxy.php?token=getQRState&s=" + Math.random();
        $.get(url, function (data) {
            var value = data;
            //console.log(value);
            var state = value.split(',')[4].split('。')[0].substring(1);

            if (state === "二维码未失效") {
                return;
            }
            else if (state === "二维码已失效") {
                alert("二维码已失效, 点击确定， 刷新二维码！");
                clearInterval(id);
                location.reload();
            }
            else if (state === "二维码认证中") {
                //改变UI 提醒用户 二维码认证中
                if (UIState == 1)
                    toggleUI();
            }
            else {
                //登录成功啦
                clearInterval(id);
                var url = data.split(',')[2].replace('\'', '');
                $.post(global.api.openUrl, {'url': url}
                    , success = function () {
                        $.get(global.api.getvfwebqq, success = function (o) {
                            $.get(global.api.doLogin, success = function (o) {
                                    onLogin();
                                }
                            )
                        });

                    });
                //console.log(url);


            }
            //console.log(value.split(',')[4].split('。')[0].substring(1));
        });
    }


    function pullmsg() {
        $.get(global.api.pullMsg, success = function (o) {

            if (o.indexOf('<center><h1>404 Not Found</h1></center>') !== -1) {
              console.log(o);
              pullmsg();
              return;
            }
            var json = eval('(' + o + ')');
            //print(json);
            //print(typeof json.retcode);
            //"{"result":[{"poll_type":"group_message","value":{"content":[["font",{"color":"000000","name":"微软雅黑","size":10,"style":[0,0,0]}],"何事长向别时圆"],"from_uin":2007966834,"group_code":2007966834,"msg_id":14130,"msg_type":0,"send_uin":1044774583,"time":1471493206,"to_uin":1933237931}}],"retcode":0}↵"
            if(json.retcode === 0 && json.result != undefined){
                var msg=json.result[0].value.content[1];
                console.log('bingo: '+ msg);

                if(json.result[0].poll_type === 'group_message'){
                    onMsg('group', json.result[0].value.send_uin, json.result[0].value.from_uin, msg);
                }
                else if(json.result[0].poll_type === 'message'){
                    onMsg('message', json.result[0].value.from_uin, null, msg);
                }

                //onMsg(msg);
            }else {
                console.log('retcode:' + json.retcode);
            }
            pullmsg();
        });
    }
    function sayToQun(id, group, msg){
        var msgid = 10000001;
        if (typeof msg === 'object') {
          for(var i=0; i<msg.length; i++){
            $.ajax({
              url:global.api.sendQunMsg2,
              data: {'gid':group,'msg':msg[i]},
              async:false,
              type:'post'
            });
            msgid ++;
          }
          return;
        }
        $.post(global.api.sendQunMsg2, {'gid':group,'msg':msg});
    }
    function say(id, msg) {
      var msgid = 10000001;
      if (typeof msg === 'object') {
        for(var i=0; i<msg.length; i++){
          $.ajax({
            url:global.api.sendBuddyMsg2,
            data: {'uin':id,'msg':msg[i]},
            async:false,
            type:'post'
          });
          msgid ++;
        }
        return;
      }
        $.post(global.api.sendBuddyMsg2, {'uin':id, 'msg':msg});
    }
    function getOnlineBuddies2(){
      $.get(global.api.getOnlineBuddies2);
    }


    function onLogin() {
        $('#text').innerHTML = "小毅运行中……";
        getOnlineBuddies2();
        pullmsg();
    }
    function onMsg(type, id, group, msg){
        var a = new Date();
        var ret = functions.match(id, msg);
        if(ret !== undefined && ret !== null){
            if(type === 'group') {
              sayToQun(id, group, ret);
            }
            else if (type === 'message') {
              say(id, ret);
            }
        }
        var b = new Date();
        console.log("Handle Msg: " + new Date(b-a).getSeconds() + "s");
    }

    var UIState = 1;

    function toggleUI() {
        switch (UIState) {
            case 1:
                $(".content").css("display", "none");
                $(".scanning").css("display", "block");
                UIState = 2;
                break;
            case 2:
                $(".content").css("display", "block");
                $(".scanning").css("display", "none");
                UIState = 1;
                break;
        }
    }

    getQR();
    (function () {
        var url = "http://localhost:8085/qq/php/proxy.php?token=cookie&s=" + Math.random();
        $.get(url);
    })();

    $(".getQR").click(function () {
        getQR();
        //console.log("hehe ");
    });
    $(".back").click(function () {
        toggleUI();
        clearInterval(id);
        location.reload();
    })
    var id = setInterval(function () {
        getQRState()
    }, 2000);
    //console.log(getQRState());
    //console.log( $(".getQR").click());
})
