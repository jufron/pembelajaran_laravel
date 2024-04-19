<?php


namespace App\Data;


class UserRepository {

    private Database $database;
    private Log $log;

    public function __construct(Database $database, Log $log)
    {
        $this->database = $database;
        $this->log = $log;
    }

    public function get ($user): string
    {
        return $this->database->queryGet($user);
    }

    public function log (): string
    {
        return $this->log->getLog();
    }
}
