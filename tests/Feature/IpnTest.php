<?php

use DrH\Jenga\Events\JengaIpnEvent;
use DrH\Jenga\Models\JengaIpn;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;


$ipnUrl = 'jenga/callbacks/ipn';

it('handles successful ipn', function () use ($ipnUrl) {
    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    assertDatabaseCount((new JengaIpn())->getTable(), 1);

    Event::assertDispatched(JengaIpnEvent::class, 1);
});

//
//it('handles duplicate ipn', function () use ($ipnUrl) {
//    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
//        ->assertSuccessful()
//        ->assertJson($this->mockResponses['ipn']['response']);
//
//    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
//        ->assertSuccessful()
//        ->assertJson($this->mockResponses['ipn']['response']);
//
//    assertDatabaseCount((new JengaIpn())->getTable(), 1);
//
//    Event::assertDispatched(JengaIpnEvent::class, 1);
//
//});
