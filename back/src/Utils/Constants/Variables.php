<?php

namespace App\Utils\Constants;

class Variables
{
    /** @var string */
    final public const DATE_SERVER = 'Y-m-d';

    /** @var string */
    final public const DATE_TIME_SERVER = self::DATE_SERVER . '\T' . self::TIME_LONG;

    /** @var string */
    final public const DATE_FR = 'd/m/Y';

    /** @var string */
    final public const DATE_FR_LONG = 'd/m/Y';

    /** @var string */
    final public const TIME_LONG = 'H:i:s';

    /** @var string */
    final public const TIME_FR = 'H\hi';
}
