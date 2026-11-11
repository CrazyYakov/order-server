<?php

namespace Marketplace\Shared\Infrastructure\Adapters;

use Illuminate\Database\DatabaseManager;
use Marketplace\Shared\Core\Ports\TransactionContract;

class Transaction implements TransactionContract
{
    public function __construct(
        private DatabaseManager $db,
    ) {}

    public function begin(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollback(): void
    {
        $this->db->rollBack();
    }
}
