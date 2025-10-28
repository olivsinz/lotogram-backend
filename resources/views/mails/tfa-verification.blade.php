@extends('mails.layout.default')

@section('content')
    Merhaba {{ $user->first_name }}, <br>
    Secret : {{$secret}}
@endsection
