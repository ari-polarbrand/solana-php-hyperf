<?php

namespace He426100\SolanaPhpSdk\Util;

interface HasSecretKey
{
    public function getSecretKey(): Buffer;
}
