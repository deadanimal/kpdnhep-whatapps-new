<?php

namespace App\Console\Commands;

use App\Models\Complainer;
use App\Models\People\People;
use App\Models\TableUpdateLog;
use App\Repositories\Feedback\Email\EmailFetcherRepository;
use Illuminate\Console\Command;

class fetchEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:fetchEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all new email from mail server';

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
     * @return bool
     */
    public function handle()
    {
        EmailFetcherRepository::fetch();
        return true;
    }
}
