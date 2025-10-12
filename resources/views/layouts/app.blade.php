<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel Learning Matrix') — The Learning Matrix</title>

    <style>
        /* ── Reset & Base ──────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #2c2c2c; line-height: 1.6; }
        a { color: #2E6DA4; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* ── Navigation ────────────────────────────────── */
        .navbar { background: #1B2D50; color: #fff; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 56px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,.3); }
        .navbar-brand { font-size: 1.1rem; font-weight: 700; color: #fff; letter-spacing: .5px; }
        .navbar-brand span { color: #5BA4E0; }
        .navbar-links { display: flex; gap: 1.5rem; align-items: center; }
        .navbar-links a { color: #AACCFF; font-size: .9rem; transition: color .2s; }
        .navbar-links a:hover { color: #fff; text-decoration: none; }
        .btn-nav { background: #2E6DA4; color: #fff !important; padding: 6px 14px; border-radius: 4px; font-size: .85rem; }
        .btn-nav:hover { background: #1B4F8A !important; }

        /* ── Layout ─────────────────────────────────────── */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
        .main-content { padding: 2rem 0; min-height: calc(100vh - 56px - 56px); }

        /* ── Flash Messages ─────────────────────────────── */
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem; font-size: .9rem; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-error   { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }

        /* ── Cards ──────────────────────────────────────── */
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 1.5rem; }
        .card-sm { background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 1rem; transition: box-shadow .2s, transform .2s; }
        .card-sm:hover { box-shadow: 0 4px 12px rgba(0,0,0,.12); transform: translateY(-2px); }

        /* ── Buttons ─────────────────────────────────────── */
        .btn { display: inline-block; padding: 8px 18px; border-radius: 5px; font-size: .9rem; font-weight: 600; cursor: pointer; border: none; transition: opacity .15s; }
        .btn:hover { opacity: .88; }
        .btn-primary   { background: #2E6DA4; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-danger    { background: #dc3545; color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: .8rem; }

        /* ── Forms ──────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 4px; font-size: .875rem; color: #444; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: .9rem; transition: border-color .2s; }
        .form-control:focus { outline: none; border-color: #2E6DA4; box-shadow: 0 0 0 3px rgba(46,109,164,.15); }
        .form-control.is-invalid { border-color: #dc3545; }
        .invalid-feedback { color: #dc3545; font-size: .8rem; margin-top: 3px; }
        textarea.form-control { min-height: 120px; resize: vertical; font-family: inherit; }
        select.form-control { background-color: #fff; }

        /* ── Badges ─────────────────────────────────────── */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: .75rem; font-weight: 600; color: #fff; }
        .badge-phase { background: #1B2D50; }

        /* ── Code blocks ─────────────────────────────────── */
        .code-block { background: #1e2433; color: #abb2bf; border-radius: 6px; padding: 1.25rem; overflow-x: auto; font-family: 'Courier New', monospace; font-size: .85rem; line-height: 1.6; margin: 1rem 0; border-left: 4px solid #2E6DA4; }
        .code-lang { font-size: .7rem; color: #888; text-transform: uppercase; font-weight: 700; margin-bottom: 6px; }

        /* ── Phase pills ─────────────────────────────────── */
        .phase-pill { display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: .78rem; font-weight: 700; color: #fff; }

        /* ── Pagination ──────────────────────────────────── */
        .pagination-wrapper { margin-top: 2rem; display: flex; justify-content: center; }
        .pagination-wrapper nav { display: flex; gap: 4px; }
        .pagination-wrapper svg { width: 14px; height: 14px; }

        /* ── Page header ─────────────────────────────────── */
        .page-header { background: #1B2D50; color: #fff; padding: 2.5rem 0; margin-bottom: 2rem; }
        .page-header h1 { font-size: 2rem; font-weight: 700; }
        .page-header p  { color: #AACCFF; margin-top: .5rem; }

        /* ── Footer ─────────────────────────────────────── */
        footer { background: #1B2D50; color: #8AADCC; text-align: center; padding: 1rem; font-size: .8rem; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ── NAVIGATION ──────────────────────────────────── --}}
    @include('partials.nav')

    {{-- ── MAIN CONTENT ────────────────────────────────── --}}
    <main class="main-content">
        <div class="container">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" role="alert">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- ── FOOTER ──────────────────────────────────────── --}}
    <footer>
        <p>The Laravel Learning Matrix &copy; {{ date('Y') }} &mdash; Built with PHP / Laravel</p>
    </footer>

    @stack('scripts')
</body>
</html>
