@extends('layouts.app')

@section('title', $concept->title)

@section('content')

{{-- ── BREADCRUMB ───────────────────────────────────────── --}}
<nav style="font-size: .85rem; color: #888; margin-bottom: 1.25rem;">
    <a href="{{ route('home') }}">Home</a>
    &nbsp;/&nbsp;
    <a href="{{ route('concepts.index') }}">Concepts</a>
    &nbsp;/&nbsp;
    <a href="{{ route('concepts.index', ['phase' => $concept->phase]) }}">Phase {{ $concept->phase }}</a>
    &nbsp;/&nbsp;
    <span style="color: #444;">{{ $concept->title }}</span>
</nav>

<div style="display: grid; grid-template-columns: 1fr 280px; gap: 1.5rem;">

    {{-- ── LEFT: Main content ──────────────────────────── --}}
    <div>
        {{-- Title block --}}
        <div class="card" style="margin-bottom: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    @phaseBadge($concept->phase)
                    @if($concept->category)
                    <span style="margin-left: 8px; background: {{ $concept->category->color }}22; color: {{ $concept->category->color }}; border: 1px solid {{ $concept->category->color }}44; padding: 2px 8px; border-radius: 10px; font-size: .75rem; font-weight: 600;">
                        {{ $concept->category->name }}
                    </span>
                    @endif
                </div>

                <div style="display: flex; gap: .5rem;">
                    @can('update', $concept)
                    <a href="{{ route('concepts.edit', $concept) }}" class="btn btn-secondary btn-sm">Edit</a>
                    @endcan

                    @can('delete', $concept)
                    <form method="POST" action="{{ route('concepts.destroy', $concept) }}"
                          onsubmit="return confirm('Delete this concept permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @endcan
                </div>
            </div>

            <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50; margin-bottom: .75rem;">
                {{ $concept->title }}
            </h1>

            <p style="color: #444; font-size: 1rem; line-height: 1.7;">{{ $concept->description }}</p>

            {{-- Tags --}}
            @if($concept->tags->isNotEmpty())
            <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: .4rem;">
                @foreach($concept->tags as $tag)
                <span class="badge" style="background: {{ $tag->color }};">{{ $tag->name }}</span>
                @endforeach
            </div>
            @endif

            {{-- Meta --}}
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #eee; font-size: .8rem; color: #999; display: flex; gap: 1.5rem;">
                <span>{{ $concept->view_count }} views</span>
                @if($concept->author)
                <span>By {{ $concept->author->name }}</span>
                @endif
                <span>Updated {{ $concept->updated_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Full explanation --}}
        @if($concept->explanation)
        <div class="card" style="margin-bottom: 1.25rem;">
            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1B2D50; margin-bottom: 1rem; padding-bottom: .5rem; border-bottom: 2px solid #2E6DA4;">
                Explanation
            </h2>
            <div style="line-height: 1.8; color: #333; white-space: pre-line;">{{ $concept->explanation }}</div>
        </div>
        @endif

        {{-- Featured code example --}}
        @if($concept->code_example)
        <div class="card" style="margin-bottom: 1.25rem;">
            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1B2D50; margin-bottom: .75rem;">
                Code Example
            </h2>
            <div class="code-lang">{{ strtoupper($concept->code_language) }}</div>
            <div class="code-block"><pre>{{ $concept->code_example }}</pre></div>
        </div>
        @endif

        {{-- Additional examples --}}
        @if($concept->examples->isNotEmpty())
        <div class="card">
            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1B2D50; margin-bottom: 1rem;">
                Additional Examples ({{ $concept->examples->count() }})
            </h2>
            @foreach($concept->examples as $example)
            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 700; color: #333; margin-bottom: .25rem;">{{ $example->title }}</h3>
                @if($example->description)
                <p style="font-size: .88rem; color: #666; margin-bottom: .5rem;">{{ $example->description }}</p>
                @endif
                <div class="code-lang">{{ strtoupper($example->language) }}</div>
                <div class="code-block"><pre>{{ $example->code }}</pre></div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── RIGHT: Sidebar ──────────────────────────────── --}}
    <aside>
        {{-- Related concepts --}}
        @if($related->isNotEmpty())
        <div class="card" style="margin-bottom: 1.25rem;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1B2D50; margin-bottom: .75rem;">
                Also in Phase {{ $concept->phase }}
            </h3>
            @foreach($related as $r)
            <div style="padding: .5rem 0; border-bottom: 1px solid #f0f0f0; last-child:border-none;">
                <a href="{{ route('concepts.show', $r) }}" style="font-size: .9rem; font-weight: 600; color: #2E6DA4;">
                    {{ $r->title }}
                </a>
                <p style="font-size: .8rem; color: #777; margin-top: 2px;">{{ Str::limit($r->description, 60) }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- API endpoint hint --}}
        <div class="card" style="background: #1e2433; color: #abb2bf;">
            <div style="font-size: .75rem; font-weight: 700; color: #5BA4E0; margin-bottom: .5rem;">API ENDPOINT</div>
            <code style="font-size: .8rem; word-break: break-all;">GET /api/concepts/{{ $concept->slug }}</code>
        </div>
    </aside>

</div>

@endsection
