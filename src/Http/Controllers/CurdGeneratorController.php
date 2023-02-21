<?php

namespace AlphaDevTeam\AlphaCruds\Http\Controllers;

use Artisan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CurdGeneratorController extends BaseAlphaCrudsController
{
    private string $model;
    private string $module;
    private bool $force;
    private array $fields;
    private array $types;
    public function main(): View
    {
        return $this->view('main');
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
        $this->createViews();
        $this->createRoutes();

        $this->showSuccessMessage('CRUD created successfully.');


        return back();
    }

    private function createModel(): void
    {
        Artisan::call(
            'alphacruds:make-model',
            array_merge([
                'model' => $this->model,
                'fillable' => $this->createFillableFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        );
    }

    private function createController(): void
    {
        Artisan::call(
            'alphacruds:make-controller',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        );
    }

    private function createRequest(): void
    {
        Artisan::call(
            'alphacruds:make-request',
            array_merge([
                'model' => $this->model,
                'create-fields' => $this->createCreateFields(),
                'update-fields' => $this->createUpdateFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        );
    }

    private function createViews(): void
    {
        Artisan::call(
            'alphacruds:make-views',
            array_merge([
                'model' => $this->model,
                'fields' => $this->createInputFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        );
    }

    private function createRoutes(): void
    {
        Artisan::call(
            'alphacruds:make-routes',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        );
    }

    private function createFillableFields(): string
    {
        $result = [];
        foreach ($this->fields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

    private function createCreateFields(): string
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

    private function createUpdateFields(): string
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

    private function createInputFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= '"'.Str::snake($this->fields[$i])
                .'"=>["'
                .$this->types[$i]
                .'"],';
        }

        return base64_encode($result.']');
    }
}
