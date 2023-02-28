<?php

namespace AlphaDevTeam\AlphaCruds\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ApiCrudCrudGeneratorController extends BaseCrudGeneratorController
{

    private string $model;
    private string $module;
    private bool $force;
    private array $fields;
    private array $types;

    private bool $errors = false;

    protected function getIndexView(): string
    {
        return 'api-crud-generator';
    }

    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module' => 'string|required',
            'model' => 'string|required',
            'force' => 'nullable',
            'fields' => 'array|nullable',
            'types' => 'array|nullable'
        ]);

        $this->model = $validated['model'];
        $this->module = $validated['module'];
        $this->force = isset($validated['force']);
        $this->fields = $validated['fields'] ?? [];
        $this->types = $validated['types'] ?? [];

        $this->createModel();
        $this->createController();
        $this->createRequest();
        $this->createRoutes();
        $this->createResource();

        !$this->errors && $this->showSuccessMessage('CRUD created successfully.');


        return back();
    }

    private function createModel(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-model',
            array_merge([
                'model' => $this->model,
                'fillable' => $this->generateFillableFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createController(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-api-controller',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createRequest(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-request',
            array_merge([
                'model' => $this->model,
                'create-fields' => $this->generateCreateFields(),
                'update-fields' => $this->generateUpdateFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createRoutes(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-api-routes',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createResource(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-resource',
            array_merge([
                'model' => $this->model,
                'fields' => $this->generateResourceFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function handleCommandOutput(int $result): void
    {
        if ($result == 1) {
            $this->showErrorMessage(Artisan::output());
            $this->errors = true;
        }
    }

    private function generateFillableFields(): string
    {
        $result = [];
        foreach ($this->fields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

    private function generateCreateFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= '"'.Str::snake($this->fields[$i])
                .'"=>["'
                .$this->toValidationType($this->types[$i])
                .'","required"],';
        }

        return base64_encode($result.']');
    }

    private function generateUpdateFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= '"'.Str::snake($this->fields[$i])
                .'"=>["'
                .$this->toValidationType($this->types[$i])
                .'"],';
        }

        return base64_encode($result.']');
    }

    private function toValidationType(string $type): string
    {
        return match ($type) {
            'text' => 'string',
            'number' => 'numeric',
        };
    }

    private function generateResourceFields(): string
    {
        $result = [];
        foreach ($this->fields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

}