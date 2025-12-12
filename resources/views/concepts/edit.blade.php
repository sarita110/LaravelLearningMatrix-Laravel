@extends('layouts.app')

@section('title', 'Edit: ' . $concept->title)

@section('content')

<div style="max-width: 760px; margin: 0 auto;">

    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #1B2D50;">Edit Concept</h1>
            <p style="color: #666; margin-top: 4px;">{{ $concept->title }}</p>
        </div>
        <a href="{{ route('concepts.show', $concept) }}" class="btn btn-secondary">← Back to Concept</a>
    </div>

    <div class="card">
        {{-- PUT method — HTML forms only support GET/POST, so we spoof PUT with @method --}}
        <form method="POST" action="{{ route('concepts.update', $concept) }}">
            @csrf
            @method('PUT') {{-- Method spoofing: sends X-HTTP-Method-Override: PUT header --}}

            <div class="form-group">
                <label for="title">Concept Title *</label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $concept->title) }}" autofocus>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $concept->category_id) == $cat->id ? 'selected' : '' }}>
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
                        @foreach($phases as $p)
                        <option value="{{ $p }}" {{ old('phase', $concept->phase) == $p ? 'selected' : '' }}>
                            Phase {{ $p }}
                        </option>
                        @endforeach
                    </select>
                    @error('phase')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Short Description *</label>
                <textarea id="description" name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          style="min-height: 80px;">{{ old('description', $concept->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="explanation">Full Explanation</label>
                <textarea id="explanation" name="explanation"
                          class="form-control @error('explanation') is-invalid @enderror"
                          style="min-height: 180px;">{{ old('explanation', $concept->explanation) }}</textarea>
                @error('explanation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="code_example">Featured Code Example</label>
                <div style="display: grid; grid-template-columns: 1fr 160px; gap: .75rem; align-items: start;">
                    <textarea id="code_example" name="code_example"
                              class="form-control @error('code_example') is-invalid @enderror"
                              style="min-height: 180px; font-family: 'Courier New', monospace; font-size: .85rem; background: #f9fafb;">{{ old('code_example', $concept->code_example) }}</textarea>
                    <div>
                        <label for="code_language">Language</label>
                        <select id="code_language" name="code_language" class="form-control">
                            @foreach(['php', 'blade', 'bash', 'json', 'sql'] as $lang)
                            <option value="{{ $lang }}" {{ old('code_language', $concept->code_language) === $lang ? 'selected' : '' }}>
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

            <div class="form-group" style="display: flex; align-items: center; gap: .75rem;">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" id="is_published" name="is_published" value="1"
                       {{ old('is_published', $concept->is_published) ? 'checked' : '' }}
                       style="width: 18px; height: 18px; cursor: pointer;">
                <label for="is_published" style="margin-bottom: 0; cursor: pointer;">
                    Published
                    <small style="font-weight:400; color:#888; display:block;">
                        @if(!$concept->is_published)
                            (Publishing will fire the ConceptPublished event and send email notifications)
                        @else
                            (Currently published — uncheck to make it a draft)
                        @endif
                    </small>
                </label>
            </div>

            <div style="display: flex; gap: .75rem; padding-top: 1rem; border-top: 1px solid #eee; margin-top: .5rem;">
                <button type="submit" class="btn btn-primary">Update Concept</button>
                <a href="{{ route('concepts.show', $concept) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>

@endsection
