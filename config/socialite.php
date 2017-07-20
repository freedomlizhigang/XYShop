<?php

return [

    // 微信配置
    'wechat' => [
        'client_id'     => 'wx0845c4b247acd58c',
        'client_secret' => 'c68b97056e850850292c810fe375976c',
        // 这里基本没用，都是实时修改的
        'redirect'      => env('APP_URL', 'http://xyshop.xi-yi.ren').'/oauth/wx/callback', 
    ],

];
