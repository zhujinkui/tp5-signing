<?php
// 类库名称：在线合同签约
// +----------------------------------------------------------------------
// | PHP version 5.6+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.myzy.com.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 阶级娃儿 <262877348@qq.com> 群：304104682
// +----------------------------------------------------------------------
namespace think;

use think\facade\Config;

class SignContract
{
	/**
	 * @var array 配置信息
	 */
	protected $config = [
		// 私钥
		'private_key' => 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBANIwqMKRiZMTMerWYJsp54AoMUcIbgZsdB4FjtGAzabh/NYH9ptNgNBfBo78yShPCP5c0wB0MVqg3wv5ExQRcCA5uj1ajO+FuHy5ESxmDDftxOzQlpHlMdvxCLZwJjy0+Il2AsZcbcSy3HMDN8HGhOG01A9rllbx6JnyC8hFdd+7AgMBAAECgYBztZHRuqjPrGt4ahe4k3L73CR0hDF9m8q4lDqxHoUX76RudufNSvc0vnsvz/01EX1T+em2gECDMbhYMP/NtmPQegoVIsojSGSSF8Q+q7JOCQlDi9JXiRMkoj+uSMeSqa4EbqOdoFAj+F8BlzYJCUCdfdcJRR4Zb8seFNlpUfDToQJBAPMGQt8dWfFGDGlo9Tnif5GIlz09Of7odn/NOyFb6c+fca0ufrg816GWGgLBl0qnj8bO/93P+EY0MWsVF8RytRkCQQDdaZtWGm9YImGT+PKdKapQvt0C5RAfi2OAnRndqCs8bA1K1kPII8hg/t2QFPshx48pqayJ7ve5/dmeig1y0eHzAkAKWnHu32k9hiZxNy97T9LveEo5KaqW2YBy4WNrgGbtmXVWU2zCnJTzJVnmVCkF3S2a4qaz5HBHTWHtlfB1Rg3BAkEA0cpr3fTkRX0mOf/rWhENiL6gSUrjsQ/w8v9ob8cVWIYFPkCxLuUAyy8Snp/SqFofA1n62yMrZPbriTXDsmS+EwJBAOFhYJS/x04TKX3H4iGDXLKLTSaQWoDyHBIZG61HSLVI8UTTre/Efc8jrs6GnYXkXAA0KeAcUQDxdeF0YRFhc2g=',
		// 公钥
		'public_key'  => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDSMKjCkYmTEzHq1mCbKeeAKDFHCG4GbHQeBY7RgM2m4fzWB/abTYDQXwaO/MkoTwj+XNMAdDFaoN8L+RMUEXAgObo9Wozvhbh8uREsZgw37cTs0JaR5THb8Qi2cCY8tPiJdgLGXG3EstxzAzfBxoThtNQPa5ZW8eiZ8gvIRXXfuwIDAQAB',
		// 唯一标识
		'zqid'        => 'ZQABA206A379B342FB987B8DCCBA679549',
		// 异常回调地址
		'notify_url'  => 'http://www.temp.com/notify_url.php',
		// 同步回调地址
		'return_url'  => 'http://www.temp.com/return_url.php'
	];

	/**
	 * 构造函数
	 * @access protected
	 */
	public function __construct()
	{
	    // 判断是否有设置配置项.此配置项为数组，做一个兼容
	    if (Config::has('contract')) {
	        $this->configs = Config::get('contract');
	    }
	}

	/**
	 * [contractPush 签约记录]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function contractPush($params = [], $api_url = '')
	{
		// 推送特定参数
		$param_data = [
			"user_code"  => $params['sign_code'],
			"name"       => $params['realname'],
			"zqid"       => $this->config['zqid'],
			"mobile"     => $params['mobile'],
			"id_card_no" => $params['id_card']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [createTemplate 创建合同模板]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function createTemplate($params = [], $api_url = '')
	{
		// 推送特定参数
		$param_data = [
			"zqid"         => $this->config['zqid'],
			't_no'         => $params['template_id'],
			'no'           => $params['contract_num'],
			'name'         => $params['contract_name'],
			'contract_val' => $params['json_val']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [viewContractImg 查看合同]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function viewContractImg($params = [], $api_url = '')
	{
		// 推送特定参数
		$param_data = [
			"zqid" => $this->config['zqid'],
			'no'   => $params['contract_num']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [downloadContractImg 下载合同]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function downloadContractImg($params = [], $api_url = '')
	{
		// 推送特定参数
		$param_data = [
			"zqid" => $this->config['zqid'],
			'no'   => $params['contract_num']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [signView 合同签约]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function signView($params = [], $api_url = '', $sign_type = 'WRITTEN')
	{
		// 推送特定参数
		$param_data = [
			"zqid"       => $this->config['zqid'],
			"no"         => $params['contract_num'],
			"user_code"  => $params['sign_code'],
			"sign_type"  => $sign_type,
			"notify_url" => $this->config['notify_url'],
			"return_url" => $this->config['return_url']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [signView 销毁合同]
	 * @param  array  $params  [签约参数]
	 * @param  string $api_url [API接口]
	 */
	public function unsetContract($params = [], $api_url = '')
	{
		// 推送特定参数
		$param_data = [
			"zqid" => $this->config['zqid'],
			'no'   => $params['contract_num']
		];

		// 获取签名
		$param_data['sign_val'] =  $this->contractSign($param_data);
		// 得到结果
		$content            = curlPost($api_url,$param_data);

		return $content;
	}

	/**
	 * [contractSign 签名]
	 * @param  array  $param_data [签名数据]
	 */
	protected function contractSign($param_data = [])
	{
		// 签字sign规则
		return __sign($param_data, $this->config['private_key']);
	}
}