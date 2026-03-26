@props(['field', 'label'])

@php
    $currentSort = request('sort');
    $currentDir = request('direction', 'asc');
    $isActive = $currentSort === $field;
    $nextDir = $isActive && $currentDir === 'asc' ? 'desc' : 'asc';
    
    $icon = 'bi-arrow-down-up';
    if ($isActive) {
        $icon = $currentDir === 'asc' ? 'bi-sort-up' : 'bi-sort-down';
    }
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDir]) }}" 
   class="text-decoration-none {{ $isActive ? 'text-primary fw-700' : 'text-muted' }}">
    {{ $label }}
    <i class="bi {{ $icon }} ms-1 small" style="font-size: 0.75rem;"></i>
</a>
