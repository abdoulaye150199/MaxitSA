<?php
// AbstractRepository.php
namespace App\Core\Abstract;

abstract class AbstractRepository
{
    abstract public function selectAll(): array;
    abstract public function insert($user): bool;
    abstract public function update($user): bool;
    abstract public function delete($id): bool;
    abstract public function selectById($id);
    abstract public function selectBy(array $filter): array;
    abstract public function selectByCode($code);
}