<?php

use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

//TODO: Add tests for all these helpers
//    e.g. if logging channels doesn't exist, we shouldn't throw error
if (!function_exists('shouldJengaLog')) {
    function shouldJengaLog(): bool
    {
        return config('jenga.logging.enabled') == true;
    }
}

if (!function_exists('getJengaLogger')) {
    function getJengaLogger(): LoggerInterface
    {
        if (shouldJengaLog()) {
            $channels = [];

            foreach (config('jenga.logging.channels') as $rawChannel) {
                if (is_string($rawChannel)) {
                    $channels[] = $rawChannel;
                } elseif (is_array($rawChannel)) {
                    $channels[] = Log::build($rawChannel);
                }
            }

            return Log::stack($channels);
        }

        return Log::build([
            'driver' => 'single',
            'path' => '/dev/null',
        ]);
    }
}

if (!function_exists('jengaLog')) {
    function jengaLog(string $level, string $message, array $context = []): void
    {
        $message = '[LIB - JENGA]: ' . $message;
        getJengaLogger()->log($level, $message, $context);
    }
}

if (!function_exists('jengaLogError')) {
    function jengaLogError(string $message, array $context = []): void
    {
        $message = '[LIB - JENGA]: ' . $message;
        getJengaLogger()->error($message, $context);
    }
}

if (!function_exists('jengaLogInfo')) {
    function jengaLogInfo(string $message, array $context = []): void
    {
        $message = '[LIB - JENGA]: ' . $message;
        getJengaLogger()->info($message, $context);
    }
}

if (!function_exists('parseGuzzleResponse')) {
    function parseGuzzleResponse(ResponseInterface $response, bool $includeBody = false): array
    {
        $headers = [];
        $excludeHeaders = ['set-cookie'];
        foreach ($response->getHeaders() as $name => $value) {
            if (in_array(strtolower($name), $excludeHeaders)) {
                continue;
            }

            $headers[$name] = $value;
        }

        // response is cloned to avoid any accidental data damage
        $body = (clone $response)->getBody();
        if (!$body->isReadable()) {
            $content = 'unreadable';

            return [
                'protocol' => $response->getProtocolVersion(),
                'reason_phrase' => $response->getReasonPhrase(),
                'status_code' => $response->getStatusCode(),
                'headers' => $headers,
                'size' => $response->getBody()->getSize(),
                'body' => $content,
            ];
        }

        if ($body->isSeekable()) {
            $previousPosition = $body->tell();
            $body->rewind();
        }

        $content = $body->getContents();

        if ($body->isSeekable()) {
            $body->seek($previousPosition);
        }

        return $includeBody ?
            [
                'protocol' => $response->getProtocolVersion(),
                'reason_phrase' => $response->getReasonPhrase(),
                'status_code' => $response->getStatusCode(),
                'headers' => $headers,
                'size' => $response->getBody()->getSize(),
                'body' => $content,
            ] :
            [
                'protocol' => $response->getProtocolVersion(),
                'reason_phrase' => $response->getReasonPhrase(),
                'status_code' => $response->getStatusCode(),
                'headers' => $headers,
                'size' => $response->getBody()->getSize(),
            ];
    }
}
