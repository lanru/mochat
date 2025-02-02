<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * LogFileHandler.php
 *
 * 日志处理
 *
 * User：YM
 * Date：2019/11/29
 * Time：下午6:39
 */

namespace App\Common\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * LogFileHandler
 * 日志处理，存储文件
 * 将info、warning、notic等类型存储一个文件，debug类型存储一个文件，error类型存储一个文件
 * @package App\Common\Handler
 */
class LogFileHandler extends StreamHandler
{

    /**
     * handle
     * 改写父类方法，增加判断日志输出，框架日志
     * @param array $record
     * @return bool
     */
    public function handle(array $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }
        $record = $this->processRecord($record);
        // env才记录DEBUG日志
        if (config('app_env') !== 'env' && $record['level'] == Logger::DEBUG) {
            return false;
        }

        $record['formatted'] = $this->getFormatter()->format($record);
        $this->write($record);
        return false === $this->bubble;
    }

    /**
     * isHandling
     * 重写该方法，作用改变日志的存储文件的方式。
     * 将debug,error，单独存储，其它的按着原来规则
     * User：YM
     * Date：2019/11/29
     * Time：下午6:49
     * @param array $record
     * @return bool
     */
    public function isHandling(array $record): bool
    {
        switch ($record['level']) {
            case Logger::DEBUG:
                return $record['level'] == $this->level;
                break;
            case $record['level'] == Logger::ERROR || $record['level'] == Logger::CRITICAL || $record['level'] == Logger::ALERT || $record['level'] == Logger::EMERGENCY:
                return Logger::ERROR <= $this->level && Logger::EMERGENCY >= $this->level;
                break;
            default:
                return Logger::INFO <= $this->level && Logger::WARNING >= $this->level;
        }
    }
}