<?php
namespace app\helpers;

use yii\helpers\ArrayHelper;

class Map {
    /**
     * @param $address
     * @return mixed
     * 腾讯地图 地理位置逆解析
     */
    public static function getLatitudeAndLongitude($address)
    {
        $qqMapkey = 'HCLBZ-4VQRF-UFGJS-JDIZ6-G66U5-JVFOX';
        $url = "http://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key={$qqMapkey}";
        $opts = [
            'http' => [
                'method' => "GET",
                'timeout' => 4, //设置超时
            ]
        ];
        $context = stream_context_create($opts);
        $locationInfo = file_get_contents($url, false, $context);
        $location = json_decode($locationInfo);
        $location = ArrayHelper::toArray($location);
        $locat = [];
        $locat['lng'] = ArrayHelper::getValue($location, 'result.location.lng');
        $locat['lat'] = ArrayHelper::getValue($location, 'result.location.lat');
        return $locat;
    }
}