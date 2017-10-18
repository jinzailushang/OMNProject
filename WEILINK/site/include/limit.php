<?php

/**
 * 载入权限
 * @copyright 2015-06-02, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

return array(
    array('name' => '商品归并', 'child' => array(
            array(
                'name' => '归并信息',
                'op' => null,
                'act' => 'merger',
                'sub' => array(
                    'merger.index' => '归并信息',
                    'merger.edit' => '编辑',
                    'merger.export' => '导出',
                ),
            ),
        )
    ),
    array('name' => '仓储连网 ', 'child' => array(
            array(
                'name' => '计划入库单',
                'op' => null,
                'act' => 'plan_storage',
                'sub' => array(
                    'plan_storage.index' => '入库单列表',
                    'plan_storage.detail' => '详细',
                    'plan_storage.export' => '导出',
                ),
            ),
            array(
                'name' => '仓储核放单',
                'op' => null,
                'act' => 'actual_storage',
                'sub' => array(
                    'actual_storage.index' => '核放单列表',
                    'actual_storage.get_data' => '详细',
                    'actual_storage.export' => '导出',
                ),
            ),
            array(
                'name' => '出入库记录',
                'op' => null,
                'act' => 'inout_goods',
                'sub' => array(
                    'inout_goods.index' => '出入库记录',
                    'inout_goods.detail' => '详细',
                    'inout_goods.export' => '导出',
                ),
            ),
            array(
                'name' => '推送管理',
                'op' => null,
                'act' => 'push',
                'sub' => array(
                    'push.index' => '推送列表',
                    'push.get_data' => '详细',
                ),
            ),
            array(
                'name' => '日志管理',
                'op' => null,
                'act' => 'storage_customs_log',
                'sub' => array(
                    'storage_customs_log.index' => '日志列表',
                    'storage_customs_log.get_data' => '详细',
                ),
            ),
            array(
                'name' => '货站管理',
                'op' => null,
                'act' => 'house',
                'sub' => array(
                    'house.index' => '货站列表',
                    'house.add' => '添加',
                    'house.edit' => '编辑',
                    'house.delete' => '删除',
                    
                ),
            ),
        )
    ),
    array('name' => '接口管理 ', 'child' => array(
           
            array(
                'name' => '接收日志',
                'op' => null,
                'act' => 'api_log',
                'sub' => array(
                    'api_log.index' => '日志列表',
                    'api_log.get_data' => '详细',
                ),
            ),
            array(
                'name' => '推送日志',
                'op' => null,
                'act' => 'order_push_log',
                'sub' => array(
                    'order_push_log.index' => '推送日志',
                    'order_push_log.get_data' => '详细',
                ),
            ),
            array(
                'name' => '推送管理',
                'op' => null,
                'act' => 'order_push',
                'sub' => array(
                    'order_push.index' => '推送列表',
                    'order_push.get_data' => '详细',
                    
                ),
            ),
        )
    ),
    array('name' => '申报管理', 'child' => array(
            array(
                'name' => '个人申报',
                'op' => null,
                'act' => 'pgd',
                'sub' => array(
                    'pgd.index' => '个人申报',
                    'pgd.detail' => '申报详情',
                    'pgd.view' => '申报操作',
                    'pgd.batchdec' => '批量申报',
                    'pgd.export' => '申报导出',
                    'pgd.orderlog' => '订单日志',
                ),
            ),
            array(
                'name' => '手工放行',
                'op' => null,
                'act' => 'pgd',
                'sub' => array(
                    'pgd.import' => '手工放行',
                ),
            ),
        )
    ),
    array('name' => '系统设置', 'child' => array(
            array(
                'name' => '基本设置',
                'op' => null,
                'act' => 'setting',
                'sub' => array(
                    'setting.system' => '基本设置',
                )
            ),
            array(
                'name' => '权限管理',
                'op' => null,
                'act' => 'admin',
                'sub' => array(
                    'admin.admin' => '管理员',
                    'admin.admin_add' => '添加管理员',
                    'admin.admin_edit' => '修改管理员',
                    'admin.admin_del' => '删除管理员',
                    'admin.gadmin' => '权限组',
                    'admin.gadmin_add' => '添加权限组',
                    'admin.gadmin_set' => '编辑权限组',
                    'admin.gadmin_del' => '删除权限组'
                )
            ),
            array(
                'name' => '单位代码',
                'op' => null,
                'act' => 'measure',
                'sub' => array(
                    'measure.index' => '单位代码',
                    'measure.add' => '添加单位',
                    'measure.edit' => '修改单位',
                    'measure.del' => '删除单位',
                )
            ),
//            array(
//                'name' => '国别代码',
//                'op' => null,
//                'act' => 'country',
//                'sub' => array(
//                    'country.index' => '国别代码',
//                    'country.add' => '添加国别',
//                    'country.edit' => '修改国别',
//                    'country.del' => '删除国别',
//                )
//            ),
//            array(
//                'name' => '币制代码',
//                'op' => null,
//                'act' => 'currency',
//                'sub' => array(
//                    'currency.index' => '币制代码',
//                    'currency.add' => '添加币制',
//                    'currency.edit' => '修改币制',
//                    'currency.del' => '删除币制',
//                )
//            ),
//            array(
//                'name' => '港口代码',
//                'op' => null,
//                'act' => 'openport',
//                'sub' => array(
//                    'openport.index' => '港口代码',
//                )
//            ),
            array(
                'name' => '清理缓存',
                'op' => null,
                'act' => 'cache',
                'sub' => array(
                    'cache.clear' => '清理缓存',
                )
            ),
        )
    ),
);
?>
