<?php

namespace AlphaDevTeam\AlphaCruds\Http\Controllers;

use Illuminate\Support\Facades\DB;

trait TableData
{
    protected function getTableColumns(string $table): array
    {
        return array_diff(DB::getSchemaBuilder()->getColumnListing($table), $this->getFieldsToDelete());
    }

    private function getFieldsToDelete(): array
    {
        return [
            'id',
            'updated_at',
            'created_at',
        ];
    }
}
