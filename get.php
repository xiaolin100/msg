<?php
header('Content-type:text/html;charset=utf-8');
require_once 'db.php';
$db = db::instance();
$return = array('code' => 1, 'msg' => 'success', 'data' => array());
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 2;  //条数限制
$direction = isset($_GET['direction']) && $_GET['direction'] == 'old' ? 'old' : 'new'; //方向 new获取最新，old获取以前数据

if($direction == 'new'){
    $maxId = file_get_contents('maxId');
    if($maxId<=0){
        $maxId = 0;
    }
    $sql = "select id,title,content from msg where id > ".$maxId." order by create_time desc limit 0, ".$limit;
} else {
    $minId = file_get_contents('minId');
    if($minId<=0){
        $minId = 0;
    }
    $sql = "select id,title,content from msg where id < ".$minId." order by create_time desc limit 0, ".$limit;
}

$list = $db->connect()->findAll($sql);
if(empty($list)){
    $return['msg'] = '暂无数据';
} else {
    $arr = array();
    foreach ($list as $item){
        $arr[] = $item['id'];
    }
    //保存最大最小id，下次获取数据用
    $maxId = max($arr);
    file_put_contents('maxId',$maxId);
    $minId = min($arr);
    file_put_contents('minId',$minId);
    $return['data'] = $list;
}
echo json_encode($return);
