@extends('layouts.screen')

@section('content')
    <div id="screen">
        <screen></screen>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/screen.js') }}"></script>
@endpush
