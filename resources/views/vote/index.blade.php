@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="vote">
            <vote></vote>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/vote.js') }}"></script>
@endpush
