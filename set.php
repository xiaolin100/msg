<?php
header('Content-type:text/html;charset=utf-8');
require_once 'db.php';
$return = array('code' => 1, 'msg' => 'success', 'data' => array());
if($_POST){
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    if($title == '' || $content == ''){
        $return['code'] = 0;
        $return['msg'] = "标题内容不能为空";
        echo json_encode($return);
    }

    $db = db::instance();
    $data = array('title' => $title, 'content' => $content,'create_time' => time());
    $rs = $db->connect()->insert('msg',$data);
    if(!$rs){
        $return['code'] = 0;
        $return['msg'] = "数据保存失败";
    }
    echo json_encode($return);
}
