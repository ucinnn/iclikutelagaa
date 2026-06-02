<?php

return [

    'column_toggle' => [

        'heading' => '列',

    ],

    'columns' => [

        'actions' => [
            'label' => '操作|操作',
        ],

        'text' => [

            'actions' => [
                'collapse_list' => '显示 :count 条更少',
                'expand_list' => '显示 :count 条更多',
            ],

            'more_list_items' => '还有 :count 条',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => '选择/取消选择本页所有项以进行批量操作。',
        ],

        'bulk_select_record' => [
            'label' => '选择/取消选择项 :key 以进行批量操作。',
        ],

        'bulk_select_group' => [
            'label' => '选择/取消选择分组 :title 以进行批量操作。',
        ],

        'search' => [
            'label' => '搜索',
            'placeholder' => '搜索',
            'indicator' => '搜索中',
        ],

    ],

    'summary' => [

        'heading' => '摘要',

        'subheadings' => [
            'all' => '所有 :label',
            'group' => ':group 摘要',
            'page' => '本页',
        ],

        'summarizers' => [

            'average' => [
                'label' => '平均值',
            ],

            'count' => [
                'label' => '计数',
            ],

            'sum' => [
                'label' => '总和',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => '完成记录排序',
        ],

        'enable_reordering' => [
            'label' => '重新排序记录',
        ],

        'filter' => [
            'label' => '筛选',
        ],

        'group' => [
            'label' => '分组',
        ],

        'open_bulk_actions' => [
            'label' => '批量操作',
        ],

        'toggle_columns' => [
            'label' => '切换列显示',
        ],

    ],

    'empty' => [

        'heading' => '无 :model',

        'description' => '创建一个 :model 开始使用。',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => '应用筛选',
            ],

            'remove' => [
                'label' => '移除筛选',
            ],

            'remove_all' => [
                'label' => '移除所有筛选',
                'tooltip' => '移除所有筛选',
            ],

            'reset' => [
                'label' => '重置',
            ],

        ],

        'heading' => '筛选',

        'indicator' => '已启用筛选',

        'multi_select' => [
            'placeholder' => '全部',
        ],

        'select' => [
            'placeholder' => '全部',
        ],

        'trashed' => [

            'label' => '已删除记录',

            'only_trashed' => '仅显示已删除记录',

            'with_trashed' => '包含已删除记录',

            'without_trashed' => '不包含已删除记录',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => '按组分组',
                'placeholder' => '按组分组',
            ],

            'direction' => [

                'label' => '分组方向',
                'options' => [
                    'asc' => '升序',
                    'desc' => '降序',
                ],

            ],

        ],

    ],

    'reorder_indicator' => '拖放记录以重新排序。',

    'selection_indicator' => [

        'selected_count' => '已选择 1 条记录|已选择 :count 条记录',

        'actions' => [

            'select_all' => [
                'label' => '全选 :count',
            ],

            'deselect_all' => [
                'label' => '取消全选',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => '排序依据',
            ],

            'direction' => [

                'label' => '排序方向',

                'options' => [
                    'asc' => '升序',
                    'desc' => '降序',
                ],

            ],

        ],

    ],

];
