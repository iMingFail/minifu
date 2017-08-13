(function($) {
    $.extend({
        myTime: {
            /**
             * 当前时间戳
             * @return <int>    unix时间戳(秒)
             */
            CurTime: function(){
                return Date.parse(new Date())/1000;
            },
            /**
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20 日期格式
             * @return <int>    unix时间戳(秒)
             */
            DateToUnix: function(string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                    )).getTime() / 1000;
            },
            /**
             * 时间戳转换日期
             * @param <int> unixTime  待时间戳(秒)
             * @param <bool> isFull  返回完整时间(Y-m-d 或者 Y-m-d H:i:s)
             * @param <int> timeZone  时区
             */
            UnixToDate: function(unixTime, isFull, timeZone) {
                if (typeof (timeZone) == 'number')
                {
                    unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                }
                var time = new Date(unixTime * 1000);
                var ymdhis = "";
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();
                if (isFull === true)
                {
                    ymdhis += " " + time.getUTCHours() + ":";
                    ymdhis += time.getUTCMinutes() + ":";
                    ymdhis += time.getUTCSeconds();
                }
                return ymdhis;
            }
        }
    });
})(jQuery);

var data = [
    [1475856540,1246.22,1246.87,1246.07,1246.13],
    [1475856600,1246.13,1246.82,1246.07,1246.55],
    [1475856660,1246.55,1246.82,1246.30,1246.66],
    [1475856720,1246.45,1246.95,1246.40,1246.78],
    [1475856780,1246.02,1246.97,1246.02,1246.80],
    [1475856840,1246.01,1247.28,1246.01,1247.25],
    [1475856900,1247.24,1247.54,1247.10,1247.42],
    [1475856960,1247.72,1247.93,1247.27,1247.81],
    [1475857020,1247.68,1249.40,1247.20,1249.38],
    [1475857080,1249.41,1249.51,1247.51,1247.51],
    [1475857140,1247.51,1247.99,1247.13,1247.89],
    [1475857200,1247.89,1248.80,1247.08,1248.80],
    [1475857260,1248.79,1248.99,1248.54,1248.11],
    [1475857320,1248.11,1248.98,1248.01,1248.79],
    [1475857380,1248.78,1249.02,1248.69,1249.02],
    [1475857440,1249.02,1249.42,1249.02,1249.06],
    [1475857500,1249.06,1249.95,1249.04,1249.11],
    [1475857560,1249.11,1249.92,1249.09,1249.26],
    [1475857620,1249.27,1249.92,1249.14,1249.33],
    [1475857680,1249.39,1249.92,1249.21,1249.22],
    [1475857740,1249.22,1249.24,1248.11,1248.17],
    [1475857800,1248.19,1248.92,1248.15,1248.61],
    [1475857860,1248.61,1248.92,1248.40,1248.67],
    [1475857920,1248.55,1248.92,1248.42,1248.43],
    [1475857980,1248.41,1248.92,1248.32,1248.42],
    [1475858040,1248.66,1248.92,1248.55,1248.82],
    [1475858100,1248.82,1249.11,1248.67,1249.11],
    [1475858160,1249.19,1249.23,1247.67,1247.90],
    [1475858220,1247.98,1248.92,1247.67,1248.42],
    [1475858280,1248.46,1249.92,1248.44,1249.47]
];
var chart = new AChart({
    theme : AChart.Theme.SmoothBase,
    id : 'canvas',
    forceFit : true, //自适应宽度
    fitRatio : 0.5, // 高度是宽度的 0.5
    plotCfg : {
        margin : [15,20,25,40] //画板的边距
    },
    xAxis : {
        type : 'timeCategory' ,
        formatter : function(value)   {
            return Chart.Date.format(new Date(value),'hh:MM');
            // return Chart.Date.format($.myTime.UnixToDate(value, true),'hh:MM');
        },
        labels : {
            label : {
                'font-size': '11'
            }
        },
        animate : false
    },
    yAxis : [{
        position: 'left',
        grid : {
            animate : false
        },
        labels :{
            label : {
                'font-size': '10',
                fill : '#333',
                'text-anchor' : 'end',
                x: -5
            }
        }
    }],
    xTickCounts : [1,5],//设置x轴tick最小数目和最大数目
    yTickCounts : [1,3],//设置x轴tick最小数目和最大数目
    tooltip : {
        valueSuffix : '￥',
        shared: false,
        crosshairs:true
    },
    series : [{
        type: 'candlestick',
        // name: '股票',
        tipNames: ['开盘','最高','最低', '收盘'],
        suffix: '元',
        data: data
    }]
});

chart.render();