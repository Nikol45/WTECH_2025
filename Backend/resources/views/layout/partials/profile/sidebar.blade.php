<div class="h-100 border pt-4 p-3 text-center position-relative">
    {{-- Avatar --}}
    <div class="position-relative d-inline-block">
        <img src="{{ asset($user->icon->path ?? 'images/empty.png') }}" class="rounded-circle mb-2" alt="Avatar" width="100" height="100">
        <button class="btn custom-button edit-avatar-btn" title="Upraviť avatar">
            <span class="material-icons">add_a_photo</span>
        </button>
    </div>

    {{-- Nickname --}}
    <div class="my-4 d-flex align-items-center justify-content-center">
        <h5 class="fw-bold mb-0 me-2">{{ $user->nickname ?? 'Používateľ' }}</h5>
        <button class="btn custom-button p-0 ms-2" title="Upraviť prezývku">
            <span class="material-icons">drive_file_rename_outline</span>
        </button>
    </div>

    {{-- Odznak --}}
    <div class="mb-2 fw-bold">
        <span style="font-size: 1.5rem;" class="me-2">{{ $user->badge_icon ?? '🧺' }}</span>
        {{ $user->badge_name ?? 'Skúsený ochutnávač' }}
    </div>

    {{-- Progress bar --}}
    <div class="px-3 mb-4">
        <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">{{ $user->badge_current ?? 0 }}</small>
            <small class="text-muted">{{ $user->badge_target ?? 30 }}</small>
        </div>
        <div class="progress mb-2">
            <div class="progress-bar" style="width: {{ round(($user->badge_current ?? 0) / ($user->badge_target ?? 1) * 100, 1) }}%;"></div>
        </div>
        <small class="text-muted">
            Ešte {{ ($user->badge_target ?? 0) - ($user->badge_current ?? 0) }} recenzií do ďalšieho odznaku
        </small>
    </div>
</div>
