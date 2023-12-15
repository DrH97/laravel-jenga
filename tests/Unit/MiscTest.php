<?php

namespace DrH\Jenga\Tests\Unit;

use GuzzleHttp\Psr7\Response;

test('confirm environment is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
});


test('logger', function () {
    expect(shouldJengaLog())->toBe(false);

    config()->set('jenga.logging.enabled', true);

    expect(shouldJengaLog())->toBe(true);

    config()->set('jenga.logging.channels', ['single']);

    jengaLogError('test Logging Error');

    config()->set('jenga.logging.channels', [
        [
            'driver' => 'single',
            'path' => '/dev/null',
        ]
    ]);

    jengaLogInfo('test Logging Info');

    jengaLog('warning', 'test Logging Warning');

});

test('parsing guzzle response', function () {
    expect(parseGuzzleResponse(new Response(headers: ['set-cookie' => true, 'asd' => 1])))->toBeArray()->not->toHaveKey('body')
        ->and(parseGuzzleResponse(new Response(), true))->toBeArray()->toHaveKey('body')
        ->and(parseGuzzleResponse(new Response(400)))->toBeArray()->toHaveKey('status_code', 400);
});
