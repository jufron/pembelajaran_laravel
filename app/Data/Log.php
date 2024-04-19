<?php


namespace App\Data;


class Log {

    private $log;

    public function __construct($log) {
        $this->log = $log;
    }

    public function getLog (): string
    {
        return $this->log;
    }
}
