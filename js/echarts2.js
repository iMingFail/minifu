// app.title = '2015 年上证指数';
var myChart = echarts.init(document.getElementById('myChart'));
window.onresize = myChart.resize;

// 数据意义：开盘(open)，收盘(close)，最低(lowest)，最高(highest)
var data = {categoryData:[], values:[]};

function splitData(rawData) {
    var categoryData = [];
    var values = []
    for (var i = 0; i < rawData.length; i++) {
        categoryData.push(rawData[i].splice(0, 1)[0]);
        values.push(rawData[i])
    }
    return {
        categoryData: categoryData,
        values: values
    };
}

function calculateMA(dayCount) {
    var result = [];
    for (var i = 0, len = data.values.length; i < len; i++) {
        if (i < dayCount) {
            result.push('-');
            continue;
        }
        var sum = 0;
        for (var j = 0; j < dayCount; j++) {
            sum += data.values[i - j][1];
        }
        result.push(sum / dayCount);
    }
    return result;
}


function loadData() {
    var limit = $('#time_diff li a.changed').attr("type");
    var type= $(".product_switch li.sw_active").attr("index");
    $.ajax({
        type: "POST",
        url: "action/dataAction.php?action=get_data",
        dataType: "json",
        data: {"type": type, "limit":limit},
        success: function (json) {
            if (json.success == 1) {
                var len = 0;
                var d = new Array();
                var close_value = json.data[0].close_value;
                for (var i = (json.data.length-1); i >= 0; i--) {
                    var d2 = [json.data[i].datetime, parseFloat(json.data[i].open_value), parseFloat(json.data[i].close_value), parseFloat(json.data[i].min_value), parseFloat(json.data[i].max_value)];
                    d[len] = d2;
                    len++;
                }
                data = splitData(d);

                var value = json.data[0].close_value;

                $(".now_price").html(json.data[0].close_value);
                $(".position_tab_price").html(json.data[0].close_value);
                var color = "#FF1B00";
                $(".now_price").removeClass("drop").addClass("rise");
                if (parseFloat(json.data[0].open_value) > parseFloat(json.data[0].close_value)) {
                    color = "#0BFF00";
                    $(".now_price").removeClass("rise").addClass("drop");
                }

                var txt_len = 2;
                if ($(".product_switch li.sw_active").attr("index") == 4 || $(".product_switch li.sw_active").attr("index") == 5) {
                    txt_len = 5;
                } else if ($(".product_switch li.sw_active").attr("index") == 2) {
                    txt_len = 3;
                }

                myChart.setOption({
                    // backgroundColor: '#21202D',
                    // legend: {show:false,left:'0%',top:'0%',animation:true,data:[$("#optionname").html(),'MA3','MA5'],inactiveColor: '#777',textStyle:{color:'#fff'}, selected:{'MA3':true,'MA5':true}},
                    tooltip:{trigger: 'axis', formatter: function (params) {
                        var res = params[0].name;
                        res += '<br/>开盘 : ' + params[0].value[0].toFixed(txt_len);
                        res += ' 最高 : ' + params[0].value[3].toFixed(txt_len);
                        res += '<br/>收盘 : ' + params[0].value[1].toFixed(txt_len);
                        res += ' 最低 : ' + params[0].value[2].toFixed(txt_len);
                        return res;
                    }},
                    grid:{left: '1%',right:'2%',bottom:'0%',top:'5%',containLabel:true,animation:true,},
                    xAxis:{type:'category',data:data.categoryData,animation:true,boundaryGap: true,axisLine:{lineStyle:{color:'#eeeeee'}},axisLabel:{interval:3}},
                    yAxis:{type:'value',position:'left',scale:true,animation:true,axisLine:{lineStyle:{color:'#eeeeee'}},splitLine:{show:true},axisLabel:{formatter:function(value){return parseFloat(value).toFixed(txt_len);}}},
                    series:[
                        {
                            name: $("#optionname").html(),connectNulls:true,animation:true,type:'candlestick',data:data.values,itemStyle:{normal:{color:'#FF1B00',color0:'#0BFF00',lineStyle:{width: 1,color: '#FF1B00',color0:'#0BFF00'}}},
                            markLine: {
                                animation:false,
                                symbol: ['none', 'none'],
                                lineStyle:{
                                    normal:{
                                        width:1
                                    }
                                },
                                data : [[
                                    {xAxis:0,yAxis:value,itemStyle:{normal:{color:color,label:{show:true,position:'start',textStyle:{fontSize:'13',fontWeight:'bold'}}}}},
                                    {xAxis:(json.data.length-1),yAxis:value,value:value}
                                ]]
                            }
                        },
                        {name:'MA3',type:'line',data:calculateMA(3),smooth:true,animation:false,showSymbol:false,lineStyle:{normal:{opacity:1,width:2}}},
                        {name:'MA5',type:'line',data:calculateMA(5),smooth:true,animation:false,showSymbol:false,lineStyle:{normal:{opacity:1,width:2}}},
                        {name:'MA15',type:'line',data:calculateMA(15),smooth:true,animation:false,showSymbol:false,lineStyle:{normal:{opacity:1,width:2}}}
                    ]
                });
            } else {
                // alert("今天星期四");
                // window.location.href = "index.php?t=3";
                // alert("今天星期四");
            }
        }
    });
}