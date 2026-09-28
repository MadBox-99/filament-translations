<?php

declare(strict_types=1);

return [
    'navigation_label' => 'Fordítások',
    'title' => 'Fordítások kezelése',
    'section' => 'Nyelvi fájl',
    'fields' => [
        'locale' => 'Nyelv',
        'translations' => 'Fordítások',
        'key' => 'Kulcs',
        'value' => 'Fordítás',
        'new_locale' => 'Nyelvkód',
        'new_locale_placeholder' => 'pl. en, de, fr',
    ],
    'actions' => [
        'save' => 'Mentés',
        'add_locale' => 'Nyelv hozzáadása',
    ],
    'notifications' => [
        'saved' => 'A fordítások elmentve.',
        'locale_exists' => 'Ez a nyelv már létezik.',
        'locale_added' => 'A(z) „:locale” nyelv hozzáadva.',
    ],
];
