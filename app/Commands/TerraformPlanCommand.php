<?php

namespace App\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use App\Controllers\TerraformController;

class TerraformPlanCommand extends Command
{
    protected $signature = "plan";

    protected $description = "Runs Terraform plan based off the config.yaml file";

    private const TERRAFORM_INIT = 'init';
    private const TERRAFORM_PLAN = 'plan';

    public function handle(): void
    {
        if (!Storage::exists('config.yaml')) {
            $this->error('config.yaml not provided');
            Log::warning("Config.yaml not provided");
            return;
        }

        $this->configureShim();
        $this->runTerraform(self::TERRAFORM_INIT);
        $this->runTerraform(self::TERRAFORM_PLAN);
    }

    private function configureShim(): void
    {
        $this->task('Configuring shim', function () {
            if (Storage::exists('.shim/config.yaml')) {
                Storage::delete('.shim/config.yaml');
            }

            Storage::copy('config.yaml', '.shim/config.yaml');
        });
    }

    private function runTerraform(string $command): void
    {
        $terraformController = new TerraformController();

        $this->task('Running Terraform ' . ucfirst($command), function () use ($terraformController, $command) {
            $terraformController->terraform($command);
        });
    }
}
