<?php

namespace He426100\SolanaPhpSdk\Accounts;

use He426100\SolanaPhpSdk\Borsh;

final class Uses
{
    use Borsh\BorshDeserializable;

    public const SCHEMA = [
        self::class => [
            'kind' => 'struct',
            'fields' => [
                ['useMethod', 'u8'],
                ['remaining', 'u64'],
                ['total', 'u64']
            ],
        ],
    ];
}
