<?php

namespace Aqamarine\AlphaCruds\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class TranslatedCrudCrudGeneratorController extends BaseCrudGeneratorController
{
    private string $model;
    private string $module;
    private bool $force;
    private bool $withoutBase;
    private array $fields;
    private array $types;
    private array $translatedFields;
    private array $translatedTypes;

    private bool $errors = false;

    protected function getIndexView(): string
    {
        return 'translated-crud-generator';
    }

    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module' => 'string|required',
            'model' => 'string|required',
            'force' => 'nullable',
            'without_base' => 'nullable',
            'with_migration' => 'nullable',
            'with_intermediate_migration' => 'nullable',
            'fields' => 'array|nullable',
            'types' => 'array|nullable',
            'translated_fields' => 'array|nullable',
            'translated_types' => 'array|nullable',
        ]);

        $this->model = $validated['model'];
        $this->module = $validated['module'];
        $this->force = isset($validated['force']);
        $this->withoutBase = isset($validated['without_base']);
        $this->fields = $validated['fields'] ?? [];
        $this->types = $validated['types'] ?? [];
        $this->translatedFields = $validated['translated_fields'] ?? [];
        $this->translatedTypes = $validated['translated_types'] ?? [];

        $this->createModel();
        $this->createController();
        $this->createRequest();
        $this->createViews();
        $this->createRoutes();
        $this->createFactory();
        $this->createTest();

        $withMigration = isset($validated['with_migration']);
        $withIntermediateMigration = isset($validated['with_intermediate_migration']);

        if ($withMigration) {
            $this->createMigration();
        }

        if ($withIntermediateMigration) {
            $this->createIntermediateMigration();
        }

        !$this->errors && $this->showSuccessMessage('CRUD created successfully.');

        return back();
    }

    private function createModel(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-model',
            array_merge(
                [
                    'model' => $this->model,
                    'fillable' => $this->generateFillableFields(),
                    'module' => $this->module,
                    '-t' => $this->generateTranslatedFields(),
                ],
                $this->force ? ['-f' => true] : [],
                $this->withoutBase ? ['-wb' => true] : [],
            )
        ));
    }

    private function createController(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-controller',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
                '-t' => $this->generateTranslatedFields(),
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

    private function createViews(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-views',
            array_merge([
                'model' => $this->model,
                'fields' => $this->generateInputFields(),
                'module' => $this->module,
                '-t' => $this->generateTranslatedInputFields(),
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createRoutes(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-routes',
            array_merge([
                'model' => $this->model,
                'module' => $this->module,
                '-t' => true,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createMigration(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-migration',
            [
                'model' => $this->model,
                'fields' => $this->generateMigrationFields(),
            ]
        ));
    }
    private function createIntermediateMigration(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-migration',
            [
                'model' => $this->model,
                'fields' => $this->generateIntermediateMigrationFields(),
                '-l' => true,
            ]
        ));
    }

    private function createFactory(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-factory',
            array_merge([
                'model' => $this->model,
                'fields' => $this->generateFactoryFields(),
                'module' => $this->module,
            ], $this->force ? ['-f' => true] : [])
        ));
    }

    private function createTest(): void
    {
        $this->handleCommandOutput(Artisan::call(
            'alphacruds:make-test',
            array_merge([
                'model' => $this->model,
                'fields' => $this->generateTestFields(),
                'correct-fields' => $this->generateCorrectTestFields(),
                'wrong-fields' => $this->generateWrongTestFields(),
                'module' => $this->module,
                '--translations' => $this->generateTestTranslatedFields(),
                '--correct-translations' => $this->generateCorrectTestTranslatedFields(),
                '--wrong-translations' => $this->generateWrongTestTranslatedFields(),
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

    private function generateTranslatedFields(): string
    {
        $result = [];
        foreach ($this->translatedFields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

    private function generateCreateFields(): string
    {
        return base64_encode('[]');
    }

    private function generateUpdateFields(): string
    {
        $result = '[';
        $fields = array_merge($this->fields, $this->translatedFields, ['language_code']);
        $types = array_merge($this->types, $this->translatedTypes, ['exists:languages,code']);
        for ($i = 0; $i < sizeof($fields); $i++) {
            $result.= '"'.Str::snake($fields[$i])
                .'"=>["'
                .$this->toValidationType($types[$i])
                .'"],
                ';
        }

        return base64_encode($result.']');
    }

    private function toValidationType(string $type): string
    {
        return match ($type) {
            'text' => 'string',
            'number' => 'numeric',
            default => $type,
        };
    }

    private function generateInputFields(): string
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

    private function generateTranslatedInputFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->translatedFields); $i++) {
            $result.= '"'.Str::snake($this->translatedFields[$i])
                .'"=>["'
                .$this->translatedTypes[$i]
                .'"],';
        }

        return base64_encode($result.']');
    }

    private function generateMigrationFields(): string
    {
        $result = '';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= Str::snake($this->fields[$i]) .':'.$this->toSchemaType($this->types[$i]).':nullable,';
        }

        return rtrim($result, ',');
    }
    private function generateIntermediateMigrationFields(): string
    {
        $result = '';
        for ($i = 0; $i < sizeof($this->translatedFields); $i++) {
            $result.= Str::snake($this->translatedFields[$i])
                .':'.$this->toSchemaType($this->translatedTypes[$i])
                .':nullable,';
        }

        return rtrim($result, ',');
    }

    private function toSchemaType(string $type): string
    {
        return match ($type) {
            'text' => 'string',
            'number' => 'bigInteger',
        };
    }

    private function generateFactoryFields(): string
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

    private function generateTestFields(): string
    {
        $result = [];
        foreach ($this->fields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

    private function generateCorrectTestFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= '"'.Str::snake($this->fields[$i])
                .'"=>'
                .$this->toCorrectValues($this->types[$i])
                .',';
        }

        return base64_encode($result.']');
    }

    private function generateWrongTestFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->fields); $i++) {
            $result.= '"'.Str::snake($this->fields[$i])
                .'"=>'
                .$this->toWrongValues($this->types[$i])
                .',';
        }

        return base64_encode($result.']');
    }

    private function generateTestTranslatedFields(): string
    {
        $result = [];
        foreach ($this->translatedFields as $field) {
            $result[] = Str::snake($field);
        }

        return base64_encode('["'.implode('","', $result).'"]');
    }

    private function generateCorrectTestTranslatedFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->translatedFields); $i++) {
            $result.= '"'.Str::snake($this->translatedFields[$i])
                .'"=>'
                .$this->toCorrectValues($this->translatedTypes[$i])
                .',';
        }

        return base64_encode($result.']');
    }

    private function generateWrongTestTranslatedFields(): string
    {
        $result = '[';
        for ($i = 0; $i < sizeof($this->translatedFields); $i++) {
            $result.= '"'.Str::snake($this->translatedFields[$i])
                .'"=>'
                .$this->toWrongValues($this->translatedTypes[$i])
                .',';
        }

        return base64_encode($result.']');
    }

    private function toCorrectValues(string $type): string
    {
        return match ($type) {
            'text' => '"string"',
            'number' => 1337,
        };
    }

    private function toWrongValues(string $type): string
    {
        return match ($type) {
            'text' => 1337,
            'number' => '"string"',
        };
    }
}
