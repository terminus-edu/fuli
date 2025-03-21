<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PayService
{
    public static function payUrl(array $params): string
    {
        // 拼接参数
        $query = http_build_query(self::buildRequestParam($params));
        // 拼接URL
        $url = env('ANDADAPAY_URL') . '/submit.php?' . $query;
        return $url;
    }
    private static function buildRequestParam($params){
        $params['pid']=env('ANDADAPAY_ID');
        $params['timestamp']=time();
        $mysign = self::getSign($params);
        $params['sign'] = $mysign;
        $params['sign_type'] = 'MD5';
        return $params;
    }

    public static function getSign(array $params): string
    {
        ksort($params);
        reset($params);
		$signstr = '';
		foreach($params as $k => $v){
			if($k != "sign" && $k != "sign_type" && $v!=''){
				$signstr .= $k.'='.$v.'&';
			}
		}
		$signstr = substr($signstr,0,-1);
        $key = env('ANDADAPAY_KEY');
        return md5($signstr . $key);
    }
}
