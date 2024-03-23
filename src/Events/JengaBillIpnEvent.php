<?php

namespace DrH\Jenga\Events;

use DrH\Jenga\Models\JengaBillIpn;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JengaBillIpnEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly JengaBillIpn $ipn)
    {
        jengaLogInfo('JengaBillIpnEvent: ', $ipn->toArray());
    }
}
