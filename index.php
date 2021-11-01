<?php

require_once('GetFulfillableOrdersClass.php');

$gfo = new GetFulfillableOrders();

$result = $gfo->process('{"1":8,"2":4,"3":5}', 'orders.csv');

if($result['error']){
	echo $result['message'];
}else{
	echo $result['result_text'];
}