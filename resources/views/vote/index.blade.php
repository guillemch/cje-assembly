@extends('layouts.app')

@section('title', 'Votar - ')

@section('content')
    <div class="container">
        <div id="vote">
            <vote></vote>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.user = {
            votes: {!! Auth::user()->votes !!},
            code_exemption: {!! Auth::user()->code_exemption !!}
        }
    </script>
    <script src="{{ mix('/js/vote.js') }}"></script>
@endpush
