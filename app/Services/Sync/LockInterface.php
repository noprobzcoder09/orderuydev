<?php

namespace App\Services\Sync;

Interface LockInterface
{       
    public function check(string $field);
}
