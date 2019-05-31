<?php

namespace App\Services;

Interface CRUDInterface
{	
	public function store(array $data): array;

    public function delete(int $id): array;

    public function search(): array;

    public function verify(string $value): string;

    public function update(array $data): array;

    public function getAll();

    public function get(int $id);
    
}
