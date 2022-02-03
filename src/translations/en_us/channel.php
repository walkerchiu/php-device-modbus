<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceModbus: Channel
    |--------------------------------------------------------------------------
    |
    */

    'name'        => 'Name',
    'description' => 'Description',
    'serial'      => 'Serial',
    'identifier'  => 'Identifier',
    'order'       => 'Order',
    'is_enabled'  => 'Is Enabled',

    'protocol'        => 'Protocol',
    'interface'       => 'Interface',
    'scan_interval'   => 'Scan Interval',
    'polling_timeout' => 'Polling Timeout',
    'retry_interval'  => 'Retry Interval',

    'baud'     => 'Baud',
    'parity'   => 'Parity',
    'stop_bit' => 'Stop bit',

    'ip'       => 'IP Address',
    'port'     => 'Port',

    'list'   => 'Channel List',
    'create' => 'Create Channel',
    'edit'   => 'Edit Channel',

    'form' => [
        'information' => 'Information',
            'basicInfo'   => 'Basic info'
    ],

    'delete' => [
        'header' => 'Delete Channel',
        'body'   => 'Are you sure you want to delete this channel?'
    ]
];
