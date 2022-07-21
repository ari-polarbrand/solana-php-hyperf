# Hyperf solana-php-sdk

移植自 solana-php-sdk 组件（[solana-php-sdk](https://github.com/verze-app/solana-php-sdk )）


Simple PHP SDK for Solana.

## Installation

You can install the package via composer:

```bash
composer require he426100/solana-php-sdk
```

## Usage

### Using the Solana simple client

You can use the `Connection` class for convenient access to API methods. Some are defined in the code:

```php
use He426100\SolanaPhpSdk\Connection;
use He426100\SolanaPhpSdk\SolanaRpcClient;

// Using a defined method
$sdk = new Connection(new SolanaRpcClient(SolanaRpcClient::MAINNET_ENDPOINT));
$accountInfo = $sdk->getAccountInfo('4fYNw3dojWmQ4dXtSGE9epjRGy9pFSx62YypT7avPYvA');
var_dump($accountInfo);
```

For all the possible methods, see the [API documentation](https://docs.solana.com/developing/clients/jsonrpc-api).

### Directly using the RPC client

The `Connection` class is just a light convenience layer on top of the RPC client. You can, if you want, use the client directly, which allows you to work with the full `Response` object:

```php
use He426100\SolanaPhpSdk\SolanaRpcClient;

$client = new SolanaRpcClient(SolanaRpcClient::MAINNET_ENDPOINT);
$accountInfoResponse = $client->call('getAccountInfo', ['4fYNw3dojWmQ4dXtSGE9epjRGy9pFSx62YypT7avPYvA']);
$accountInfoBody = $accountInfoResponse->json();
$accountInfoStatusCode = $accountInfoResponse->getStatusCode();
``````

### Transactions

Here is working example of sending a transfer instruction to the Solana blockchain:

```php
$client = new SolanaRpcClient(SolanaRpcClient::DEVNET_ENDPOINT);
$connection = new Connection($client);
$fromPublicKey = KeyPair::fromSecretKey([...]);
$toPublicKey = new PublicKey('J3dxNj7nDRRqRRXuEMynDG57DkZK4jYRuv3Garmb1i99');
$instruction = SystemProgram::transfer(
    $fromPublicKey->getPublicKey(),
    $toPublicKey,
    6
);

$transaction = new Transaction(null, null, $fromPublicKey->getPublicKey());
$transaction->add($instruction);

$txHash = $connection->sendTransaction($transaction, $fromPublicKey);
```

Note: This project is in alpha, the code to generate instructions is still being worked on `$instruction = SystemProgram::abc()`
