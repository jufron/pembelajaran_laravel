<?php

namespace App\Data;


class Database {

    public function queryGet ($tableName): string
    {
        return "select * from $tableName";
    }

    public function queryAll ($tableName): string
    {
        return "select * from $tableName";
    }
}
