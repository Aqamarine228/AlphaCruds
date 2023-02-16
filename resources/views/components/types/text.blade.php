<input type="text"
       class="form-control  @error($errorName) is-invalid @enderror"
       @if ($disabled) disabled @endif
       id="{{ $id }}"
       name="{{ $name }}"
       value="{{ $defaultValue }}"
       placeholder="{{ $placeholder ?? $label }}">
