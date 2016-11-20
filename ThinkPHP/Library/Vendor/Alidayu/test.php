<?php

include "TopSdk.php";
date_default_timezone_set('Asia/Shanghai');


$c = new TopClient;
$c ->appkey = '23535693' ;
$c ->secretKey = '54836e841d9b9752e54f5985ab9f491a' ;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req ->setExtend( "" );
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( "注册测试签名" );
$req ->setSmsParam( "{product:'北京仙人跳文化传播有限公司',code:'6666'}" );
$req ->setRecNum( "15680871314" );
$req ->setSmsTemplateCode( "SMS_11480818" );
$resp = $c ->execute( $req );
var_dump($resp);
exit;

$httpdns            = new HttpdnsGetRequest;
$client             = new ClusterTopClient("4272", "0ebbcccfee18d7ad1aebc5b135ffa906");
$client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
var_dump($client->execute($httpdns, "6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));
?>