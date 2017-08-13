<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/15
 * Time: 21:13
 */

require_once '../db/mysql_operate.php';

$pageNo = intval($_GET['page']);
$countNum = $pageNo*30;
$conditions = array();
$params = "a.id,a.content,a.praiseNum, a.datetime, b.nickName, b.photoUrl";
$limit="limit $pageNo, 30";
$order_by_list=array("a.datetime" => "desc");
$left_join_tab=array("js_user" => array("as"=>"b", "param"=>"id", ""=>"userId"));
$result = db_select('js_user_article', $conditions, $params, $limit, $order_by_list, $left_join_tab);
if ($result != null && count($result) > 0) {
    foreach ($result as $key => $value) {
        $nickName = "匿名玩家";
        $photoUrl = "";
        if ($value["nickName"] != null && $value["nickName"] != "") {
            $nickName = $value["nickName"];
        }
        if ($value["photoUrl"] != null && $value["photoUrl"] != "") {
            $photoUrl = $value["photoUrl"];
        }
        ?>
        <li>
            <div class="po-avt-wrap">
                <img class="po-avt data-avt" src="<?php echo $photoUrl ?>">
            </div>
            <div class="po-cmt">
                <div class="po-hd">
                    <p class="po-name"><span class="data-name"><?php echo $nickName?></span></p>
                    <div class="post">
                        <div class="kwd"><p id="kwd"><?php echo $value["content"]; ?></div>
                        <p>
                            <?php
                            $conditions = array("a.aid="=>$value["id"]);
                            $params = "a.url";
                            $img_result = db_select('js_user_article_img', $conditions, $params);
                            if ($img_result != null && count($img_result) > 0) {
                                foreach ($result as $key2 => $value2) {
                                    ?>
                                    <img class="list-img" src="<?php echo $value2["url"] ?>" data-original="" style="height: 80px; display: block;"  typename="wxpic">
                                    <?php
                                }
                            }
                            ?>
                        </p>
                    </div>
                    <p class="time"><?php echo $value["datetime"]; ?></p>
                    <div style="float:right;position:relative;top:-30px;height:10px;margin-right: 5px"
                         class="feed">
                        <div style="background-position:left center;height:55px;width:40px;background-size:cover;" class="heart" id="like441070" rel="like"></div>
                        <div class="likeCount"><?php echo $value["praiseNum"]; ?>></div>
                    </div>
                </div>
                <div class="r"></div>
            </div>
        </li>
        <?php
    }
}