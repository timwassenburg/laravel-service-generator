<?php

it('can execute make:service command', function () {
    $this->artisan('make:service Test')
        ->assertExitCode(0);
});
