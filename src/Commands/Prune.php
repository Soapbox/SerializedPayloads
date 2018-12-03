<?php

namespace SoapBox\SerializedPayloads\Commands;

use Illuminate\Console\Command;
use SoapBox\SerializedPayloads\Payload;

class Prune extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serialized-payloads:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prunes old payloads.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Payload::shouldDelete()->delete();
    }
}
