<?php

namespace App\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{   

    private $name;
    private $path;
    private $log;

    private $events = [
        'DEBUG' => 'DEBUG',
        'INFO' => 'INFO',
        'NOTICE' => 'NOTICE',
        'WARNING' => 'WARNING',
        'ERROR' => 'ERROR',
        'CRITICAL' => 'CRITICAL',
        'ALERT' => 'ALERT',
        'EMERGENCY' => 'EMERGENCY',
    ];

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->log = new Logger($this->name);
    }

    public function __call($method, $args)
    {
        $method = strtolower($method);
        if (!method_exists($this, $method)) {
            throw new \Exception("Logger {$method} does not exist.", 1);
        }

        $this->log->pushHandler(new StreamHandler(storage_path($this->path)), $this->$method());
        if (count($args) == 1) {
            if (is_array($args[0])) {
                $this->log->$method(null,$args[0]);    
            } else {
                $this->log->$method($args[0]);    
            }
        }
        elseif (count($args) == 2)  {
            $this->log->$method($args[0], $args[1]);
        }
    }

    private function info() {
        return Logger::INFO;
    }

    private function debug() {
        return Logger::DEBUG;
    }

    private function notice() {
        return Logger::NOTICE;
    }

    private function warning() {
        return Logger::WARNING;
    }

    private function error() {
        return Logger::ERROR;
    }

    private function critical() {
        return Logger::CRITICAL;
    }

    private function alert() {
        return Logger::ALERT;
    }

    private function emergency() {
        return Logger::EMERGENCY;
    }
}
