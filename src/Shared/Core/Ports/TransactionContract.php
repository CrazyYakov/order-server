<?php

namespace Marketplace\Shared\Core\Ports;

interface TransactionContract
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
