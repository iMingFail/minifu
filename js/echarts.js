var data = [
    // 开盘，收盘，最低，最高
    [1246.22,1246.13,1246.44,1246.87],
    [1246.13,1246.55,1246.07,1246.82],
    [1246.55,1246.66,1246.30,1246.82],
    [1246.45,1246.78,1246.40,1246.95],
    [1246.02,1246.80,1246.02,1246.97],
    [1246.01,1247.25,1246.01,1247.28],
    [1247.24,1247.42,1247.10,1247.54],
    [1247.72,1247.81,1247.27,1247.93],
    [1247.68,1249.38,1247.20,1249.40],
    [1249.41,1247.51,1247.51,1249.51],
    [1247.51,1247.89,1247.13,1247.99],
    [1247.89,1248.80,1247.08,1248.80],
    [1248.79,1248.11,1248.54,1248.99],
    [1248.11,1248.79,1248.01,1248.98],
    [1248.78,1249.02,1248.69,1249.02],
    [1249.02,1249.06,1249.02,1249.42],
    [1249.06,1249.11,1249.04,1249.95],
    [1249.11,1249.26,1249.09,1249.92],
    [1249.27,1249.33,1249.14,1249.92],
    [1249.39,1249.22,1249.21,1249.92],
    [1249.22,1248.17,1248.11,1249.24],
    [1248.19,1248.61,1248.15,1248.92],
    [1248.61,1248.67,1248.40,1248.92],
    [1248.55,1248.43,1248.42,1248.92],
    [1248.41,1248.42,1248.32,1248.92],
    [1248.66,1248.82,1248.55,1248.92],
    [1248.82,1249.11,1248.67,1249.11],
    [1249.19,1247.90,1247.67,1249.23],
    [1247.98,1248.42,1247.67,1248.92],
    [1248.46,1249.47,1248.44,1249.92]
];
var time = [
    "09:00","09:01","09:02","09:03","09:04","09:05","09:06","09:07","09:08","09:09",
    "09:10","09:11","09:12","09:13","09:14","09:15","09:16","09:17","09:18","09:19",
    "09:20","09:21","09:22","09:23","09:24","09:25","09:26","09:27","09:28","09:29",
];
// 路径配置
require.config({
    paths: {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});
// 使用
require(
    [
        'echarts',
        'echarts/chart/k', // 使用k图就加载k模块，按需加载
    ],
    function (ec) {
        // 基于准备好的dom，初始化echarts图表
        myChart = ec.init(document.getElementById('myChart'));
        option = {
            animation:false,
            color:'#dfbd6c',
            grid:{
                x: 10,
                y: 10,
                x2: 55,
                y2: 25,
            },
            tooltip : {
                trigger: 'axis',
                show:false,
                formatter: function (params) {
                    var res = "";//params[0].seriesName + ' ' + params[0].name;
                    // res += '最高 : ' + params[0].value[3].toFixed(2);
                    res += '<br/>开盘 : ' + params[0].value[0].toFixed(2);
                    res += '<br/>收盘 : ' + params[0].value[1].toFixed(2);
                    // res += '<br/>最低 : ' + params[0].value[2].toFixed(2);
                    return res;
                }
            },
            dataZoom : {
                show : false,
                realtime: true,
                start : 0,
                end : 100
            },
            xAxis : [
                {
                    type : 'category',
                    axisTick: {onGap:false, interval:function(index, value){
                        // alert(index);
                        if (index%5==0) {
                            return true;
                        } else {
                            return false;
                        }
                    }},
                    splitLine: {show:false},
                    boundaryGap:true,
                    axisLabel:{
                        interval:4
                    },
                    // axisTick:{interval:0},
                    data:[]
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    position:'right',
                    scale:true,
                    boundaryGap:[0.01, 0.01],
                    // max:1214.93,
                    // min:1210.11,
                    axisTick:{show:false},
                    axisLabel:{
                        formatter:function (value) {
                            return parseFloat(value).toFixed(2);
                            // return value;
                        }
                    }
                }
            ],
            series : [
                {
                    type:'k',
                    barMaxWidth: 36,
                    barWidth: 7,

                    data:[],
                    textStyle:{
                        color:"#fff"
                    },
                    itemStyle: {
                        normal: {
                            color: '#ff3200',          // 阳线填充颜色
                            color0: '#00bfb5',      // 阴线填充颜色
                            lineStyle: {
                                width: 1,
                                color: '#ff3200',   // 阳线边框颜色
                                color0: '#00bfb5'   // 阴线边框颜色
                            }
                        },
                        emphasis: {
                            // color: 各异,
                            // color0: 各异
                        }
                    }
                }
            ]
        };

        myChart.setOption(option);
        window.onresize = myChart.resize;
        /*myChart.showLoading({
            text: '正在努力的读取数据中...',    //loading话术
        });*/
    }
);

function loadData() {
    var limit = $('#time_diff li a.changed').attr("type");
    var type= $(".product_switch li.sw_active").attr("index");
    option.series[0].data = [];
    option.xAxis[0].data = [];
    myChart.setOption(option);
    window.onresize = myChart.resize;
    $.ajax({
        type: "POST",
        url: "action/dataAction.php?action=get_data",
        dataType: "json",
        data: {"type": type, "limit":limit},
        success: function (json) {
            if (json.success == 1) {
                var len = 0;
                for (var i = (json.data.length-1); i >= 0; i--) {
                    option.series[0].data[len] = [
                        parseFloat(json.data[i].open_value),
                        parseFloat(json.data[i].close_value),
                        parseFloat(json.data[i].min_value),
                        parseFloat(json.data[i].max_value)
                    ];
                    option.xAxis[0].data[len] = json.data[i].datetime;
                    len++;
                }
                myChart.setOption(option);
                if (null != json.data[0].max_value && "null" != json.data[0].max_value && "" != json.data[0].max_value) {
                    add_mark_line(parseFloat(json.data[0].max_value), "#ff3200");
                }
                load_temp_data_flag = true;
            }
        }
    });
}
function load_temp_data() {
    var type= $(".product_switch li.sw_active").attr("index");
    if (load_temp_data_flag == true) {
        var len_line = option.series[0].data.length;
        $.getJSON('action/dataAction.php?action=get_temp_data&type='+type, function (json) {
            if (json.success == 1) {
                // 开盘，收盘，最低，最高
                // var closeing = option.series[0].data[len_line - 2][1]; //开盘值
                option.series[0].data[len_line - 1] = [
                    parseFloat(json.data[0].open_value),
                    parseFloat(json.data[0]._temp_value),
                    parseFloat(json.data[0].min_value),
                    parseFloat(json.data[0].max_value)
                ];
                if (null != json.data[0].max_value && "null" != json.data[0].max_value && "" != json.data[0].max_value) {
                    $(".now_price").html(json.data[0]._temp_value);
                }
                var color = "#ff3200";
                $(".now_price").removeClass("drop").addClass("rise");
                if (parseFloat(option.series[0].data[option.series[0].data.length - 1][0]) > parseFloat(json.data[0]._temp_value)) {
                    color = "#00bfb5";
                    $(".now_price").removeClass("rise").addClass("drop");
                }
                if (null != json.data[0]._temp_value && "null" != json.data[0]._temp_value && "" != json.data[0]._temp_value) {
                    add_mark_line(parseFloat(json.data[0]._temp_value).toFixed(2), color);
                }
            }
        });
    }
}
function del_mark_line() {
    myChart.delMarkLine(0, "当前");
    myChart.setOption(option);
}
function add_mark_line(value, color) {
    del_mark_line();
    myChart.addMarkLine(0, {
        data : [
            [
                {name:'当前', xAxis: -1,yAxis:value,itemStyle:{normal:{color:color,label:{show:true,textStyle:{fontSize:'12',fontWeight:'bold'}}}}},
                {xAxis:100,yAxis:value,value:value}
            ]
        ]
    });
}