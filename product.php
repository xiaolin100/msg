<?php
header('Content-type:text/html;charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once 'db.php';
$db = db::instance();
$action = isset($_GET['action']) ? trim($_GET['action']) : '';
$return = array();

if($action == 'getList'){
    $orderDateStart = urldecode($_GET['orderDateStart']);
    $orderDateEnd = urldecode($_GET['orderDateEnd']);
    $sql = "select 
	p.Name as ProductName, sum(detail.Quantity) as Quantity 
from 
	t_product as p 
	inner join t_orderdetail as detail on p.ProdId = detail.Prodid 
    INNER join t_order as o on detail.OrderId = o.OrderID
where 
	o.CreateDate >= '$orderDateStart' and o.CreateDate <= '$orderDateEnd'
	group by ProductName
	";
    $list = $db->connect()->findAll($sql);
    if(empty($list) || (count($list) == 1 && $list[0]['ProductName'] == '')){
        $list = array();
    }
    $return['orderDateEnd'] = $orderDateEnd;
    $return['orderDateStart'] = $orderDateStart;
    $return['productQuantity'] = $list;
    echo json_encode($return);
}



