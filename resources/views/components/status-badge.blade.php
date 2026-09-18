@php
    $status_class = match ($status) {
        'scheduled' => 'bg-primary-subtle text-primary-emphasis',
        'inprogress' => 'bg-warning-subtle text-warning-emphasis',
        'completed' => 'bg-success-subtle text-success-emphasis',
        'ai_reviewed' => 'bg-info-subtle text-info-emphasis',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge $status_class"]) }}>
    {{ \Str::ucwords(\Str::replace('_', ' ', $status)) }}
</span>
