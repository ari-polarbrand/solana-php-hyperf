<?php

namespace He426100\SolanaPhpSdk\Util;

use He426100\SolanaPhpSdk\PublicKey;

class SignaturePubkeyPair implements HasPublicKey
{
    protected PublicKey $publicKey;
    public ?string $signature;

    public function __construct(PublicKey $publicKey, ?string $signature = null)
    {
        $this->publicKey = $publicKey;
        $this->signature = $signature;
    }

    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }
}
