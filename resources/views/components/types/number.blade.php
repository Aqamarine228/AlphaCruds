<input type="number"
       class="form-control  @error($errorName) is-invalid @enderror"
       id="{{ $id }}"
       name="{{ $name }}"
       value="{{ $defaultValue }}"
       @if ($disabled) disabled @endif
       @if (isset($min))
       min="{{ $min }}"
       @endif
       @if (isset($step))
       step="{{ $step }}"
       @endif
       @if (isset($max))
       max="{{ $max }}"
       @endif
       placeholder="{{ $placeholder ?? $label }}">
