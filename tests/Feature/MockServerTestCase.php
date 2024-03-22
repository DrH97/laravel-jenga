<?php

namespace DrH\Jenga\Tests\Feature;

use DrH\Jenga\Library\BaseClient;
use DrH\Jenga\Library\Core;
use DrH\Jenga\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class MockServerTestCase extends TestCase
{
    use RefreshDatabase;

//    protected BaseClient $baseClient;

//    protected MockHandler $mock;

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        Config::set('jenga.key', 'somethinggoeshere');
//        Config::set('jenga.secret', 'somethinggoeshere');
//
//        $this->mock = new MockHandler();
//
//        $handlerStack = HandlerStack::create($this->mock);
//        $this->baseClient = new BaseClient(new Client(['handler' => $handlerStack]));
//
//        $this->core = new Core($this->baseClient);
//    }

    protected array $mockResponses = [
        'ipn' => [
            'request' => [
                "callbackType" => "IPN",
                "customer" => [
                    "name" => "John Doe",
                    "mobileNumber" => "254712345678",
                    "reference" => "071648816466242"
                ],
                "transaction" => [
                    "date" => "2023-10-11 14:15:20",
                    "reference" => "328411183176",
                    "paymentMode" => "PWE",
                    "amount" => 150,
                    "billNumber" => "INVZCF",
                    "servedBy" => "EQ",
                    "additionalInfo" => "CARD",
                    "orderAmount" => 150,
                    "serviceCharge" => 5.25,
                    "status" => "SUCCESS",
                    "remarks" => "00:Approved"
                ],
                "bank" => [
                    "reference" => "328411183176",
                    "transactionType" => "C",
                    "account" => null
                ]
            ],
            'response' => [
                'reference' => '328411183176',
                'statusCode' => '0',
                'statusMessage' => 'IPN received'
            ]
        ]
    ];
}
