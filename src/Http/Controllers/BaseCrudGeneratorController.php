<?php

namespace Aqamarine\AlphaCruds\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class BaseCrudGeneratorController extends BaseAlphaCrudsController
{
    abstract protected function getIndexView(): string;

    public function index(Request $request): View|RedirectResponse
    {
        if (!$request->input('table')) {
            return $this->view($this->getIndexView());
        }

        if (!Schema::hasTable($request->input('table'))) {
            $this->showErrorMessage('Table must exists in database.');
            return back();
        }

        $fields = $this->getTableColumns($request->input('table'));
        $model = Str::singular(Str::studly($request->input('table')));

        return $this->view($this->getIndexView(), [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    private function getTableColumns(string $table): array
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
