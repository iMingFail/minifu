<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/17
 * Time: 0:02
 */
?>
<script src=http://211.149.232.173:3000/socket.io/socket.io.js"></script>
<script src="js/jquery-1.js"></script>
六十秒<script type="text/javascript">
    $(function(){
        var socket = io.connect('http://211.149.232.173:3000');
        socket.on('user_withdrawal', function (data) {
            $('#m_bg_music').remove();
            $('#bg_music').append('<embed id="m_bg_music"  loop=true  volume="60" autostart=true hidden=true src="resources/5103.mp3" />');
        });
        socket.on('user_pay', function (data) {
            $('#m_bg_music').remove();
            $('#bg_music').append('<embed id="m_bg_music"  loop=true  volume="60" autostart=true hidden=true src="resources/4134.mp3" />');
        });
        /*socket.on('up_out_money_num', function (data) {
             $("#out_moeny_num").html(data.list[0].out_moeny_num);
        });*/
    });
</script>

<header class="am-topbar am-topbar-inverse admin-header">
    <div class="am-topbar-brand">
        <strong>迷你富</strong> <small id="bg_music_btn">代理商后台管理系统</small>
        <!--背景音乐-->
        <div id="bg_music"></div>
    </div>
    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

        <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
            <!--      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">0</span></a></li>-->
            <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                    <span class="am-icon-user"></span> <?php echo $_SESSION['agent_admin']["mobilePhone"];?> <span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li><a href="admin_operate.php?action=edit&id=<?php echo $_SESSION['agent_admin']["id"];?>"><span class="am-icon-user"></span> 资料</a></li>
<!--                    <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>-->
                    <li><a href="login.php"><span class="am-icon-power-off"></span> 退出</a></li>
                </ul>
            </li>
            <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
        </ul>
    </div>
</header>
