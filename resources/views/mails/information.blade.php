@extends('mails.layout.default')

@section('content')
    Merhaba {{ $user->first_name }}, <br>
    {{$content}}
@endsection
