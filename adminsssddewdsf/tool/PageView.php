<?php

/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/17
 * Time: 2:20
 */
class PageView
{
    /**页码**/
    public $pageNo = 1;
    /**页大小**/
    public $pageSize = 20;
    /**共多少页**/
    public $pageCount = 0;
    /**总记录数**/
    public $totalNum = 0;
    /**偏移量,当前页起始行**/
    public $offSet = 0;
    /**每页数据**/
    public $pageData = array();

    /**是否有上一页**/
    public $hasPrePage = true;
    /**是否有下一页**/
    public $hasNextPage = true;

    public $pageNoList = array();

    public $jsFunction ='jsFunction';
    /**
     *
     * @param unknown_type $count 总行数
     * @param unknown_type $size 分页大小
     * @param unknown_type $string
     */
    public function __construct($count=0, $size=20,$pageNo=1,$pageData =array(),$jsFunction='jsFunction'){
        $this->totalNum = $count;//总记录数
        $this->pageSize = $size;//每页大小
        $this->pageNo = $pageNo;
        //计算总页数
        $this->pageCount = ceil($this->totalNum/$this->pageSize);
        $this->pageCount = ($this->pageCount<=0)?1:$this->pageCount;
        //检查pageNo
        $this->pageNo = $this->pageNo == 0 ? 1 : $this->pageNo;
        $this->pageNo = $this->pageNo > $this->pageCount? $this->pageCount : $this->pageNo;

        //计算偏移
        $this->offset = ( $this->pageNo - 1 ) * $this->pageSize;
        //计算是否有上一页下一页
        $this->hasPrePage = $this->pageNo == 1 ?false:true;
        $this->hasNextPage = $this->pageNo >= $this->pageCount ?false:true;
        $this->pageData = $pageData;
        $this->jsFunction = $jsFunction;
    }
    /**
     * 分页算法
     * @return
     */
    private function generatePageList(){
        $pageList = array();
        if($this->pageCount <= 9){
            for($i=0;$i<$this->pageCount;$i++){
                array_push($pageList,$i+1);
            }
        }else{
            if($this->pageNo <= 4){
                for($i=0;$i<5;$i++){
                    array_push($pageList,$i+1);
                }
                array_push($pageList,-1);
                array_push($pageList,$this->pageCount);

            }else if($this->pageNo > $this->pageCount - 4){
                array_push($pageList,1);

                array_push($pageList,-1);
                for($i=5;$i>0;$i--){
                    array_push($pageList,$this->pageCount - $i+1);
                }
            }else if($this->pageNo > 4 && $this->pageNo <= $this->pageCount - 4){
                array_push($pageList,1);
                array_push($pageList,-1);
                array_push($pageList,$this->pageNo -2);
                array_push($pageList,$this->pageNo -1);
                array_push($pageList,$this->pageNo);
                array_push($pageList,$this->pageNo + 1);
                array_push($pageList,$this->pageNo + 2);
                array_push($pageList,-1);
                array_push($pageList,$this->pageCount);
            }
        }
        return $pageList;
    }

    public function getPageSize() {
        $this->generatePageList();
        return $this->pageCount;
    }

    /***
     * 创建分页控件
     * @param
     * @return String
     */
    public function echoPageAsDiv($url="", $s="?"){
        $pageList = $this->generatePageList();
        $pageString = "";
        if(!empty($pageList)){
            if($this->pageCount > 1){
                if($this->hasPrePage){
                    $pageString = $pageString ."<a  href=\"".$url.$s."pageNo=".($this->pageNo-1)."\" type=\"button\" class=\"am-btn am-btn-primary am-round\">上一页</a>";
                }
                foreach ($pageList as $k=>$p){
                    if($this->pageNo == $p){
                        $pageString = $pageString ."<a href=\"".$url.$s."pageNo=".$this->pageNo."\" type=\"button\" class=\"am-btn am-btn-secondary am-radius\">" . $this->pageNo . "</a>";
                        continue;
                    }
                    if($p == -1){
                        $pageString = $pageString ."<a type=\"button\" class=\"am-btn am-btn-default am-radius\" disabled=\"disabled\">...</a>";
                        continue;
                    }
                    $pageString = $pageString ."<a href=\"".$url.$s."pageNo=".$p."\" type=\"button\" class=\"am-btn am-btn-default am-radius\">" . $p . "</a>";
                }
                if($this->hasNextPage){
                    $pageString = $pageString ."<a href=\"".$url.$s."pageNo=".($this->pageNo+1)."\" type=\"button\" class=\"am-btn am-btn-primary am-round\">下一页</a>";
                }
            }
        }
        return $pageString;
    }
}