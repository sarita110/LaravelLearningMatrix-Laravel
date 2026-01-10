@extends('layouts.app')

@section('title', 'Admin — User Management')

@section('content')

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50;">User Management</h1>
        <p style="color: #666; margin-top: 4px;">Approve new registrations and manage user accounts.</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #D4EDDA; color: #155724; border: 1px solid #C3E6CB; border-radius: 6px; padding: 12px 16px; margin-bottom: 1.5rem; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

{{-- ── PENDING APPROVALS ─────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.2rem; font-weight: 700; color: #7A3100; margin-bottom: 1rem;">
        ⏳ Pending Approval
        @if($pending->count())
            <span style="background: #FFC107; color: #333; font-size: .75rem; padding: 2px 8px; border-radius: 10px; margin-left: 6px;">
                {{ $pending->count() }}
            </span>
        @endif
    </h2>

    @forelse($pending as $user)
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee;">
        <div>
            <div style="font-weight: 700; color: #1B2D50;">{{ $user->name }}</div>
            <div style="font-size: .85rem; color: #666;">{{ $user->email }}</div>
            <div style="font-size: .78rem; color: #999; margin-top: 2px;">
                Registered {{ $user->created_at->diffForHumans() }}
            </div>
        </div>
        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm"
                    onclick="return confirm('Approve {{ addslashes($user->name) }}\'s account?')">
                ✓ Approve
            </button>
        </form>
    </div>
    @empty
    <p style="color: #888; font-style: italic;">No pending registrations — you're all caught up!</p>
    @endforelse
</div>

{{-- ── ALL APPROVED USERS ────────────────────────────────────── --}}
<div class="card">
    <h2 style="font-size: 1.2rem; font-weight: 700; color: #1B2D50; margin-bottom: 1rem;">
        ✓ Approved Users ({{ $approved->count() }})
    </h2>

    @foreach($approved as $user)
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
        <div>
            <span style="font-weight: 700; color: #1B2D50;">{{ $user->name }}</span>
            @if($user->isAdmin())
                <span style="background: #1B2D50; color: #fff; font-size: .7rem; font-weight: 700; padding: 1px 7px; border-radius: 8px; margin-left: 6px;">ADMIN</span>
            @endif
            <div style="font-size: .83rem; color: #666;">{{ $user->email }}</div>
        </div>
        <span style="font-size: .78rem; color: #2E7D32; font-weight: 600;">✓ Active</span>
    </div>
    @endforeach
</div>

@endsection
