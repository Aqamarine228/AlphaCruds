<form action="{{$route}}" method="get">
    @include('alphacruds::components.input_group', [
        'type' => 'text',
        'label' => 'Table',
        'name' => 'table',
        'placeholder' => 'Table Name'
    ])
    <button type="submit" class="btn btn-primary btn-sm mb-3"><em class="fas fa-search"></em> Get Fields</button>
</form>
