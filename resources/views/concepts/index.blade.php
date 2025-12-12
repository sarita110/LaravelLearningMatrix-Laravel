@extends('layouts.app')

@section('title', 'Browse Concepts' . ($phase ? ' — Phase ' . $phase . ': ' . $phaseName : ''))

@section('content')

{{-- ── PAGE HEADER ──────────────────────────────────────── --}}
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50;">
            @if($phase)
                Phase {{ $phase }}: {{ $phaseName }}
            @else
                All Laravel Concepts
            @endif
        </h1>
        <p style="color: #666; margin-top: 4px;">
            {{ $concepts->total() }} concept{{ $concepts->total() !== 1 ? 's' : '' }} found
        </p>
    </div>

    @auth
    <a href="{{ route('concepts.create') }}" class="btn btn-primary">+ Add Concept</a>
    @endauth
</div>

{{-- ── FILTERS ──────────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
    <form method="GET" action="{{ route('concepts.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">

        {{-- Search --}}
        <div style="flex: 1; min-width: 180px;">
            <label style="font-size: .8rem; font-weight: 600; color: #666; display: block; margin-bottom: 4px;">Search</label>
            <input type="text" name="search" class="form-control"
                   placeholder="Search concepts..."
                   value="{{ $search }}">
        </div>

        {{-- Phase filter --}}
        <div>
            <label style="font-size: .8rem; font-weight: 600; color: #666; display: block; margin-bottom: 4px;">Phase</label>
            <select name="phase" class="form-control" style="min-width: 120px;">
                <option value="">All Phases</option>
                @foreach($phases as $p)
                    <option value="{{ $p }}" {{ (int)$phase === $p ? 'selected' : '' }}>
                        Phase {{ $p }}{{ isset($phaseNames[$p]) ? ': ' . $phaseNames[$p] : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="height: 38px;">Filter</button>

        @if($search || $phase)
            <a href="{{ route('concepts.index') }}" class="btn btn-secondary" style="height: 38px; line-height: 22px;">Clear</a>
        @endif
    </form>
</div>

{{-- ── CONCEPT GRID ──────────────────────────────────────── --}}
@forelse($concepts as $concept)
    <div class="card-sm" style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: flex-start;">

        {{-- Phase badge --}}
        <div style="flex-shrink: 0; padding-top: 3px;">
            @phaseBadge($concept->phase)
        </div>

        {{-- Content --}}
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: .5rem;">
                <h2 style="font-size: 1.05rem; font-weight: 700;">
                    <a href="{{ route('concepts.show', $concept) }}" style="color: #1B2D50;">{{ $concept->title }}</a>
                </h2>

                {{-- Category --}}
                @if($concept->category)
                <span style="background: {{ $concept->category->color }}22; color: {{ $concept->category->color }}; border: 1px solid {{ $concept->category->color }}44; padding: 2px 8px; border-radius: 10px; font-size: .75rem; font-weight: 600;">
                    {{ $concept->category->name }}
                </span>
                @endif
            </div>

            <p style="color: #555; font-size: .88rem; margin-top: 5px; line-height: 1.5;">
                {{ Str::limit($concept->description, 140) }}
            </p>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: .75rem;">
                <div style="font-size: .78rem; color: #999;">
                    {{ $concept->view_count }} view{{ $concept->view_count !== 1 ? 's' : '' }}
                    &nbsp;&bull;&nbsp;
                    <span style="background: #f0f0f0; padding: 1px 6px; border-radius: 4px; font-family: monospace; font-size: .75rem;">{{ $concept->code_language }}</span>
                </div>

                <div style="display: flex; gap: .5rem;">
                    <a href="{{ route('concepts.show', $concept) }}" class="btn btn-primary btn-sm">View</a>

                    @can('update', $concept)
                    <a href="{{ route('concepts.edit', $concept) }}" class="btn btn-secondary btn-sm">Edit</a>
                    @endcan

                    @can('delete', $concept)
                    <form method="POST" action="{{ route('concepts.destroy', $concept) }}"
                          onsubmit="return confirm('Delete {{ addslashes($concept->title) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card" style="text-align: center; padding: 3rem; color: #666;">
        <p style="font-size: 1.1rem; margin-bottom: 1rem;">No concepts found yet.</p>
        @auth
        <a href="{{ route('concepts.create') }}" class="btn btn-primary">Add the First Concept</a>
        @endauth
    </div>
@endforelse

{{-- ── PAGINATION ────────────────────────────────────────── --}}
@if($concepts->hasPages())
<div class="pagination-wrapper">
    {{ $concepts->links() }}
</div>
@endif

@endsection
