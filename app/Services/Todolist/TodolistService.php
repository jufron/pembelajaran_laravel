<?php

namespace App\Services\Todolist;


interface TodolistService {

    public function saveTodo (string $id, string $todo): void;

    public function getTodo (): array;

    public function deleteTodo (string $id);
}
