<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapDebugConsole
{
    /**
     * Handle a debug console AJAX request.
     * Only works when APP_DEBUG=true AND request comes from a local/private IP.
     */
    public static function handle(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }

        // Security: require debug mode
        if (!LeapErrorHandler::getInstance()->isDebug()) {
            echo json_encode(['error' => 'Debug mode is not enabled']);
            return;
        }

        // Security: require local/private IP
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        if (!self::isPrivateIp($remoteIp)) {
            echo json_encode(['error' => 'Console is only available from local/private networks']);
            return;
        }

        // Read JSON body
        $rawBody = file_get_contents('php://input');
        $body = json_decode($rawBody, true);
        if (!$body || !isset($body['code'])) {
            echo json_encode(['error' => 'Invalid request body']);
            return;
        }

        $code = (string)$body['code'];
        $contextId = (string)($body['contextId'] ?? '');

        // Load context if available
        $contextVars = [];
        if ($contextId !== '') {
            $ctxFile = dirname(__DIR__) . '/storage/debug/' . basename($contextId) . '.ctx';
            if (file_exists($ctxFile)) {
                $contextVars = unserialize(file_get_contents($ctxFile)) ?: [];
            }
        }

        // Execute code in isolated scope
        $output = '';
        $error = '';

        try {
            extract($contextVars, EXTR_SKIP);
            ob_start();
            $result = eval($code);
            $output = ob_get_clean() ?: '';
            if ($result !== null && $result !== false) {
                $output .= ($output !== '' ? "\n" : '') . var_export($result, true);
            }
        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            $error = get_class($e) . ': ' . $e->getMessage();
        }

        echo json_encode([
            'output' => $output,
            'error' => $error,
        ]);
    }

    /**
     * Check if an IP address is local or private.
     */
    private static function isPrivateIp(string $ip): bool
    {
        // IPv6 loopback
        if ($ip === '::1') {
            return true;
        }
        // IPv4 loopback and private ranges
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE) === false) {
            // The IP is either private, reserved, or invalid — private/loopback passes
            return filter_var($ip, FILTER_VALIDATE_IP) !== false;
        }
        return false;
    }
}
