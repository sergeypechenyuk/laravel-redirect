<?php

namespace PSV\Widgets\Console;

use Illuminate\Console\Command;
use PSV\Widgets\Redirect;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirect:list 
                            {source? : source url}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of redirects';

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
        if (is_null($source))
            $redirect = Redirect::orderBy("source", "asc")->orderBy("destination", "asc")->get();
        else
            $redirect = Redirect::where("source", "like", $source."%")->orderBy("source", "asc")->orderBy("destination", "asc")->get();

        $redirect = $redirect->transform(function($r) {
            return [
                'source' => $r->source,
                'destination' => $r->destination,
                'code' => $r->code,
                'expired' => $r->expired_at,
            ];
        });

        $this->table(
            [
                'Source', 'Destination', 'Code', 'Exprired'
            ],
            $redirect
        );

    }
}
