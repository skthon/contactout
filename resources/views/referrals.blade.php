@extends('layouts.app')

@section('content')
    <div id="root" data-access-token="{{ session('access_token') }}">
    </div>
</div>
@endsection
