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
        'addSortLink' => 8, //设置头条
        'delSortLink' => 9, //取消头条
        'addSortPhoto' => 10, //设置幻灯片
        'delSortPhoto' => 11, //取消幻灯片
    ],
    'state' => [

    ],
    'sortPhotoMaxNum' => 8,
    'sortLinkMaxNum' => 100,

    'ballotType' => [
        '单选',
        '多选',
        'PK',
    ],
];
