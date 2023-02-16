<textarea name="{{ $name }}" id="{{ $id }}" class="form-control @error($errorName) is-invalid @enderror" cols="{{ $cols ?? 30 }}" rows="{{ $rows ?? 10 }}">{{ $defaultValue }}</textarea>
