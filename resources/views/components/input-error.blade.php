@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        @foreach ((array) $messages as $message)
            <small class="d-block text-danger">{{ $message }}</small>
        @endforeach
    </div>
@endif
