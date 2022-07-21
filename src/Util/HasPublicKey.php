<?php

namespace He426100\SolanaPhpSdk\Util;

use He426100\SolanaPhpSdk\PublicKey;

interface HasPublicKey
{
    public function getPublicKey(): PublicKey;
}
