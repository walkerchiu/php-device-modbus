<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceModbus: Channel
    |--------------------------------------------------------------------------
    |
    */

    'name'        => '名稱',
    'description' => '描述',
    'serial'      => '編號',
    'identifier'  => '識別符',
    'order'       => '順序',
    'is_enabled'  => '是否啟用',

    'protocol'        => '通訊協議',
    'interface'       => '通訊介面',
    'scan_interval'   => '掃描間隔',
    'polling_timeout' => '輪詢等待時間',
    'retry_interval'  => '重試間隔',

    'baud'     => '鮑率',
    'parity'   => '同為元檢查',
    'stop_bit' => '終止位元',

    'ip'       => 'IP 位址',
    'port'     => '連接埠',

    'list'   => '通道清單',
    'create' => '新增通道',
    'edit'   => '通道修改',

    'form' => [
        'information' => '通道資訊',
            'basicInfo'   => '基本資訊'
    ],

    'delete' => [
        'header' => '刪除通道',
        'body'   => '確定要刪除這條通道嗎？'
    ]
];
