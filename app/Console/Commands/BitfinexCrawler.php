<?php

namespace App\Console\Commands;

use App\Transaction;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class BitfinexCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitfinex:crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl data form bitfinex api';

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
     * @return mixed
     */
    public function handle()
    {
        $symbol = $this->anticipate('What is your symbol?', ['Trades']);
        $client = new Client(['base_uri' => 'https://api.bitfinex.com/v1/trades/']);
        $response = $client->request('GET', $symbol . '/?limit_trades=999');
        $responsesAsArray = \GuzzleHttp\json_decode($response->getBody());
        $bar = $this->output->createProgressBar(count($responsesAsArray));

        foreach ($responsesAsArray as $response) {
            Transaction::create([
                'amount' => $response->amount,
                'price' => $response->price,
                'type' => $response->type,
                'timestamp' => $response->timestamp,
            ]);
            $bar->advance();
        }

        $bar->finish();
        echo "\n";
        echo "\n";
        $this->info('Done.');
        echo "\n";
    }
}
