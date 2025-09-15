<?php

declare(strict_types=1);

it('serves offline page', function () {
    $response = $this->get('/offline.html');
    $response->assertSuccessful();
    $response->assertSee('You\'re offline');
});


