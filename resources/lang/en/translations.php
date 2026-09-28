<?php

declare(strict_types=1);

return [
    'navigation_label' => 'Translations',
    'title' => 'Manage Translations',
    'section' => 'Language File',
    'fields' => [
        'locale' => 'Language',
        'translations' => 'Translations',
        'key' => 'Key',
        'value' => 'Translation',
        'new_locale' => 'Locale Code',
        'new_locale_placeholder' => 'e.g. en, de, fr',
    ],
    'actions' => [
        'save' => 'Save',
        'add_locale' => 'Add Language',
    ],
    'notifications' => [
        'saved' => 'Translations saved.',
        'locale_exists' => 'Language already exists.',
        'locale_added' => 'Language ":locale" added.',
    ],
];
