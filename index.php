<?php

header("Content-Type: Text/Html;Charset=UTF-8");
require "./vendor/autoload.php";

$obj = new think\SignContract();

$params = [
	"contract_num" => 'sign_001',
	"sign_code"    => '5110010258',
	"sign_type"    => 'WRITTEN'
];

// 签约合同
$curl_result = $obj->signView($params, 'http://test.sign.zqsign.com:8081/signView');


// $params = [
// 	"contract_num"  => 'sign_001'
// ];

// 查看合同
//$curl_result = $obj->viewContractImg($params, 'http://test.sign.zqsign.com:8081/getImg');
// 下载合同
//$curl_result = $obj->downloadContractImg($params, 'http://test.sign.zqsign.com:8081/getPdfUrl');


// $params = [
// 	"template_id"   => 'bf4a24f22da44afaba254918febd7ce9',
// 	"contract_num"  => 'sign_001',
// 	"contract_name" => '测试合同',
// 	"json_val"      => "{'jsonVal':[{'测试':'','2':'','3':'','4':'','5':'','6':'','7':'','8':'','9':'','10':'','Signer1':'','Signer2':'','承租方':'张三','12':'','13':'','14':'','15':'','16':'','17':'','出租方':'李四','18':'','19':'','20':''}]}"
// ];

// $curl_result = $obj->createTemplate($params, 'http://test.sign.zqsign.com:8081/pdfTemplate');


// $params = [
// 	"sign_code" => '5110010258',
// 	"realname"  => '朱金奎',
// 	"mobile"    => '15501052244',
// 	"id_card"   => '42112319871109007X'
// ];

// $curl_result = $obj->contractPush($params, 'http://test.sign.zqsign.com:8081/personReg');

var_dump($curl_result);