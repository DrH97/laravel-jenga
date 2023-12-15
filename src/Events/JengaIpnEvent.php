<?php

namespace DrH\Jenga\Events;

use DrH\Jenga\Models\JengaIpn;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JengaIpnEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly JengaIpn $ipn)
    {
        jengaLogInfo('JengaIpnEvent: ', $ipn->toArray());
    }
}
