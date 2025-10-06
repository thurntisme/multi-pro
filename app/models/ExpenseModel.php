<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class ExpenseModel extends Model
{
    protected function getTable(): string
    {
        return 'expenses';
    }
}
