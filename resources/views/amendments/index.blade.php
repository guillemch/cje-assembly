@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="amendments">
            <amendments></amendments>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.config = {
            groups: {!! json_encode($groups) !!}
        }
    </script>
    <script src="{{ mix('/js/amendments.js') }}"></script>
@endpush
