<?php

namespace DrH\Jenga\Http;

use DrH\Jenga\Events\JengaBillIpnEvent;
use DrH\Jenga\Events\JengaIpnEvent;
use DrH\Jenga\Models\JengaBillIpn;
use DrH\Jenga\Models\JengaIpn;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Controller extends \Illuminate\Routing\Controller
{

    private function flatten(array $array): array
    {
        $ritit = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $result = array();

        foreach ($ritit as $leafValue) {
            $keys = array();
            foreach (range(0, $ritit->getDepth()) as $depth) {
                $keys[] = Str::snake($ritit->getSubIterator($depth)->key());
            }
            $result[ join('_', $keys) ] = $leafValue;
        }

        return $result;
    }

    public function handleIpn(Request $request): JsonResponse
    {
        jengaLogInfo('IPN: ', $request->all());

        try {
            $data = $this->flatten($request->all());
            unset($data['callback_type']); // TODO: should we check and ensure type is IPN?

            if (JengaIpn::whereTransactionReference($data['transaction_reference'])->exists()) {
                throw new Exception('ipn already received');
            }

            $ipn = JengaIpn::create($data);

            event(new JengaIpnEvent($ipn));
        } catch (Exception $e) {
            jengaLogError('Error handling ipn: ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json([
            'reference' => $request->transaction['reference'],
            'statusCode' => '0',
            'statusMessage' => 'IPN received'
        ]);
    }

    public function handleBillIpn(Request $request): JsonResponse
    {
        jengaLogInfo('Bill IPN: ', $request->all());

        if (empty($request->all()) || $request->username != config('jenga.bill.username') || $request->password != config('jenga.bill.password')) {
            return response()->json([
                'responseCode' => 'OK',
                'responseMessage' => 'INVALID DATA',
            ], 422);
        }

        try {
            $data = $this->flatten($request->all());
            unset($data['username'], $data['password']); // TODO: should we check and ensure type is IPN?

            if (JengaBillIpn::whereBillNumber($data['bill_number'])->exists()) {
                throw new Exception('bill ipn already received');
            }

            $ipn = JengaBillIpn::create($data);

            event(new JengaBillIpnEvent($ipn));
        } catch (Exception $e) {
            jengaLogError('Error handling bill ipn: ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json([
            'responseCode' => 'OK',
            'responseMessage' => 'SUCCESSFUL',
        ]);
    }
}
