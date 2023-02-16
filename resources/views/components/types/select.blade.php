<?php
$items = $items instanceof \Illuminate\Support\Collection ? $items->toArray() : $items;
$multiple = $multiple ?? false;
$isItemsAssocArray = $items === array_values($items);
?>
<select name="{{ $name }}" id="{{ $id }}"
        class="form-control  @error($errorName) is-invalid @enderror"
        {{ $multiple ? 'multiple' : '' }} @if ($disabled) disabled @endif>

    @foreach($items as $key => $item)
        @php $value = $isItemsAssocArray ? $item : $key;  @endphp
        <option value="{{ $value }}"
        @if (is_array($defaultValue) || $defaultValue instanceof \Illuminate\Support\Collection)
            @foreach($defaultValue as $valueItem)
                {{ $valueItem == $value ? 'selected' : ''  }}
                @endforeach
            @else
            {{ $defaultValue == $value ? 'selected' : ''  }}
            @endif
        >
            {{ $item }}
        </option>
        {{--        @if (is_array($defaultValue) || $defaultValue instanceof \Illuminate\Database\Eloquent\Collection)--}}
        {{--            <option value="{{ $item[$optionId] }}"--}}
        {{--                @foreach($defaultValue as $defaultValueItem)--}}
        {{--                    {{ $defaultValueItem['id'] == $item['id'] ? 'selected' : ''  }}--}}
        {{--                @endforeach--}}
        {{--            >--}}
        {{--                {{ $item[$optionName] }}--}}
        {{--            </option>--}}
        {{--        @else--}}
        {{--            @if (isset($item[$optionId]))--}}
        {{--                <option value="{{ $item[$optionId] }}"--}}
        {{--                        {{ $defaultValue == $item[$optionId] ? 'selected' : ''  }}--}}
        {{--                >--}}
        {{--                    {{ $item[$optionName] }}--}}
        {{--                </option>--}}

        {{--            @else--}}

        {{--            @endif--}}
        {{--        @endif--}}

    @endforeach
</select>
