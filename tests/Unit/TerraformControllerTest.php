<?php

use App\Controllers\TerraformController;
use PHPUnit\Framework\TestCase;

class TerraformControllerTest extends TestCase {

    public function testTerraformInit(): void {
        $controller = new TerraformController();
        $this->hasUnexpectedOutput(null);

        $this->mockShellExec('terraform -chdir=.shim/infra init', null);
        $controller->terraform('init');
    }

    public function testPlanAndApply(): void {
        $controller = new TerraformController();
        touch('config.yaml');

        $this->mockShellExec('terraform -chdir=.shim/infra init', null);
        $controller->terraform('plan');
        $this->assertThat(file_exists('config.yaml'));
        unlink('config.yaml');
    }

    private function mockShellExec(string $command, ?string $output): void
    {
        // Replace shell_exec with a mock that returns the specified output
        $function = function ($cmd) use ($command, $output) {
            $this->assertSame($command, $cmd);
            return $output;
        };
        $this->setFunctionMock('shell_exec', $function);
    }

    private function setFunctionMock(string $functionName, callable $function): void
    {
        if (function_exists($functionName)) {
            $this->setIsolation(false);
            runkit_function_redefine($functionName, '', $function);
            $this->setIsolation(true);
        }
    }
}
