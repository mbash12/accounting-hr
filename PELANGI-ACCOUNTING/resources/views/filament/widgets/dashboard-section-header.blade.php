<div style="grid-column: 1 / -1; margin-top: 1.5rem; margin-bottom: 0.5rem;">
    <div style="display: flex; align-items: center; gap: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">
        <div style="padding: 0.5rem; background-color: #f0f9ff; border-radius: 0.5rem;">
            @if($icon)
                <x-filament::icon
                    icon="{{ $icon }}"
                    style="height: 1.5rem; width: 1.5rem; color: #0284c7;"
                />
            @endif
        </div>
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.025em; color: #111827;">
                {{ $title }}
            </h2>
            <p style="font-size: 0.875rem; color: #6b7280;">
                {{ $description }}
            </p>
        </div>
    </div>
</div>
