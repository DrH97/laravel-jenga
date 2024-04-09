<?php

use DrH\Jenga\Events\JengaBillIpnEvent;
use DrH\Jenga\Events\JengaIpnEvent;
use DrH\Jenga\Models\JengaBillIpn;
use DrH\Jenga\Models\JengaIpn;
use Illuminate\Support\Facades\Config;
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


it('handles duplicate ipn', function () use ($ipnUrl) {
    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    assertDatabaseCount((new JengaIpn())->getTable(), 1);

    Event::assertDispatched(JengaIpnEvent::class, 1);

});

$billIpnUrl = 'jenga/callbacks/bill-ipn';

it('handles successful bill ipn', function () use ($billIpnUrl) {
    Config::set('jenga.bill.username', 'Equity');
    Config::set('jenga.bill.password', '3pn!Ty@zoi9');

    postJson($billIpnUrl, $this->mockResponses['bill_ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['bill_ipn']['response']);

    assertDatabaseCount((new JengaBillIpn())->getTable(), 1);

    Event::assertDispatched(JengaBillIpnEvent::class, 1);
});


it('handles duplicate bill ipn', function () use ($billIpnUrl) {
    Config::set('jenga.bill.username', 'Equity');
    Config::set('jenga.bill.password', '3pn!Ty@zoi9');

    postJson($billIpnUrl, $this->mockResponses['bill_ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['bill_ipn']['response']);

    postJson($billIpnUrl, $this->mockResponses['bill_ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['bill_ipn']['response']);

    assertDatabaseCount((new JengaBillIpn())->getTable(), 1);

    Event::assertDispatched(JengaBillIpnEvent::class, 1);

});

it('handles invalid bill ipn - body missing', function () use ($billIpnUrl) {
    postJson($billIpnUrl, $this->mockResponses['bill_ipn']['request_invalid_body'])
        ->assertUnprocessable()
        ->assertJson($this->mockResponses['bill_ipn']['response_invalid']);

    assertDatabaseCount((new JengaBillIpn())->getTable(), 0);

    Event::assertNotDispatched(JengaBillIpnEvent::class);

});

it('handles invalid bill ipn - credentials', function () use ($billIpnUrl) {
    Config::set('jenga.bill.username', 'Equity');
    Config::set('jenga.bill.password', '3pn!Ty@zoi9');

    postJson($billIpnUrl, $this->mockResponses['bill_ipn']['request_invalid_credentials'])
        ->assertUnprocessable()
        ->assertJson($this->mockResponses['bill_ipn']['response_invalid']);

    assertDatabaseCount((new JengaBillIpn())->getTable(), 0);

    Event::assertNotDispatched(JengaBillIpnEvent::class);

});
