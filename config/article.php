<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/15
 * Time: 16:12
 */
return
[
    'operation' => [
        'create' => 1,
        'update' => 2,
        'delete' => 3,
        'audit' => 4, //提交审核
        'move' => 5, //转移频道
        'pass' => 6, //审核通过
        'outline' => 7, //下线
    ],
    'state' => [

    ],
    'sortPhotoMaxNum' => 8,

    'ballotType' => [
        '单选',
        '多选',
        'PK',
    ],
];
