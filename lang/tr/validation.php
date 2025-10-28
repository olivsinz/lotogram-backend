<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, doğrulayıcı sınıf tarafından kullanılan varsayılan
    | hata mesajlarını içerir. Bu kurallardan bazıları boyut kuralları gibi
    | birden fazla versiyona sahiptir. Bu mesajları burada isteğe bağlı
    | olarak değiştirebilirsiniz.
    |
    */

    'accepted' => ':attribute alanı kabul edilmelidir.',
    'accepted_if' => ':attribute alanı, :other :value olduğunda kabul edilmelidir.',
    'active_url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'after' => ':attribute alanı, :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute alanı, :date tarihinden sonra veya ona eşit bir tarih olmalıdır.',
    'alpha' => ':attribute alanı sadece harfler içermelidir.',
    'alpha_dash' => ':attribute alanı sadece harfler, sayılar, çizgiler ve alt çizgiler içermelidir.',
    'alpha_num' => ':attribute alanı sadece harfler ve sayılar içermelidir.',
    'array' => ':attribute alanı bir dizi olmalıdır.',
    'ascii' => ':attribute alanı yalnızca tek bayt alfanümerik karakterler ve semboller içermelidir.',
    'before' => ':attribute alanı, :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute alanı, :date tarihinden önce veya ona eşit bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute alanı, :min ve :max öğeleri arasında olmalıdır.',
        'file' => ':attribute alanı, :min ve :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute alanı, :min ve :max arasında olmalıdır.',
        'string' => ':attribute alanı, :min ve :max karakterler arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı izin verilmeyen bir değer içeriyor.',
    'confirmed' => ':attribute alanının teyidi eşleşmiyor.',
    'current_password' => 'Parola yanlış.',
    'date' => ':attribute alanı geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute alanı, :date ile eşit bir tarih olmalıdır.',
    'date_format' => ':attribute alanı, :format formatı ile eşleşmelidir.',
    'decimal' => ':attribute alanı, :decimal ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute alanı reddedilmelidir.',
    'declined_if' => ':attribute alanı, :other :value olduğunda reddedilmelidir.',
    'different' => ':attribute alanı ile :other alanı farklı olmalıdır.',
    'digits' => ':attribute alanı :digits rakam olmalıdır.',
    'digits_between' => ':attribute alanı, :min ve :max rakamları arasında olmalıdır.',
    'dimensions' => ':attribute alanının resim boyutları geçersiz.',
    'distinct' => ':attribute alanında yinelenen bir değer var.',
    'doesnt_end_with' => ':attribute alanı aşağıdakilerden biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute alanı aşağıdakilerden biriyle başlamamalıdır: :values.',
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute alanı aşağıdakilerden biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'file' => ':attribute alanı bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değer içermelidir.',
    'gt' => [
        'array' => ':attribute alanı :value öğeden fazla olmalıdır.',
        'file' => ':attribute alanı :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute alanı :value sayısından büyük olmalıdır.',
        'string' => ':attribute alanı :value karakterden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute alanı :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute alanı :value veya daha fazla kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :value veya daha büyük bir sayı olmalıdır.',
        'string' => ':attribute alanı :value veya daha fazla karakter içermelidir.',
    ],
    'image' => ':attribute alanı bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde bulunmuyor.',
    'integer' => ':attribute alanı bir tamsayı olmalıdır.',
    'ip' => ':attribute alanı geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute alanı geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute alanı geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute alanı geçerli bir JSON stringi olmalıdır.',
    'lt' => [
        'array' => ':attribute alanı :value öğeden az olmalıdır.',
        'file' => ':attribute alanı :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute alanı :value sayısından küçük olmalıdır.',
        'string' => ':attribute alanı :value karakterden küçük olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute alanı :value veya daha az öğe içermelidir.',
        'file' => ':attribute alanı :value veya daha az kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :value veya daha küçük bir sayı olmalıdır.',
        'string' => ':attribute alanı :value veya daha az karakter içermelidir.',
    ],
    'mac_address' => ':attribute alanı geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute alanı :max öğeden daha fazla olmamalıdır.',
        'file' => ':attribute alanı :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute alanı :max sayısından büyük olmamalıdır.',
        'string' => ':attribute alanı :max karakterden büyük olmamalıdır.',
    ],
    'mimes' => ':attribute alanı :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute alanı :values MIME türünde bir dosya olmalıdır.',
    'min' => [
        'array' => ':attribute alanı en az :min öğe içermelidir.',
        'file' => ':attribute alanı en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en az :min olmalıdır.',
        'string' => ':attribute alanı en az :min karakter olmalıdır.',
    ],
    'multiple_of' => ':attribute alanı :value\'nin katları olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute alanının formatı geçersiz.',
    'numeric' => ':attribute alanı bir sayı olmalıdır.',
    'password' => 'Parola yanlış.',
    'present' => ':attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanına izin verilmemektedir.',
    'prohibited_if' => ':other :value olduğunda :attribute alanına izin verilmemektedir.',
    'prohibited_unless' => ':attribute alanı, :other :values içinde olmadıkça yasaktır.',
    'prohibits' => ':attribute alanı, :other alanının bulunmasını yasaklar.',
    'regex' => ':attribute alanının biçimi geçersizdir.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı, :values için girişler içermelidir.',
    'required_if' => ':other :value olduğunda :attribute alanı gereklidir.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute alanı gereklidir.',
    'required_unless' => ':attribute alanı, :other :values içinde olmadıkça gereklidir.',
    'required_with' => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_with_all' => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_without' => ':values mevcut olmadığında :attribute alanı gereklidir.',
    'required_without_all' => ':values hiçbiri mevcut olmadığında :attribute alanı gereklidir.',
    'same' => ':attribute alanı, :other ile eşleşmelidir.',
    'size' => [
        'array' => ':attribute alanı :size öğe içermelidir.',
        'file' => ':attribute alanı :size kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :size olmalıdır.',
        'string' => ':attribute alanı :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute alanı, şu değerlerden biriyle başlamalıdır: :values.',
    'string' => ':attribute alanı bir dize olmalıdır.',
    'timezone' => ':attribute alanı geçerli bir zaman dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => ':attribute alanı büyük harf olmalıdır.',
    'url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'ulid' => ':attribute alanı geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute alanı geçerli bir UUID olmalıdır.',


    /*
|--------------------------------------------------------------------------
| Özel Doğrulama Dil Satırları
|--------------------------------------------------------------------------
|
| Burada, "attribute.rule" söz dizimini kullanarak özellikler için özel doğrulama
| mesajları belirleyebilirsiniz. Bu, belirli bir özellik kuralı için
| hızlıca özel bir dil satırı belirtmeyi kolaylaştırır.
|
*/

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'özel-mesaj',
        ],
        'settings' => [
            'json_string_invalid' => 'Ayarlar geçersiz bir format içeriyor.',
            'json_string_characters' => 'Ayarlar geçersiz karakterler içeriyor.',
            'json_string_key_length' => 'Ayar parametresi anahtarları 64 karakterden uzun olamaz.',
            'json_string_value_length' => 'Ayar parametresi değerleri 256 karakterden uzun olamaz.',
        ],
        'string' => ':attribute alanı bir dize olmalıdır.',
        'username' => ':attribute alanı yalnızca harfler, sayılar, nokta ve alt çizgiler içerebilir.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Özel Doğrulama Nitelikleri
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, yer tutucumuz olan özellik
    | ile daha okuyucu dostu bir şeyi değiştirmek için kullanılır, örneğin
    | "email" yerine "E-Mail Adresi". Bu sadece mesajımızı
    | daha ifade edilebilir hale getirmemize yardımcı olur.
    |
    */

    'attributes' => [
        'user' => 'Kullanıcı',
        'name' => 'İsim',
        'email' => 'E-Mail Adresi',
        'password' => 'Parola',
        'title' => 'Ünvan',
        'username' => 'Kullanıcı Adı',
        'first_name' => 'Ad',
        'last_name' => 'Soyad',
        'password_confirmation' => 'Parola Doğrulama',
        'username' => 'Kullanıcı Adı',
        'email' => 'E-Posta',
        'password' => 'Parola',
        'title.id' => 'Ünvan',
        'g_recaptcha_response' => 'Google Recaptcha',
        'secret' => 'Doğrulama Kodu',
        'deposit_server' => 'Yatırım Sunucusu',
        'is_active' => 'Status',
        'per_page' => 'Sayfa Başına Kayıt',
        'page' => 'Sayfa',
        'form_domain' => 'Form Adresi',
        'method' => [
            'tfa' => 'Doğrulama Yöntemi',
            'method' => 'Ödeme Yöntemi',
        ],
        'purpose' => [
            'tfa' => 'Doğrulama Amacı',
        ],
        'settings' => 'Ayarlar',
        'setting' => 'Ayar',
        'withdraw_status' => 'Para Çekme Durumu',
        'deposit_status' => 'Para Yatırma Durumu',
        'worker_status' => 'Çalışma Durumu',
        'panel_domain' => 'Panel Adresi',
        'provider' => 'Altyapı',
        'type' => [
            'provider_gateway' => 'Geri Bildirim Adres Türü',
        ],
        'deposit_callback_url' => 'Para Yatırma Geri Bildirim Adresi',
        'withdraw_callback_url' => 'Para Çekme Geri Bildirim Adresi',
        'callback_timeout' => 'Geri Bildirim İletim Süresi',
        'can_deposit' => 'Para Yatırabilir',
        'can_withdraw' => 'Para Çekebilir',
        'provider_gateway' => 'Geri Bildirim Adresi',
        'permission_uuid' => 'Yetki',
        'role' => 'Rol',
        'description' => 'Açıklama',
        'value' => 'Değer',
        'expired_at' => 'Bitiş Tarihi',
        'site' => 'Site',
        'deposit_commission' => 'Para Yatırma Komisyonu',
        'withdraw_commission' => 'Para Çekme Komisyonu',
        'has_redirect' => 'Yönlendirme Aktif',
        'redirect_url' => 'Yönlendirme Adresi',
        'redirect_mode' => 'Yönlendirme Modu',
        'can_api_withdraw' => 'API Para Çekebilir',
        'can_cp_withdraw' => 'Panel Para Çekebilir',
        'can_login' => 'Giriş Yapabilir',
        'can_over_balance' => 'Bakiye Üstü Çekebilir',
        'key' => [
            'site' => 'Site Anahtarı',
        ],
        'deposit_fullname_min_score' => 'Para Yatırma Ad Soyad Minimum Skor',
        'user_group' => 'Kullanıcı Grubu',
    ],

];
