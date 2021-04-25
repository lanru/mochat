<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

$handlers = [
    // info、waring、notice日志等，按日期切分
    [
        'class' =>  Monolog\Handler\RotatingFileHandler::class,
        'constructor' => [
            'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
            'level' => Monolog\Logger::INFO,
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => "%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ]
    ],
    // debug日志
    [
        'class' => App\Common\Handler\LogFileHandler::class,
        'constructor' => [
            'stream' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
            'level' => Monolog\Logger::DEBUG,
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => "%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ]
    ],
    // error日志
    [
        'class' => App\Common\Handler\LogFileHandler::class,
        'constructor' => [
            'stream' => BASE_PATH . '/runtime/logs/hyperf-error.log',
            'level' => Monolog\Logger::ERROR,
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => "%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ]
    ],
];

return [
    'default' => [
        // 配置多个hander，根据每个handel产生日志
        'handlers' => $handlers
    ],
    'SLOW_QUERY_TIME'=>env('SLOW_QUERY_TIME',800),
    // 'default' => [
    //     'handler' => [
    //         'class' => Monolog\Handler\StreamHandler::class,
    //         'constructor' => [
    //             'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
    //             'level' => Monolog\Logger::DEBUG,
    //         ],
    //     ],
    //     'formatter' => [
    //         'class' => Monolog\Formatter\LineFormatter::class,
    //         'constructor' => [
    //             'format' => null,
    //             'dateFormat' => 'Y-m-d H:i:s',
    //             'allowInlineLineBreaks' => true,
    //         ],
    //     ],
    // ],
];
