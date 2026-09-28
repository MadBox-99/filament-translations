<?php

declare(strict_types=1);

namespace Madbox99\FilamentTranslations\Tests\Fixtures;

use Illuminate\Contracts\Support\Htmlable;
use Madbox99\FilamentTranslations\Pages\ManageTranslations;
use Override;

/**
 * Apps subclass the page with Filament's own signatures; this must keep compiling.
 */
class CustomManageTranslations extends ManageTranslations
{
    #[Override]
    public function getTitle(): string|Htmlable
    {
        return 'Custom';
    }
}
