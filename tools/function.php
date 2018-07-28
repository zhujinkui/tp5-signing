<?php
/**
 * curl post
 */
function curlPost($url, $postData = array() )
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	//设置返回值
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	//得到结果
	$result = curl_exec($ch);
	curl_close($ch); //关闭curl
	return $result;
}

/**
*将参数拼接成自动提交表单
*/
function submitFormStr($query = array(),$url){
	$content = "<form id ='zqwssubmit' name='zqwssubmit' action='" . $url . "' method='post'>";
	foreach($query as $key => $value){
		$content = $content . "<input type ='hidden' name='" . $key . "' value='" . $value . "'/>" ;
	}

	$content = $content . "<input type='submit' value='确认' style='display:none;'></form>" ;
	$content = $content . "<script>document.forms['zqwssubmit'].submit();</script>" ;
	return $content;
}

//参数排序
/**
 * 数组排序
 * @param query  需要排序的数组
 * @return  排序后拼接成arg0=1&arg1=2&..........
 */
 function buildQuery( $query ){
	if ( !$query ) {
		return null;
	}
	//将要 参数 排序
	ksort( $query );
	//重新组装参数
	$params = array();
	foreach($query as $key => $value){
		$params[] = $key .'='. $value ;
	}
	$data = implode('&', $params);
	return $data;
}


/**
 * 私钥签名签名
 * @param content  代签名字符串
 * @param privateKey
 * @return  签名后的数据
 */
 function __sign($query = array(),$privateKey){
    if( ! is_array( $query ) ){
        return null;
    }
    //排序参数，
    $data =buildQuery( $query );
    // 私钥密码
    $passphrase = '';
    $key_width = 64;
    $p_key = array();
    //如果私钥是 1行
    if( ! stripos( $privateKey, "\n" )  ){
        $i = 0;
        while( $key_str = substr( $privateKey , $i * $key_width , $key_width) ){
            $p_key[] = $key_str;
            $i ++ ;
        }
    }else{
        //echo '一行？';
    }

	//将一行代码
    $privateKey = "-----BEGIN PRIVATE KEY-----\n" . implode("\n", $p_key) ;
    $privateKey = $privateKey ."\n-----END PRIVATE KEY-----";
    $pkeyid = openssl_get_privatekey($privateKey);
    openssl_sign($data, $sign, $pkeyid);
    openssl_free_key($pkeyid);
    $sign = base64_encode($sign);
    return $sign;
}

function __verify($query = array(),$publicKey,$signVal){
    if( ! is_array( $query ) ){
        return null;
    }
    //排序参数，
    $data =buildQuery( $query );
    // 私钥密码
    $passphrase = '';
    $key_width = 64;
    $p_key = array();
    //如果私钥是 1行
    if( ! stripos( $publicKey, "\n" )  ){
        $i = 0;
        while( $key_str = substr( $publicKey , $i * $key_width , $key_width) ){
            $p_key[] = $key_str;
            $i ++ ;
        }
    }else{
        //echo '一行？';
    }
	$sign = base64_decode($signVal);
	//将一行代码
    $publicKey = "-----BEGIN PUBLIC KEY-----\n" . implode("\n", $p_key) ;
    $publicKey = $publicKey ."\n-----END PUBLIC KEY-----";

    $pkeyid = openssl_get_publickey($publicKey);
	$verify = openssl_verify($data, $sign, $pkeyid);
    openssl_free_key($pkeyid);

	if($verify == 1){
		return true;
	}else{
		 return false;
	}
}

?>