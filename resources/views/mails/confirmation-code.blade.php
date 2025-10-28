@extends('mails.layout.default')

@section('content')

    {{ trans('mail.hello') }} {{ $user->first_name }} {{ $user->last_name }}, <br><br>
    {!! $content !!} <br>

    @include('mails.components.code-section', ['code' => $secret])

@endsection
