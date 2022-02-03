<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceModbus: Setting
    |--------------------------------------------------------------------------
    |
    */

    'name'        => '名稱',
    'description' => '描述',
    'location'    => '位置',
    'main_id'     => '主裝置 ID',
    'serial'      => '編號',
    'identifier'  => '識別符',
    'order'       => '順序',
    'is_enabled'  => '是否啟用',

    'typology'           => 'Typology',
    'function_code'      => 'Function Code',
    'format'             => '資料格式',
    'data_start_address' => '起始位址',
    'data_count'         => '資料數量',
    'scale_ratio'        => '線性轉換倍率',

    'list'   => '設定清單',
    'create' => '新增設定',
    'edit'   => '設定修改',

    'form' => [
        'information' => '設定資訊',
            'basicInfo'   => '基本資訊'
    ],

    'delete' => [
        'header' => '刪除設定',
        'body'   => '確定要刪除這組設定嗎？'
    ]
];
