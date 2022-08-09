<?php

namespace He426100\SolanaPhpSdk\Accounts;

use He426100\SolanaPhpSdk\Borsh;

final class Collection
{
    use Borsh\BorshDeserializable;

    public const SCHEMA = [
        self::class => [
            'kind' => 'struct',
            'fields' => [
                ['verified', 'u8'],
                ['key', 'pubkeyAsString'],
            ],
        ],
    ];
}
