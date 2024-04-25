<?php


namespace App\Contracts;


interface TodolistService {

    public function saveTodo (string $todo): void;

    public function getTodo (): array;

    public function removeTodo (string $todoID);

    public function editTodolist (string $id) : array | null;

    public function updateTodolist (string $dataTodo, $id);
}
