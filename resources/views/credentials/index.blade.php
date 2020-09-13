@extends('layouts.app')

@section('title', 'Acreditaciones - ')

@section('content')
    <div class="container">
        <div id="credentials">
            <credentials></credentials>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/credentials.js') }}"></script>
@endpush
