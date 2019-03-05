<?php

namespace PSV\Widgets\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PSV\Widgets\Redirect;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirect:update 
                            {source : source url}
                            {destination : destination url}
                            {code? : server redirect code}
                            {expired? : redirect expired time}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update redirect
        source (required): source urld
        destination (required): destination url 
        code (optional): server redirect code
        expired (optional): redirect expired time, format: Y-m-d H:i:s';

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

        $redirect = $this->validate($source, $destination, $code, $expired);

        $redirect->update([
            "destination" => $destination,
            "code" => $code,
            "expired_at" => $expired,
        ]);

        $this->info('Redirect from "'.$source.'" to "'.$destination.'" successfully updated');
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
            return Redirect::whereSource($source)->firstOrFail();
        }
        catch (ModelNotFoundException $exception) {
            $this->error('The redirect from the "'.$source.'" not exists.');
            exit;
        }

    }
}
