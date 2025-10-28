@extends('mails.layout.default')

@section('content')
    Merhaba {{ $user->first_name }} {{ $user->last_name }}, <br>
    Üyeliğiniz başarı ile tamamlanmıştır. Hemen ilk piyango biletinizi alın ve kazanma şansınızı arttırın. <br><br>

    Lotogram Destek Ekibi
@endsection
