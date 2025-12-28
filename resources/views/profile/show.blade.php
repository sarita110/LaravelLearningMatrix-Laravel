@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div style="max-width: 700px; margin: 0 auto;">

    <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50; margin-bottom: 1.75rem;">My Profile</h1>

    {{-- ── Flash messages ─────────────────────────────────────────── --}}
    @if(session('success'))
        <div style="background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; border-radius:6px; padding:12px 16px; margin-bottom:1.5rem; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         CARD 1 — Avatar
    ══════════════════════════════════════════════════════════════ --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.05rem; font-weight: 700; color: #1B2D50; margin-bottom: 1.25rem; padding-bottom: .6rem; border-bottom: 1px solid #eee;">
            Profile Picture
        </h2>

        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            {{-- Current avatar --}}
            <div style="flex-shrink: 0;">
                <img src="{{ $user->avatarUrl() }}"
                     alt="{{ $user->name }}"
                     id="avatar-preview"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e0e7ef; box-shadow: 0 2px 8px rgba(0,0,0,.12);">
            </div>

            {{-- Upload form --}}
            <div style="flex: 1; min-width: 200px;">
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                    @csrf

                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #444; margin-bottom: .5rem;">
                        Upload new photo <span style="font-weight:400; color:#999;">(JPG, PNG, GIF — max 2 MB)</span>
                    </label>

                    <input type="file" name="avatar" id="avatar-input" accept="image/*"
                           style="display:block; margin-bottom: .75rem; font-size:.88rem;"
                           onchange="previewAvatar(this)">

                    @error('avatar')
                        <p style="color:#DC3545; font-size:.83rem; margin-bottom:.5rem;">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn btn-primary btn-sm">Save Photo</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         CARD 2 — Basic Info
    ══════════════════════════════════════════════════════════════ --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.05rem; font-weight: 700; color: #1B2D50; margin-bottom: 1.25rem; padding-bottom: .6rem; border-bottom: 1px solid #eee;">
            Basic Information
        </h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div style="margin-bottom: 1rem;">
                <label for="name" style="display:block; font-size:.85rem; font-weight:600; color:#444; margin-bottom:5px;">
                    Full Name
                </label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') input-error @enderror"
                       required>
                @error('name')
                    <p style="color:#DC3545; font-size:.83rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display:block; font-size:.85rem; font-weight:600; color:#444; margin-bottom:5px;">
                    Email Address
                </label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-control @error('email') input-error @enderror"
                       required>
                @error('email')
                    <p style="color:#DC3545; font-size:.83rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Read-only info --}}
            <div style="background:#F8F9FA; border-radius:6px; padding:12px 14px; margin-bottom:1.25rem; font-size:.85rem; color:#555;">
                <div style="display:flex; gap:2rem; flex-wrap:wrap;">
                    <div>
                        <span style="font-weight:600; color:#333;">Role:</span>
                        @if($user->isAdmin())
                            <span style="background:#1B2D50; color:#fff; font-size:.72rem; font-weight:700; padding:2px 8px; border-radius:8px; margin-left:4px;">ADMIN</span>
                        @else
                            <span style="color:#2E7D32; font-weight:600; margin-left:4px;">User</span>
                        @endif
                    </div>
                    <div>
                        <span style="font-weight:600; color:#333;">Status:</span>
                        <span style="color:#2E7D32; font-weight:600; margin-left:4px;">✓ Active</span>
                    </div>
                    <div>
                        <span style="font-weight:600; color:#333;">Member since:</span>
                        <span style="margin-left:4px;">{{ $user->created_at->format('F j, Y') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         CARD 3 — Change Password
    ══════════════════════════════════════════════════════════════ --}}
    <div class="card">
        <h2 style="font-size: 1.05rem; font-weight: 700; color: #1B2D50; margin-bottom: 1.25rem; padding-bottom: .6rem; border-bottom: 1px solid #eee;">
            Change Password
        </h2>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1rem;">
                <label for="current_password" style="display:block; font-size:.85rem; font-weight:600; color:#444; margin-bottom:5px;">
                    Current Password
                </label>
                <input type="password" id="current_password" name="current_password"
                       class="form-control @error('current_password') input-error @enderror"
                       autocomplete="current-password">
                @error('current_password')
                    <p style="color:#DC3545; font-size:.83rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="password" style="display:block; font-size:.85rem; font-weight:600; color:#444; margin-bottom:5px;">
                    New Password
                </label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') input-error @enderror"
                       autocomplete="new-password">
                @error('password')
                    <p style="color:#DC3545; font-size:.83rem; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="password_confirmation" style="display:block; font-size:.85rem; font-weight:600; color:#444; margin-bottom:5px;">
                    Confirm New Password
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>

</div>

{{-- Live avatar preview before saving --}}
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
