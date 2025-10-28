<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enum Language Lines
    |--------------------------------------------------------------------------
    |
    |
    */

    'planned-competition' => [
        'status' => [
            'passive' => 'Pasif',
            'active' => 'Aktif'
        ]
    ],
    'competition' => [
        'status' => [
            'passive' => 'Pasif',
            'preparing' => 'Hazırlanıyor',
            'ready' => 'Hazır',
            'active' => 'Aktif',
            'completed' => 'Tamamlandı',
            'canceled' => 'İptal Edildi',
            'waiting_results' => 'Sonuçlar Bekleniyor',
            'results_started' => 'Sonuçlar Başladı',
        ]
    ],
    'competition-ticket' => [
        'type' => [
            'user' => 'Kullanıcı',
            'bot' => 'Kullanıcı',
        ]
    ],
    'transaction' => [
        'purpose' => [
            'in' => 'Yatırım',
            'out' => 'Çekim'
        ],
        'type' => [
            'competition' => 'Yarışma',
            'method' => 'Transfer',
            'bonus' => 'Bonus',
        ],
        'status' => [
            'completed' => 'Tamamlandı',
            'pending' => 'Beklemede',
            'processing' => 'İşleniyor',
            'canceled' => 'İptal edildi',
            'reviewing' => 'İnceleniyor'
        ],
        'gateway' => [
            'type' => [
                'production' => 'Canlı',
                'testing' => 'Test'
            ]
        ]
    ],
    'gateway' => [
        'type' => [
            'production' => 'Canlı',
            'testing' => 'Test'
        ]
    ],
    'user' => [
        'tfa_method' => [
            'none' => 'Yok',
            'authenticator' => 'Google Authenticator',
            'mail' => 'E-posta'
        ],
        'language' => [
            'tr' => 'Türkçe',
            'en' => 'İngilizce'
        ],
        'type' => [
            'user' => 'Kullanıcı',
            'admin' => 'Yönetici'
        ]
    ],
    'site' => [
        'redirect_mode' => [
            'auto' => 'Otomatik',
            'manuel' => 'Manuel'
        ]
    ],
    'player-blacklist' => [
        'purpose' => [
            'deposit' => 'Yatırım',
            'withdraw' => 'Çekim',
            'both' => 'Her ikisi'
        ]
    ],
    'method' => [
        'type' => [
            'virtual_wallet' => 'Sanal Cüzdan',
            'bank_transfer' => 'Banka Transferi',
            'crypto' => 'Kripto'
        ]
    ],
    'bonus' => [
        'action' => [
            'welcome' => 'Hoşgeldin Bonusu'
        ]
    ]

];
