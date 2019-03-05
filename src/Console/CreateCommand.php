<?php

namespace PSV\Widgets\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PSV\Widgets\Redirect;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirect:create 
                            {source : source url (required)}
                            {destination : destination url (required)}
                            {code? : server redirect code (optional)}
                            {expired? : redirect expired time, format: Y-m-d H:i:s (optional)}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create redirect';

    /**
     * Allow redirect server codes
     *
     * @var array
     */
    protected $codes = [
        301, 302
    ];

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
        $destination = $this->argument('destination');
        $code = $this->argument('code')?? config("redirect.default");
        $expired = $this->argument('expired');

        $this->validate($source, $destination, $code, $expired);

        Redirect::create([
            "source" => $source,
            "destination" => $destination,
            "code" => $code,
            "expired_at" => $expired,
        ]);

        $this->info('Redirect from "'.$source.'" to "'.$destination.'" successfully created');
    }

    /**
     * Validate input data
     *
     * @return mixed
     */
    public function validate(&$source, &$destination, &$code, &$expired) {
        if (mb_substr($source, 0, 1) == "/")
            $source = mb_substr($source, 1);

        if (mb_strlen($source) > 512) {
            $this->error('The "source" may not be greater than 512 characters.');
            exit;
        }

        if (mb_substr($destination, 0, 1) == "/")
            $destination = mb_substr($destination, 1);

        if (mb_strlen($destination) > 512) {
            $this->error('The "destination" may not be greater than 512 characters.');
            exit;
        }

        if (!in_array($code, $this->codes)) {
            $this->error('Parameter "code" must be one of the values: ' . implode(", ", $this->codes));
            exit;
        }

        try {
            if (!is_null($expired))
                $expired = Carbon::createFromFormat('Y-m-d H:i:s', $expired);

            if ($expired < Carbon::now()) {
                $this->error('The "expired" must be a date after "'.Carbon::now()->format("Y-m-d H:i:s").'".');
                exit;
            }
        }
        catch (\InvalidArgumentException $exception) {
            $this->error('Parameter "expired" must be a date in the format "Y-m-d H:i:s"');
            exit;
        }

        try {
            $redirect = Redirect::whereSource($source)->firstOrFail();
            $this->error('The redirect from the "'.$source.'" already exists and is redirected to "'.$redirect->destination.'". If you want to delete this redirect, use the command:');
            $this->error('php artisan redirect:remove "'.$source.'"');
            exit;
        }
        catch (ModelNotFoundException $exception) {}

    }
}
