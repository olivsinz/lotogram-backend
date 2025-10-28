<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enum Language Lines
    |--------------------------------------------------------------------------
    |
    |
    */
    'handler' => [
        'unknown_error' => 'İşleminiz yapılırken bir hata oluştu ve ekiplerimiz bilgilendirildi. Lütfen daha sonra tekrar deneyiniz.',
        'invalid_session' => 'Oturumunuz geçersiz. Lütfen tekrar giriş yapınız.',
        'database_connection_failed' => 'Veritabanı bağlantısı sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        'unauthorized_access_request' => 'Bu işlemi yapmaya yetkiniz bulunmamaktadır.',
        'access_denied_request' => 'Bu işlemi yapamazsınız.',
        'not_found' => 'Aradığınız bilgileri sistemimizde bulamadık.',
        'too_many_requests' => 'Çok fazla istek gönderdiniz. Lütfen bir süre bekleyiniz.',
        'method_not_allowed_http_exception' => 'Bu isteği yapamazsınız.',
        'no_records_found' => 'Aradığınız kayıt bulunamadı.',
        'conflict_http_exception' => 'Bu işlem daha önce yapılmış.',
    ],
    'withdraw' => [
        'invalid_profile_info' => 'Profil bilgileriniz eksik. Lütfen profil bilgilerinizi tamamlayınız.'
    ],
    'competition' => [
        'already_created' => 'Bu plan için daha önce bir yarşma oluşturulmuş. Bu durumda plan ödülleri değiştirilemez.',
        'rewards_total_percentage_exceeded' => 'Eklediğiniz ödül ile bu plana ait ödüllerin toplam yüzdesi 100\'ü geçiyor. Lütfen ödül yüzdesini düşürünüz.'
    ],
    'ticket_purchase' => [
        'insufficient_balance' => 'Yeterli bakiyeniz bulunmamaktadır.',
        'already_purchased' => 'Bu bilet daha önce satın alınmış.',
        'not_available' => 'Bu bilet satışa sunulmamış.',
        'not_enough_tickets' => 'Bu biletten yeterli sayıda kalmamış.',
        'not_found' => 'Böyle bir bilet bulunamadı.',
        'not_available' => 'Bu yarışma için artık bilet satın alamazsınız.'
    ],

];
