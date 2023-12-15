<?php

namespace DrH\Jenga\Tests\Database;

use DrH\Jenga\Models\JengaIpn;

it('can run migrations', function () {

    JengaIpn::create([
        "customer_name" => "John Doe",
        "customer_mobile_number" => "254712345678",
        "customer_reference" => "071648816466242",

        "transaction_date" => "2023-10-11 14:15:20",
        "transaction_reference" => "328411183176",
        "transaction_payment_mode" => "PWE",
        "transaction_amount" => 150,
        "transaction_bill_number" => "INVZCF",
        "transaction_served_by" => "EQ",
        "transaction_additional_info" => "CARD",
        "transaction_order_amount" => 150,
        "transaction_service_charge" => 5.25,
        "transaction_status" => "SUCCESS",
        "transaction_remarks" => "00:Approved",

        "bank_reference" => "328411183176",
        "bank_transaction_type" => "C",
        "bank_account" => null
    ]);

    $ipn = JengaIpn::first();

    expect($ipn->transaction_reference)->toBe('328411183176');
});
