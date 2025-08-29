<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->makeModel();
        $this->boot();
    }

    abstract public function model(): string;

    protected function boot(): void
    {
        // 可以在子类中重写用于初始化
    }

    protected function makeModel(): void
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        $this->model = $model;
    }

    public function resetModel(): void
    {
        $this->makeModel();
    }

}