<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * 查询详情操作
     * @param int $id
     * @return array
     */
    public function find(int $id): array;

    /**
     * 列表查询操作
     * @param array $params
     * @return array
     */
    public function query(array $params): array;

    /**
     * 新增操作
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;

    /**
     * 更新操作
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * 删除操作
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}