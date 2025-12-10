@extends('layouts.app')

@section('title', 'Add New Concept')

@section('content')

<div style="max-width: 760px; margin: 0 auto;">

    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50;">Add New Concept</h1>
        <p style="color: #666; margin-top: 4px;">Document a Laravel concept and add it to the Learning Matrix.</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('concepts.store') }}">
            @csrf {{-- CSRF token — required for all POST/PUT/DELETE forms --}}

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Concept Title *</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" placeholder="e.g. Service Providers" autofocus>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Category + Phase (2 columns) --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phase">Phase *</label>
                    <select id="phase" name="phase" class="form-control @error('phase') is-invalid @enderror">
                        <option value="">— Select Phase —</option>
                        @foreach($phases as $p)
                        <option value="{{ $p }}" {{ old('phase') == $p ? 'selected' : '' }}>Phase {{ $p }}</option>
                        @endforeach
                    </select>
                    @error('phase')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description">Short Description * <small style="font-weight:400; color:#888;">(min. 20 chars — shown in listings)</small></label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                          placeholder="A brief one-or-two sentence summary of the concept." style="min-height:80px;">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Explanation --}}
            <div class="form-group">
                <label for="explanation">Full Explanation <small style="font-weight:400; color:#888;">(Markdown supported)</small></label>
                <textarea id="explanation" name="explanation" class="form-control @error('explanation') is-invalid @enderror"
                          placeholder="Write a detailed explanation of this concept, its purpose, and how it fits into Laravel's architecture." style="min-height: 180px;">{{ old('explanation') }}</textarea>
                @error('explanation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Code example + Language --}}
            <div class="form-group">
                <label for="code_example">Featured Code Example</label>
                <div style="display: grid; grid-template-columns: 1fr 160px; gap: .75rem; align-items: start;">
                    <textarea id="code_example" name="code_example" class="form-control @error('code_example') is-invalid @enderror"
                              placeholder="<?php&#10;&#10;// paste your code here"
                              style="min-height: 180px; font-family: 'Courier New', monospace; font-size: .85rem; background: #f9fafb;">{{ old('code_example') }}</textarea>

                    <div>
                        <label for="code_language" style="margin-bottom: 4px;">Language</label>
                        <select id="code_language" name="code_language" class="form-control">
                            @foreach(['php', 'blade', 'bash', 'json', 'sql'] as $lang)
                            <option value="{{ $lang }}" {{ old('code_language', 'php') === $lang ? 'selected' : '' }}>
                                {{ strtoupper($lang) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('code_example')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Publish toggle --}}
            <div class="form-group" style="display: flex; align-items: center; gap: .75rem;">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" id="is_published" name="is_published" value="1"
                       {{ old('is_published') ? 'checked' : '' }}
                       style="width: 18px; height: 18px; cursor: pointer;">
                <label for="is_published" style="margin-bottom: 0; cursor: pointer;">
                    Publish immediately
                    <small style="font-weight:400; color:#888; display:block;">(Checking this fires a ConceptPublished event and triggers an email notification job)</small>
                </label>
            </div>

            {{-- Actions --}}
            <div style="display: flex; gap: .75rem; padding-top: 1rem; border-top: 1px solid #eee; margin-top: .5rem;">
                <button type="submit" class="btn btn-primary">Save Concept</button>
                <a href="{{ route('concepts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>

@endsection
