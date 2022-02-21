<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitInstance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:initInstance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instance Creates Private and Public Key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // initiate public and private keys
        // Configuration settings for the key
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        // Create the private and public key
        $res = openssl_pkey_new($config);

        // Extract the private key into $private_key
        openssl_pkey_export($res, $private_key);

        // Extract the public key into $public_key
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        // return Command::SUCCESS;
    }
}
