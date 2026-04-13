@props(['action'])

<button
    type="button"
    x-data
    x-init="$el.addEventListener('click', () => {
        window.dispatchEvent(new CustomEvent('expand-all-accounts'));
    })"
    style="display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-weight: 600; font-size: 0.875rem; transition: all 75ms; border: 1px solid #e5e7eb; background: white; color: #4b5563; cursor: pointer; white-space: nowrap;"
    onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#d1d5db';"
    onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb';"
>
    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
    <span>{{ __('Expand All') }}</span>
</button>
