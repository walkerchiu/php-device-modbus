<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceModbus: Main
    |--------------------------------------------------------------------------
    |
    */

    'name'        => '名稱',
    'description' => '描述',
    'location'    => '位置',
    'channel_id'  => '通道',
    'serial'      => '編號',
    'identifier'  => '識別符',
    'order'       => '順序',
    'is_enabled'  => '是否啟用',

    'slave_id'    => 'Slave ID',

    'list'   => '主裝置清單',
    'create' => '新增主裝置',
    'edit'   => '主裝置修改',

    'form' => [
        'information' => '主裝置資訊',
            'basicInfo'   => '基本資訊'
    ],

    'delete' => [
        'header' => '刪除主裝置',
        'body'   => '確定要刪除這臺主裝置嗎？'
    ]
];
