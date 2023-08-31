<?php

namespace App\Controllers;

class TerraformController
{
    private const TERRAFORM_PATH = '.shim/infra';
    public function terraform(string $command): void
    {
        $this->validateCommand($command);

        if ($command == 'init') {
            $this->terraformInit();
        } else {
            $this->terraformPlanAndApply($command);
        }
    }

    private function validateCommand(string $command): void {
        $allowedCommandsArr = ['init', 'plan', 'apply'];

        if (!in_array($command, $allowedCommandsArr, true)) {
            throw new \InvalidArgumentException('Invalid terraform command provided.');
        }
    }

    private function terraformInit(): void
    {
       $this->executeCommand('init');
    }

    private function terraformPlanAndApply(string $command): void
    {
        do {
          $this->executeCommand($command);
        } while (!file_exists('config.yaml'));
    }

    private function executeCommand(string $command): void {
        $fullCommand = sprintf('terraform -chdir=%s %s', self::TERRAFORM_PATH, $command);
        shell_exec($fullCommand);
    }
}
