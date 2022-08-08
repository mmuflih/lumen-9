<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Console\Commands;

use App\Helpers\Path;
use App\Helpers\Slug;
use App\Jobs\SetOutletLocation;
use App\Models\City;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;

class TestCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cmd:test';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $job = new SetOutletLocation(18);
        // $job = new SetOutletLocation("ChIJxWtbvYdXei4RcU9o09Q_ciE");
        app(Dispatcher::class)->dispatch($job);
    }
}
