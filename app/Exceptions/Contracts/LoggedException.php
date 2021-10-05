<?php


namespace App\Exceptions\Contracts;


interface LoggedException
{
    public const LOG_CRITICAL = 'critical';
    public const LOG_ERROR = 'error';
    public const LOG_DEBUG = 'debug';
    public const LOG_ALERT = 'alert';
    public const LOG_INFO = 'info';
    public const LOG_EMERGENCY = 'emergency';
    public const LOG_NOTICE = 'notice';
    public const LOG_WARNING = 'warning';

    public function getLogLevel(): string;
}
