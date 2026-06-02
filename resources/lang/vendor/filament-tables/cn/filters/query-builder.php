<?php

return [

    'label' => '查询构建器',

    'form' => [

        'operator' => [
            'label' => '运算符',
        ],

        'or_groups' => [

            'label' => '分组',

            'block' => [
                'label' => '或集 (OR)',
                'or' => '或',
            ],

        ],

        'rules' => [

            'label' => '规则',

            'item' => [
                'and' => '与',
            ],

        ],

    ],

    'no_rules' => '(无规则)',

    'item_separators' => [
        'and' => '与',
        'or' => '或',
    ],

    'operators' => [

        'is_filled' => [

            'label' => [
                'direct' => '已填写',
                'inverse' => '为空',
            ],

            'summary' => [
                'direct' => ':attribute 已填写',
                'inverse' => ':attribute 为空',
            ],

        ],

        'boolean' => [

            'is_true' => [

                'label' => [
                    'direct' => '为真',
                    'inverse' => '为假',
                ],

                'summary' => [
                    'direct' => ':attribute 为真',
                    'inverse' => ':attribute 为假',
                ],

            ],

        ],

        'date' => [

            'is_after' => [

                'label' => [
                    'direct' => '晚于',
                    'inverse' => '不晚于',
                ],

                'summary' => [
                    'direct' => ':attribute 晚于 :date',
                    'inverse' => ':attribute 不晚于 :date',
                ],

            ],

            'is_before' => [

                'label' => [
                    'direct' => '早于',
                    'inverse' => '不早于',
                ],

                'summary' => [
                    'direct' => ':attribute 早于 :date',
                    'inverse' => ':attribute 不早于 :date',
                ],

            ],

            'is_date' => [

                'label' => [
                    'direct' => '是日期',
                    'inverse' => '不是日期',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :date',
                    'inverse' => ':attribute 不是 :date',
                ],

            ],

            'is_month' => [

                'label' => [
                    'direct' => '是月份',
                    'inverse' => '不是月份',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :month',
                    'inverse' => ':attribute 不是 :month',
                ],

            ],

            'is_year' => [

                'label' => [
                    'direct' => '是年份',
                    'inverse' => '不是年份',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :year',
                    'inverse' => ':attribute 不是 :year',
                ],

            ],

            'form' => [

                'date' => [
                    'label' => '日期',
                ],

                'month' => [
                    'label' => '月份',
                ],

                'year' => [
                    'label' => '年份',
                ],

            ],

        ],

        'number' => [

            'equals' => [

                'label' => [
                    'direct' => '等于',
                    'inverse' => '不等于',
                ],

                'summary' => [
                    'direct' => ':attribute 等于 :number',
                    'inverse' => ':attribute 不等于 :number',
                ],

            ],

            'is_max' => [

                'label' => [
                    'direct' => '最大为',
                    'inverse' => '大于',
                ],

                'summary' => [
                    'direct' => ':attribute 最大为 :number',
                    'inverse' => ':attribute 大于 :number',
                ],

            ],

            'is_min' => [

                'label' => [
                    'direct' => '最小为',
                    'inverse' => '小于',
                ],

                'summary' => [
                    'direct' => ':attribute 最小为 :number',
                    'inverse' => ':attribute 小于 :number',
                ],

            ],

            'aggregates' => [

                'average' => [
                    'label' => '平均值',
                    'summary' => ':attribute 的平均值',
                ],

                'max' => [
                    'label' => '最大值',
                    'summary' => ':attribute 的最大值',
                ],

                'min' => [
                    'label' => '最小值',
                    'summary' => ':attribute 的最小值',
                ],

                'sum' => [
                    'label' => '总和',
                    'summary' => ':attribute 的总和',
                ],

            ],

            'form' => [

                'aggregate' => [
                    'label' => '聚合',
                ],

                'number' => [
                    'label' => '数字',
                ],

            ],

        ],

        'relationship' => [

            'equals' => [

                'label' => [
                    'direct' => '有',
                    'inverse' => '没有',
                ],

                'summary' => [
                    'direct' => '有 :count 个 :relationship',
                    'inverse' => '没有 :count 个 :relationship',
                ],

            ],

            'has_max' => [

                'label' => [
                    'direct' => '最多有',
                    'inverse' => '多于',
                ],

                'summary' => [
                    'direct' => '最多有 :count 个 :relationship',
                    'inverse' => '多于 :count 个 :relationship',
                ],

            ],

            'has_min' => [

                'label' => [
                    'direct' => '最少有',
                    'inverse' => '少于',
                ],

                'summary' => [
                    'direct' => '最少有 :count 个 :relationship',
                    'inverse' => '少于 :count 个 :relationship',
                ],

            ],

            'is_empty' => [

                'label' => [
                    'direct' => '为空',
                    'inverse' => '不为空',
                ],

                'summary' => [
                    'direct' => ':relationship 为空',
                    'inverse' => ':relationship 不为空',
                ],

            ],

            'is_related_to' => [

                'label' => [

                    'single' => [
                        'direct' => '是',
                        'inverse' => '不是',
                    ],

                    'multiple' => [
                        'direct' => '包含',
                        'inverse' => '不包含',
                    ],

                ],

                'summary' => [

                    'single' => [
                        'direct' => ':relationship 是 :values',
                        'inverse' => ':relationship 不是 :values',
                    ],

                    'multiple' => [
                        'direct' => ':relationship 包含 :values',
                        'inverse' => ':relationship 不包含 :values',
                    ],

                    'values_glue' => [
                        0 => '、',
                        'final' => '或',
                    ],

                ],

                'form' => [

                    'value' => [
                        'label' => '值',
                    ],

                    'values' => [
                        'label' => '值',
                    ],

                ],

            ],

            'form' => [

                'count' => [
                    'label' => '数量',
                ],

            ],

        ],

        'select' => [

            'is' => [

                'label' => [
                    'direct' => '是',
                    'inverse' => '不是',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :values',
                    'inverse' => ':attribute 不是 :values',
                    'values_glue' => [
                        0 => '、',
                        'final' => '或',
                    ],
                ],

                'form' => [

                    'value' => [
                        'label' => '值',
                    ],

                    'values' => [
                        'label' => '值',
                    ],

                ],

            ],

        ],

        'text' => [

            'contains' => [

                'label' => [
                    'direct' => '包含',
                    'inverse' => '不包含',
                ],

                'summary' => [
                    'direct' => ':attribute 包含 :text',
                    'inverse' => ':attribute 不包含 :text',
                ],

            ],

            'ends_with' => [

                'label' => [
                    'direct' => '结尾是',
                    'inverse' => '结尾不是',
                ],

                'summary' => [
                    'direct' => ':attribute 以 :text 结尾',
                    'inverse' => ':attribute 不以 :text 结尾',
                ],

            ],

            'equals' => [

                'label' => [
                    'direct' => '等于',
                    'inverse' => '不等于',
                ],

                'summary' => [
                    'direct' => ':attribute 等于 :text',
                    'inverse' => ':attribute 不等于 :text',
                ],

            ],

            'starts_with' => [

                'label' => [
                    'direct' => '开头是',
                    'inverse' => '开头不是',
                ],

                'summary' => [
                    'direct' => ':attribute 以 :text 开头',
                    'inverse' => ':attribute 不以 :text 开头',
                ],

            ],

            'form' => [

                'text' => [
                    'label' => '文本',
                ],

            ],

        ],

    ],

    'actions' => [

        'add_rule' => [
            'label' => '添加规则',
        ],

        'add_rule_group' => [
            'label' => '添加规则组',
        ],

    ],

];