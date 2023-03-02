@extends('alphacruds::layout.layout')

@section('title')
    API Crud Generator
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">API Crud Generator</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('alphacruds::components._get-table-fields', ['route' =>route('alphacruds.api-crud-generator')])
            <form action="{{route('alphacruds.api-crud-generator.create')}}" method="post" id="crudForm">
                @csrf

                @include('alphacruds::components.input_group', [
                   'type' => 'text',
                   'required' => true,
                   'label' => 'Module',
                   'name' => 'module',
                   'placeholder' => 'Module',
               ])

                @include('alphacruds::components.input_group', [
                   'type' => 'text',
                   'required' => true,
                   'label' => 'Model',
                   'name' => 'model',
                   'placeholder' => 'Model',
                   'defaultValue' => isset($model) ? $model : '',
                ])

                <div class="form-check">
                    <label class="form-check-label d-flex align-items-center">
                        Force
                        <input class="form-check-input" type="checkbox" name="force">
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label d-flex align-items-center">
                        With Migration
                        <input class="form-check-input" type="checkbox" name="with_migration">
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label d-flex align-items-center">
                        Without Base Model
                        <input class="form-check-input" type="checkbox" name="without_base">
                    </label>
                </div>

                <button class="btn btn-primary btn-sm mt-3 mb-3" id="addField" type="button">
                    <em class="fas fa-plus"></em>
                    Add Field
                </button>

                <button type="submit" class="btn btn-primary btn-sm">
                    <em class="fas fa-upload"></em>
                    Create
                </button>

                <a class="btn btn-danger btn-sm" href="{{route('alphacruds.api-crud-generator')}}">
                    <em class="fas fa-trash"></em>
                    Clear
                </a>

                @isset($fields)
                    @foreach($fields as $field)
                        <label class="d-flex">
                            <input
                                type="text"
                                required
                                placeholder="Field Name"
                                name="fields[]"
                                style="max-width: 200px"
                                value="{{$field}}"
                                class="form-control"
                            >
                            <select
                                type="text"
                                required
                                name="types[]"
                                class="ml-1 form-control"
                                style="max-width: 200px;"
                            >
                                <option value="text">
                                    Text
                                </option>
                                <option value="number">
                                    Number
                                </option>
                            </select>
                            <button
                                class="btn btn-danger btn-sm ml-1"
                                onclick="this.parentElement.remove()"
                            >
                                <em class="fas fa-trash"></em> Delete
                            </button>
                        </label>
                    @endforeach
                @endisset

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript" defer>

        document.getElementById('addField').onclick = () => {
            const container = document.getElementById('crudForm');
            const label = document.createElement('label');
            label.classList.add('d-flex');

            label.appendChild(createFieldNameInput());
            label.appendChild(createTypeSelector());
            label.appendChild(createDeleteButton());

            container.appendChild(label);
        };

        function createFieldNameInput() {
            const fieldNameInput = document.createElement('input');
            fieldNameInput.type = 'text';
            fieldNameInput.required = true;
            fieldNameInput.placeholder = 'Field Name';
            fieldNameInput.name = 'fields[]';
            fieldNameInput.classList.add('form-control');
            fieldNameInput.style.maxWidth = '200px';

            return fieldNameInput;

        }

        function createTypeSelector() {
            const selector = document.createElement('select');
            selector.name = 'types[]';
            selector.classList.add('form-control');
            selector.classList.add('ml-1');
            selector.style.maxWidth = '200px';

            const textOption = document.createElement('option');
            textOption.text = 'Text';
            textOption.value = 'text';

            const numberOption = document.createElement('option');
            numberOption.text = 'Number';
            numberOption.value = 'number';

            selector.appendChild(textOption);
            selector.appendChild(numberOption);

            return selector;
        }

        function createDeleteButton() {
            const button = document.createElement('button');
            button.innerHTML = '<em class="fas fa-trash"></em> Delete';
            button.classList.add('btn');
            button.classList.add('btn-danger');
            button.classList.add('btn-sm');
            button.classList.add('ml-1');
            button.onclick = deleteField;

            return button;
        }

        function deleteField() {
            this.parentElement.remove();
        }
    </script>
@endpush
