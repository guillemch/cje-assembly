@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="secretVote">
            <secret-vote></secret-vote>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.user = {
            votes: {!! Auth::user()->votes !!}
        }
    </script>
    <script src="{{ mix('/js/secret-vote.js') }}"></script>
@endpush
