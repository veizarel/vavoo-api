<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function getVavoo() {
    $ua = "VAVOO/2.6";
    $ch = curl_init("https://vavoo.to/api/v2/auth/register");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $auth = json_decode(curl_exec($ch), true);
    $token = $auth['token'] ?? "";

    $url = "https://vavoo.to/live2/index" . ($token ? "?token=" . $token : "");
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = json_decode(curl_exec($ch), true);
    
    $out = [];
    if (is_array($res)) {
        foreach ($res as $c) {
            if (isset($c['group']) && stripos($c['group'], 'Bulgaria') !== false) {
                $c['url'] = $token ? $c['url'] . "?token=" . $token : $c['url'];
                $out[] = $c;
            }
        }
    }
    return json_encode($out);
}
echo getVavoo();
