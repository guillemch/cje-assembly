@extends('layouts.app')

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
            votes: {!! Auth::user()->votes !!}
        }
    </script>
    <script src="{{ mix('/js/vote.js') }}"></script>
@endpush
