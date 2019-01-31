@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="myVotes">
            <my-votes></my-votes>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/my-votes.js') }}"></script>
@endpush
