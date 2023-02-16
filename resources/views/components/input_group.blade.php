<?php
$id = $id ?? $name;
$errorName = $errorName ?? $name;
$required = isset($required) ? $required : false;
$type = $type ?? 'text';
$disabled = $disabled ?? false;
$data = $data ?? [];
?>
@if ($label !== false)
    <div class="form-group {{ $errors->has($errorName) ? 'has-error' : '' }}">

        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <code>*</code>
            @endif
        </label>
        @endif

        <?php
        $defaultValue = old($name) ?? $defaultValue ?? null;
        $type = $type ?? 'text';
        $view = 'alphanews::components.types.' . $type;
        ?>
        @if (view()->exists($view))
            @include('alphanews::components.types.' . $type)
        @else
            <input
                type="{{ $type }}"
                class=" @error($errorName) is-invalid @enderror"
                id="{{ $id }}"
                name="{{ $name }}"
                value="{{ $defaultValue }}"
                @foreach($data as $key => $value)
                    data-{{ $key }}="{{ $value }}"
                @endforeach
            >
        @endif

        @if ($errors->has($errorName))
            <span class="help-block error invalid-feedback">
            <strong>{{ $errors->first($errorName) }}</strong>
        </span>
        @endif
        @if ($label !== false)
    </div>
@endif
