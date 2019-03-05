<?php

namespace PSV\Widgets\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PSV\Widgets\Redirect;

class RemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirect:remove 
                            {source : source url (required)}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove redirect';

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
        $source = $this->argument('source');

        try {
            $redirect = Redirect::whereSource($source)->firstOrFail();
            $redirect->delete();
        }
        catch (ModelNotFoundException $exception) {
            $this->error('The redirect from the "'.$source.'" not exists.');
            exit;
        }

        $this->info('Redirect from "'.$source.'" successfully removed.');
    }
}
