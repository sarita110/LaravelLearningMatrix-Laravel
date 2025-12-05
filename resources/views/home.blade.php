@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- ── HERO ──────────────────────────────────────────────── --}}
<div style="text-align:center; padding: 4rem 1rem 3rem; background: linear-gradient(135deg, #1B2D50 0%, #2E6DA4 100%); border-radius: 12px; color: #fff; margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.8rem; font-weight: 800; margin-bottom: .75rem;">
        The Laravel <span style="color: #5BA4E0;">Learning Matrix</span>
    </h1>
    <p style="font-size: 1.2rem; color: #AACCFF; max-width: 600px; margin: 0 auto 2rem;">
        A meta full-stack application — every concept documented inside the very app it powers.
    </p>
    <a href="{{ route('concepts.index') }}" class="btn btn-primary" style="font-size: 1rem; padding: 12px 28px;">
        Browse All Concepts
    </a>
</div>

{{-- ── STATS ─────────────────────────────────────────────── --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2.5rem;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #2E6DA4;">{{ $stats['total'] }}</div>
        <div style="color: #666; font-size: .9rem; margin-top: 4px;">Concepts Published</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #2E6DA4;">{{ $stats['phases'] }}</div>
        <div style="color: #666; font-size: .9rem; margin-top: 4px;">Learning Phases</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #2E6DA4;">{{ $stats['categories'] }}</div>
        <div style="color: #666; font-size: .9rem; margin-top: 4px;">Categories</div>
    </div>
</div>

{{-- ── PHASE OVERVIEW ────────────────────────────────────── --}}
<div class="card" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1.25rem; color: #1B2D50;">The 7 Phases</h2>

    @php
        $phases = [
            1 => ['title' => 'Foundation',        'desc' => 'Setup, Routing, Blade, Artisan, Config',                    'color' => '#1B2D50'],
            2 => ['title' => 'The Data Layer',     'desc' => 'Migrations, Eloquent ORM, Relationships, Collections',      'color' => '#154360'],
            3 => ['title' => 'CRUD & Forms',       'desc' => 'Controllers, The Request, Forms, Validation',               'color' => '#0D3D0D'],
            4 => ['title' => 'Auth & Authorization','desc' => 'Breeze, Middleware, Gates & Policies',                     'color' => '#3B0F52'],
            5 => ['title' => 'Mail',               'desc' => 'Sending emails and notifications',                          'color' => '#7A3100'],
            6 => ['title' => 'REST API Basics',    'desc' => 'API routes, controllers, and JSON responses',               'color' => '#1A3A50'],
            7 => ['title' => 'Advanced',           'desc' => 'Service Container, Events, Queues, Sanctum, Repositories',  'color' => '#6B1728'],
        ];
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
        @foreach($phases as $num => $phase)
        <a href="{{ route('concepts.index', ['phase' => $num]) }}" style="text-decoration: none;">
            <div class="card-sm" style="border-left: 4px solid {{ $phase['color'] }};">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                    <span class="phase-pill" style="background: {{ $phase['color'] }};">Phase {{ $num }}</span>
                    <span style="font-weight: 700; color: #1B2D50;">{{ $phase['title'] }}</span>
                </div>
                <p style="font-size: .85rem; color: #666;">{{ $phase['desc'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- ── THE META-PROJECT EXPLANATION ─────────────────────── --}}
<div class="card" style="border-left: 4px solid #2E6DA4; background: #EBF5FB;">
    <h2 style="font-size: 1.2rem; font-weight: 700; color: #1B4F8A; margin-bottom: .75rem;">
        What is the Learning Matrix?
    </h2>
    <p style="color: #2C3E50; margin-bottom: .75rem;">
        This application is a <strong>meta full-stack project</strong> — the content it manages is documentation about the very Laravel concepts that power it.
    </p>
    <p style="color: #2C3E50; margin-bottom: .75rem;">
        When you read about <em>Routing</em>, the URL you visited was matched by a Route.
        When you read about <em>Eloquent Relationships</em>, the data was fetched using an Eloquent relationship.
        When you submit a new concept, a <em>Form Request</em> validates it before it reaches the controller.
    </p>
    <p style="color: #2C3E50; font-weight: 600;">
        The goal: to move from "I have heard of that" to "I have built that."
    </p>
</div>

@endsection
