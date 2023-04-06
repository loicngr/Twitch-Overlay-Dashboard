<?php

namespace App\Utils\Constants;

class Groups
{
    // -----------
    //   MANAGER
    // ----------

    /** @var string */
    final public const GROUP_MANAGER_READ_ITEM = 'manager:read:item';

    /** @var string */
    final public const GROUP_MANAGER_CREATE_ITEM = 'manager:create:item';

    /** @var string */
    final public const GROUP_MANAGER_UPDATE_ITEM = 'manager:update:item';

    /** @var string */
    final public const GROUP_MANAGER_READ_COLLECTION = 'manager:read:collection';

    // --------
    //   GAME
    // --------

    /** @var string */
    final public const GROUP_GAME_READ_ITEM = 'game:read:item';

    /** @var string */
    final public const GROUP_GAME_CREATE_ITEM = 'game:create:item';

    /** @var string */
    final public const GROUP_GAME_UPDATE_ITEM = 'game:update:item';

    /** @var string */
    final public const GROUP_GAME_READ_COLLECTION = 'game:read:collection';

    // --------
    //   USER
    // --------

    /** @var string */
    final public const GROUP_USER_READ_ITEM = 'user:read:item';

    /** @var string */
    final public const GROUP_USER_CREATE_ITEM = 'user:create:item';

    /** @var string */
    final public const GROUP_USER_UPDATE_ITEM = 'user:update:item';

    /** @var string */
    final public const GROUP_USER_READ_COLLECTION = 'user:read:collection';

    // --------
    //  STREAM
    // --------

    /** @var string */
    final public const GROUP_STREAM_READ_ITEM = 'stream:read:item';

    /** @var string */
    final public const GROUP_STREAM_CREATE_ITEM = 'stream:create:item';

    /** @var string */
    final public const GROUP_STREAM_UPDATE_ITEM = 'stream:update:item';

    /** @var string */
    final public const GROUP_STREAM_READ_COLLECTION = 'stream:read:collection';
}
