<?php

namespace App\Interfaces;

interface TransactionInterface
{
    public function getAll();
    public function getByUserId($userId);
}
