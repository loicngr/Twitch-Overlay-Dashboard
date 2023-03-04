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

    /** @var string */
    final public const TWITCH_BASE_URL = 'https://id.twitch.tv';

    /** @var string */
    final public const OAUTH_TWITCH_BASE_URL = self::TWITCH_BASE_URL . '/oauth2';
}
