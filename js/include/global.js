var global = {};
global.baseUrl = 'http://localhost:8085/qq/php/proxy.php?';
global.api = {
    openUrl: global.baseUrl + 'token=open&s=' + Math.random(),//POST url
    getvfwebqq: global.baseUrl + 'token=getvfwebqq&s=' + Math.random(),//GET
    doLogin: global.baseUrl + 'token=doLogin&s=' + Math.random(),//GET
    getSelfInfo: global.baseUrl + 'token=getSelfInfo&s=' + Math.random(),//GET
    getFriendUin2: global.baseUrl + 'token=getFriendUin2&s=' + Math.random(),//POST uin
    getFriendInfo2: global.baseUrl + 'token=getFriendInfo2&s=' + Math.random(),//POST uin
    getSingleNick2: global.baseUrl + 'token=getSingleLongNick2&s=' + Math.random(),
    sendBuddyMsg2: global.baseUrl + 'token=sendBuddyMsg2&s=' + Math.random(),
    sendQunMsg2: global.baseUrl + 'token=sendQunMsg2&s=' + Math.random(),
    getUinAndPtwebqq: global.baseUrl + 'token=getUinAndPtwebqq&s=' + Math.random(),
    getFriends: global.baseUrl + 'token=getFriends&s=' + Math.random(),
    getGroups: global.baseUrl + 'token=getGroups&s=' + Math.random(),
    getDiscus: global.baseUrl + 'token=getDiscus&s=' + Math.random(),
    pullMsg: global.baseUrl + 'token=pullMsg&s=' + Math.random(),
    getOnlineBuddies2: global.baseUrl + 'token=getOnlineBuddies2&s=' + Math.random(),
    baidubaike: global.baseUrl + 'token=baidubaike&s=' + Math.random(),
    weather: global.baseUrl + 'token=weather&s=' + Math.random(),
    joke: global.baseUrl + 'token=joke&s=' + Math.random(),
    tuling: global.baseUrl + 'token=tuling&s=' + Math.random()

};
global.getNick = function (uin) {
    return eval("(" + $.ajax({
            type: "post",
            url: global.api.getFriendInfo2,
            data: {"uin": uin},
            async: false
        }).responseText + ")").result.nick;
};
