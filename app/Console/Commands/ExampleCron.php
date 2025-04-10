<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

// Model

class ExampleCron extends Command
{
    protected $signature = 'ExampleCron';
    protected $description = 'Example Desc';

    public function handle()
    {
        // Code Logic Here
        // End Code
        
        echo ('Success Running Command at ' . $today);
    }
}
