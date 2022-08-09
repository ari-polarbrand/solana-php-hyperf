<?php

namespace He426100\SolanaPhpSdk\Programs;

use Hyperf\Utils\Arr;
use He426100\SolanaPhpSdk\Program;
use He426100\SolanaPhpSdk\PublicKey;
use He426100\SolanaPhpSdk\Util\AccountMeta;
use He426100\SolanaPhpSdk\TransactionInstruction;

class SplTokenProgram extends Program
{
    public const SOLANA_TOKEN_PROGRAM_ID = 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA';
    public const ASSOCIATED_TOKEN_PROGRAM_ID = 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL';
    public const SYSVAR_RENT_PUBKEY = 'SysvarRent111111111111111111111111111111111';

    /**
     * @param string $pubKey
     * @return mixed
     */
    public function getTokenAccountsByOwner(string $pubKey)
    {
        return $this->client->call('getTokenAccountsByOwner', [
            $pubKey,
            [
                'programId' => self::SOLANA_TOKEN_PROGRAM_ID,
            ],
            [
                'encoding' => 'jsonParsed',
            ],
        ]);
    }

    /**
     * @param PublicKey $mint
     * @param PublicKey $owner
     * @return PublicKey
     */
    public static function getAssociatedTokenAccount(PublicKey $mint, PublicKey $owner): PublicKey
    {
        $programId = self::programId();
        $seed = [
            $owner->toBuffer(),
            $programId->toBuffer(),
            $mint->toBuffer()
        ];
        return Arr::first(PublicKey::findProgramAddress($seed, new PublicKey(self::ASSOCIATED_TOKEN_PROGRAM_ID)));
    }

    /**
     * Public key that identifies the System program
     *
     * @return PublicKey
     */
    static function programId(): PublicKey
    {
        return new PublicKey(self::SOLANA_TOKEN_PROGRAM_ID);
    }

    /**
     * Generate a token instruction that transfers token from one account to another
     * @param PublicKey $source
     * @param PublicKey $destination
     * @param PublicKey $owner
     * @param PublicKey $mint
     * @param int $amount
     * @param int $decimals
     * @return TransactionInstruction
     */
    static public function transfer(
        PublicKey $source,
        PublicKey $destination,
        PublicKey $owner,
        PublicKey $mint,
        int    $amount,
        int    $decimals
    ): TransactionInstruction
    {
        /**
         * TokenInstructions :
         *   Transfer = 3,
         *   TransferChecked = 12,
         */
        $data = [
            // u8
            ...unpack("C*", pack("C", 12)),  // token instruction index
            // u64
            ...unpack("C*", pack("P", $amount)), // amount without decimals
            // u8
            ...unpack("C*", pack("C", $decimals)),   // token decimals
        ];
        $keys = [
            new AccountMeta($source, false, true), // source account address
            new AccountMeta($mint, false, false), // mint token address
            new AccountMeta($destination, false, true), // destination account address, if not exist need call
            new AccountMeta($owner, true, true), // singer address, wallet who will be sing tx
        ];

        return new TransactionInstruction(
            self::programId(),
            $keys,
            $data
        );
    }

    /**
     * @param PublicKey $payer
     * @param PublicKey $associatedToken
     * @param PublicKey $owner
     * @param PublicKey $mint
     * @param string $programId
     * @param string $associatedTokenProgramId
     * @return TransactionInstruction
     */
    static public function createAssociatedTokenAccount(
        PublicKey $payer, // tx payer address. it address wallet whose using for sining transaction
        PublicKey $associatedToken, // generated token address, for get call function "getAssociatedTokenAddress"
        PublicKey $owner, //  wallet address for which you want to create associated token wallet
        PublicKey $mint,   // Token mint address
        string $programId = self::SOLANA_TOKEN_PROGRAM_ID,
        string $associatedTokenProgramId = self::ASSOCIATED_TOKEN_PROGRAM_ID
    ): TransactionInstruction
    {
        $data = [];
        $keys = [
            new AccountMeta($payer, true, true),
            new AccountMeta($associatedToken, false, true),
            new AccountMeta($owner, false, false),
            new AccountMeta($mint, false, false),
            new AccountMeta(SystemProgram::programId(), false, false),
            new AccountMeta(new PublicKey($programId), false, false),
            new AccountMeta(new PublicKey(self::SYSVAR_RENT_PUBKEY), false, false),
        ];

        return new TransactionInstruction(
            new PublicKey($associatedTokenProgramId),
            $keys,
            $data
        );
    }
}
