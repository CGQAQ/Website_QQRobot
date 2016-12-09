//var functions = (function () {
    var functions = {
        match: function match(uin, msg) {
            if (typeof msg !== 'string') {
              return null;
            }
            var arr = msg.split(" ");
            var order = arr[0];
            var param = "";
            for (var i = 0; i < arr.length; i++) {
                if (i == 0) {
                    continue;
                }
                if (i == arr.length - 1){
                  param += arr[i];
                  break;
                }
                param += arr[i]+" ";
            }

            if (order === "#baike" || order === "#百科") {
                return this.baike(param);
            }
            else if (order === "#echo" || order === "#输出"){
                return this.echo(param);
            }
            else if(order === "#小毅"||order === "#e"){
                return this.smarte(param) || '接口故障， 请告诉主人修理我！';
            }
            else if (order === '#天气' || order === '#weather') {
                return this.weather(param);
            }
            else if (order ==='#joke' || order === '#笑话') {
                return this.joke();
            }
            else if (order === "#help" || order ==="#帮助") {
              return this.help();
            }
        },
        baike: function (msg) {
            var ret = $.ajax({
                type:'post',
                url:global.api.baidubaike,
                async:false,
                data:{
                    'key':msg
                }
            });
            return ret.responseText;
        },
        echo: function(msg){
            return msg;
        },
        smarte:function(msg){
          var ret = $.ajax({
              type:'post',
              url:global.api.tuling,
              async:false,
              data:{
                  'info':msg
              }
          });
          return ret.responseText;
        },
        weather:function(msg){
          if (!isNaN(msg)) {
            return '你家住在数字名的城市里吗？'
          }
          var x = msg.split('-');
          var ret = $.ajax({
              type:'post',
              url:global.api.weather,
              async:false,
              data:{
                  'key':x[0]
              }
          });
          var origin = ret.responseText;
          var json = eval('(' + origin + ')');
          if (json.error_code === 207302) {
            return "查询不到该城市的相关信息!";
          }
          else if(json.error_code === 207301){
             return '错误的查询城市名!';
          }
          else if(json.error_code === 207303){
            return '服务器端网络错误！'
          }
          json = json.result.data;
          var realtime = json.realtime;
          var tomorrow = json.weather[1];
          var aftertomorrow = json.weather[2];
          if (x.length > 1) {
            if (x[1] === '实时') {
              var str = '';

              str += realtime.city_name + ' 今天：\n';
              str += '日期：' + realtime.date + '\n';
              str += '阴历：' + realtime.moon + '\n';
              str += '实时温度：' + realtime.weather.temperature + '℃' + '\n';
              str += '实时湿度：' + realtime.weather.humidity + '%' + '\n';
              str += '实时天气：' + realtime.weather.info + '\n';
              str += '实时风力及风向： ' + realtime.wind.direct + ' ' + realtime.wind.power;

              return str;
            }
            else if(x[1] === '指数'){
              var str = '';
              var ret = [];

              str = '';
              str += realtime.city_name + ' 实时：\n';
              str += '生活指数：  \n';
              str += '穿衣指数：' + json.life.info.chuanyi[0] + '  ' + json.life.info.chuanyi[1] + '\n';
              str += '感冒指数：' + json.life.info.ganmao[0] + '  ' + json.life.info.chuanyi[1] + '\n';
              str += '空调指数：' + json.life.info.kongtiao[0] + '  ' + json.life.info.kongtiao[1];
              ret.push(str);
              str = '';
              str += '污染指数：' + json.life.info.wuran[0] + '  ' + json.life.info.wuran[1] + '\n';
              str += '洗车指数：' + json.life.info.xiche[0] + '  ' + json.life.info.xiche[1] + '\n';
              str += '运动指数：' + json.life.info.yundong[0] + '  ' + json.life.info.yundong[1] + '\n';
              str += '紫外线：' + json.life.info.ziwaixian[0] + '  ' + json.life.info.ziwaixian[1] + '\n';
              if (json.pm25.pm25 !== undefined) {
                str += 'pm2.5: ' + json.pm25.pm25.curPm + ' ' + json.pm25.pm25.quality + ' ' + json.pm25.pm25.des;
              }
              ret.push(str);
              str = null;

              return ret;
            }
            else if (x[1] === '明天') {
              var str = '';
              str += realtime.city_name + ' 明天：\n';
              str += '日期：' + tomorrow.date + '\n';
              str += '阴历：' + tomorrow.nongli + '\n';
              str += '黎明：' + tomorrow.info.dawn[1] + ', ' + tomorrow.info.dawn[2] + '℃, '+ tomorrow.info.dawn[3] + '(' + tomorrow.info.dawn[4] + ')\n';
              str += '天气: ' + tomorrow.info.day[1] + '转' + tomorrow.info.night[1] + ', ' + tomorrow.info.day[2] + '℃' + ' ~ ' +tomorrow.info.night[2] + '℃, ' + tomorrow.info.day[3] + '(' + tomorrow.info.day[4] + ')\n';
              str += '日出：' + tomorrow.info.day[5] + '\n';
              str += '日落：' + tomorrow.info.night[5];
              return str;
            }
            else if (x[1] === '后天') {
              var str = '';
              str += realtime.city_name + ' 后天：\n';
              str += '日期：' + aftertomorrow.date + '\n';
              str += '阴历：' + aftertomorrow.nongli + '\n';
              str += '黎明：' + aftertomorrow.info.dawn[1] + ', ' + aftertomorrow.info.dawn[2] + '℃, '+ aftertomorrow.info.dawn[3] + '(' + aftertomorrow.info.dawn[4] + ')\n';
              str += '天气: ' + aftertomorrow.info.day[1] + '转' + aftertomorrow.info.night[1] + ', ' + aftertomorrow.info.day[2] + '℃' + ' ~ ' +aftertomorrow.info.night[2] + '℃, ' + aftertomorrow.info.day[3] + '(' + aftertomorrow.info.day[4] + ')\n';
              str += '日出：' + aftertomorrow.info.day[5] + '\n';
              str += '日落：' + aftertomorrow.info.night[5];
              return str;
            }
            else {
              return '参数错误！'
            }
          }
          else {
            var str = '';
            str += realtime.city_name + ' 实时：\n';
            str += '日期：' + realtime.date + '\n';
            str += '阴历：' + realtime.moon + '\n';
            str += '实时温度：' + realtime.weather.temperature + '℃' + '\n';
            str += '实时湿度：' + realtime.weather.humidity + '%' + '\n';
            str += '实时天气：' + realtime.weather.info + '\n';
            str += '实时风力及风向： ' + realtime.wind.direct + ' ' + realtime.wind.power;

            return str;
          }
        },
        joke:function(){
          var ret = $.ajax({
              type:'get',
              url:global.api.joke,
              async:false,
            });
            return ret.responseText;
        },
        help: function(){
          var ret = "#echo|#输出 输出信息\n";
          ret += '#e|#小毅 与小毅聊天\n';
          ret += '#baike|#百科 百度百科\n';
          ret += '#weather|#天气[-实时] 地点（-实时|指数|明天|后天）天气预报\n'
          ret += '#joke|#笑话 笑话\n'
          ret += '#help|#帮助 帮助';
          return ret;
        }
    }
//    return function(){return functions;}();
//})()
