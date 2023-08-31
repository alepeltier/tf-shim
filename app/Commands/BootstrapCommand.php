<?php

namespace App\Commands;


use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Storage;


class BootstrapCommand extends Command
{
    protected $signature = "bootstrap";

    protected $description = "Run the shim. This follows the standard terraform commands format apply amd plan";

    public function handle(): void {

        if (!file_exists('.shim')) {
            $this->task('Creating directory structure', function (){
                $this->bootstrapStructure();
            });
        }
    }

    private function bootstrapStructure(): void
    {
        File::makeDirectory('.shim');
        File::makeDirectory('.shim/infra');
    }
}
