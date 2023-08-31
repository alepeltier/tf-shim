<?php

it('Triggers correctly on shim', function () {
   $this->artisan('shim')->assertExitCode(0);
});
