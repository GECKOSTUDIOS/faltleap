<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapErrorHandler
{
    private static ?self $instance = null;
    private bool $debug = false;
    private string $storagePath;
    private bool $handling = false; // Recursive error protection

    public function __construct()
    {
        $this->storagePath = dirname(__DIR__) . '/storage';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register(): void
    {
        $this->debug = (bool)(getenv('APP_DEBUG') ?: false);

        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Convert PHP errors to ErrorException
     */
    public function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * Main exception handler
     */
    public function handleException(\Throwable $e): void
    {
        // Clean any output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $this->logError($e);

        if ($this->debug) {
            $this->renderDebugPage($e);
        } else {
            $this->renderProductionPage($e);
        }
    }

    /**
     * Catch fatal errors on shutdown
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->handleException(
                new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }

    /**
     * Run php -l syntax check on a file (debug mode only)
     */
    public function lintCheck(string $filePath): void
    {
        if (!$this->debug) {
            return;
        }
        if (!function_exists('proc_open')) {
            return;
        }
        if (!file_exists($filePath)) {
            return;
        }

        // Find the PHP CLI binary - PHP_BINARY may point to php-fpm which doesn't support -l
        $phpBin = $this->findPhpCliBinary();
        if ($phpBin === null) {
            return;
        }

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(escapeshellarg($phpBin) . ' -l ' . escapeshellarg($filePath), $descriptors, $pipes);
        if (!is_resource($process)) {
            return;
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            $output = trim($stderr ?: $stdout);
            // Parse line number from "Parse error: ... in /path on line N"
            $line = 0;
            if (preg_match('/on line (\d+)/', $output, $matches)) {
                $line = (int)$matches[1];
            }
            // Extract just the error message
            $errorMsg = $output;
            if (preg_match('/^(.*?) in .+ on line \d+/', $output, $matches)) {
                $errorMsg = $matches[1];
            }
            throw new LeapSyntaxException($filePath, $line, $errorMsg);
        }
    }

    /**
     * Find the PHP CLI binary, handling versioned binaries (e.g. php84 on Alpine)
     */
    private function findPhpCliBinary(): ?string
    {
        // Check common CLI binary names
        $candidates = ['php', 'php84', 'php83', 'php82', 'php81', 'php8.4', 'php8.3'];
        foreach ($candidates as $name) {
            $path = '/usr/bin/' . $name;
            if (is_executable($path) && strpos($name, 'fpm') === false) {
                return $path;
            }
            $path = '/usr/local/bin/' . $name;
            if (is_executable($path) && strpos($name, 'fpm') === false) {
                return $path;
            }
        }

        // Fallback: use PHP_BINARY only if it's not php-fpm
        $phpBin = PHP_BINARY;
        if ($phpBin && is_executable($phpBin) && strpos($phpBin, 'fpm') === false) {
            return $phpBin;
        }

        return null;
    }

    /**
     * Get source code lines around the error
     */
    public function getSourceCode(string $file, int $line, int $context = 10): array
    {
        if (!file_exists($file) || !is_readable($file)) {
            return [];
        }

        $lines = file($file);
        if ($lines === false) {
            return [];
        }

        $start = max(0, $line - $context - 1);
        $end = min(count($lines), $line + $context);

        $result = [];
        for ($i = $start; $i < $end; $i++) {
            $result[$i + 1] = $lines[$i];
        }
        return $result;
    }

    /**
     * Capture context variables and serialize for debug console
     */
    public function captureContext(array $vars): string
    {
        $id = bin2hex(random_bytes(16));
        $debugDir = $this->storagePath . '/debug';
        if (!is_dir($debugDir)) {
            @mkdir($debugDir, 0755, true);
        }
        // Filter out non-serializable values
        $safeVars = [];
        foreach ($vars as $key => $value) {
            try {
                serialize($value);
                $safeVars[$key] = $value;
            } catch (\Throwable $e) {
                $safeVars[$key] = '[unserializable: ' . get_class($value) . ']';
            }
        }
        @file_put_contents($debugDir . '/' . $id . '.ctx', serialize($safeVars));
        return $id;
    }

    /**
     * Log error to storage/logs/error.log
     */
    public function logError(\Throwable $e): void
    {
        $logDir = $this->storagePath . '/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $class = get_class($e);
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();

        $entry = "[{$timestamp}] {$class}: {$message} in {$file}:{$line}\n{$trace}\n\n";
        @file_put_contents($logDir . '/error.log', $entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Render the rich debug page with interactive console
     */
    public function renderDebugPage(\Throwable $e): void
    {
        // Recursive error protection
        if ($this->handling) {
            $this->renderFallbackPage($e);
            return;
        }
        $this->handling = true;

        try {
            $statusCode = 500;
            if ($e instanceof LeapHttpException) {
                $statusCode = $e->statusCode;
            }
            if (!headers_sent()) {
                http_response_code($statusCode);
                header('Content-Type: text/html; charset=UTF-8');
            }

            $exceptionClass = get_class($e);
            $message = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            $file = $e->getFile();
            $line = $e->getLine();
            $trace = $e->getTrace();

            // Capture context for debug console
            $contextId = $this->captureContext([
                '_exception' => $message,
                '_file' => $file,
                '_line' => $line,
            ]);

            // Build stack frames data for JS
            $frames = [];
            // Add the exception origin as frame 0
            $frames[] = [
                'file' => $file,
                'line' => $line,
                'class' => $exceptionClass,
                'function' => '',
                'source' => $this->getSourceCode($file, $line, 10),
            ];
            foreach ($trace as $i => $frame) {
                $frameFile = $frame['file'] ?? '[internal]';
                $frameLine = $frame['line'] ?? 0;
                $frameClass = $frame['class'] ?? '';
                $frameFunc = $frame['function'] ?? '';
                $source = [];
                if ($frameFile !== '[internal]' && $frameLine > 0) {
                    $source = $this->getSourceCode($frameFile, $frameLine, 10);
                }
                $frames[] = [
                    'file' => $frameFile,
                    'line' => $frameLine,
                    'class' => $frameClass,
                    'function' => $frameFunc,
                    'source' => $source,
                ];
            }

            // Collect request info
            $requestInfo = [
                'Method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
                'URL' => ($_SERVER['REQUEST_URI'] ?? ''),
                'Host' => $_SERVER['HTTP_HOST'] ?? '',
                'Content-Type' => $_SERVER['CONTENT_TYPE'] ?? '',
                'User-Agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ];
            $getParams = $_GET;
            $postParams = $_POST;

            // Collect session info (mask sensitive keys)
            $sessionData = $_SESSION ?? [];

            // Collect environment info (mask passwords)
            $envData = [];
            $sensitiveKeys = ['PASSWORD', 'SECRET', 'KEY', 'TOKEN', 'CREDENTIAL'];
            foreach ($_SERVER as $key => $value) {
                if (!is_string($value)) continue;
                $masked = false;
                foreach ($sensitiveKeys as $sensitive) {
                    if (stripos($key, $sensitive) !== false) {
                        $envData[$key] = '********';
                        $masked = true;
                        break;
                    }
                }
                if (!$masked) {
                    $envData[$key] = $value;
                }
            }

            // Encode data for JS
            $framesJson = json_encode($frames, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $requestJson = json_encode($requestInfo, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $getJson = json_encode($getParams, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $postJson = json_encode($postParams, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $sessionJson = json_encode($sessionData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $envJson = json_encode($envData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

            $shortFile = basename($file);

            echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$exceptionClass} - FaltLeap Debug</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background: #1a1a2e; color: #e0e0e0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, monospace; font-size: 14px; }
.header { background: #16213e; border-bottom: 3px solid #e94560; padding: 24px 32px; }
.header .exception-class { color: #e94560; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.header .exception-message { color: #fff; font-size: 22px; font-weight: 400; line-height: 1.4; word-break: break-word; }
.header .exception-location { color: #8a8a9a; font-size: 13px; margin-top: 12px; }
.header .exception-location span { color: #0f3460; background: #e94560; padding: 2px 8px; border-radius: 3px; font-weight: 600; color: #fff; margin-left: 8px; }
.main { display: flex; height: calc(100vh - 160px); }
.sidebar { width: 340px; min-width: 280px; background: #16213e; border-right: 1px solid #2a2a4a; overflow-y: auto; flex-shrink: 0; }
.sidebar .frame { padding: 12px 16px; border-bottom: 1px solid #1a1a2e; cursor: pointer; transition: background 0.15s; }
.sidebar .frame:hover { background: #1a1a3e; }
.sidebar .frame.active { background: #0f3460; border-left: 3px solid #e94560; }
.sidebar .frame .frame-method { color: #fff; font-size: 13px; font-weight: 500; }
.sidebar .frame .frame-location { color: #6a6a8a; font-size: 11px; margin-top: 3px; }
.content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.code-panel { flex: 1; overflow: auto; background: #0a0a1a; }
.code-panel .code-header { padding: 12px 20px; background: #12122a; border-bottom: 1px solid #2a2a4a; color: #8a8a9a; font-size: 12px; }
.code-panel .code-header .filepath { color: #53a8e2; }
.code-table { width: 100%; border-collapse: collapse; }
.code-table tr { line-height: 1.6; }
.code-table tr.highlight { background: rgba(233, 69, 96, 0.15); }
.code-table tr.highlight td.line-code { border-left: 3px solid #e94560; }
.code-table td.line-num { width: 60px; text-align: right; padding: 0 12px; color: #4a4a6a; user-select: none; font-size: 12px; vertical-align: top; }
.code-table td.line-code { padding: 0 16px; white-space: pre; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: 13px; overflow-x: auto; }
.tabs { display: flex; background: #16213e; border-top: 1px solid #2a2a4a; border-bottom: 1px solid #2a2a4a; }
.tabs .tab { padding: 10px 20px; cursor: pointer; color: #8a8a9a; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: color 0.15s, border-color 0.15s; border-bottom: 2px solid transparent; }
.tabs .tab:hover { color: #c0c0d0; }
.tabs .tab.active { color: #e94560; border-bottom-color: #e94560; }
.tab-content { max-height: 200px; overflow-y: auto; background: #0a0a1a; display: none; }
.tab-content.active { display: block; }
.tab-content table { width: 100%; border-collapse: collapse; }
.tab-content table td { padding: 6px 16px; border-bottom: 1px solid #1a1a2e; vertical-align: top; }
.tab-content table td:first-child { width: 220px; color: #53a8e2; font-weight: 500; white-space: nowrap; }
.tab-content table td:last-child { color: #c0c0c0; word-break: break-all; }
.console-panel { background: #0d0d1a; border-top: 1px solid #2a2a4a; }
.console-panel .console-header { padding: 8px 16px; background: #16213e; color: #8a8a9a; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.console-output { padding: 8px 16px; max-height: 120px; overflow-y: auto; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: 13px; }
.console-output .output-line { padding: 2px 0; }
.console-output .output-line.error { color: #e94560; }
.console-output .output-line.result { color: #4ecca3; }
.console-input-wrapper { display: flex; align-items: center; border-top: 1px solid #2a2a4a; padding: 0; }
.console-input-wrapper .prompt { color: #e94560; padding: 8px 4px 8px 16px; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-weight: 700; }
.console-input { flex: 1; background: transparent; border: none; color: #e0e0e0; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: 13px; padding: 8px; outline: none; }
::-webkit-scrollbar { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: #1a1a2e; }
::-webkit-scrollbar-thumb { background: #3a3a5a; border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: #4a4a6a; }
</style>
</head>
<body>
<div class="header">
    <div class="exception-class">{$exceptionClass}</div>
    <div class="exception-message">{$message}</div>
    <div class="exception-location">{$shortFile}<span>line {$line}</span></div>
</div>
<div class="main">
    <div class="sidebar" id="sidebar"></div>
    <div class="content">
        <div class="code-panel" id="code-panel"></div>
        <div class="tabs" id="tabs">
            <div class="tab active" data-tab="request">Request</div>
            <div class="tab" data-tab="session">Session</div>
            <div class="tab" data-tab="environment">Environment</div>
        </div>
        <div class="tab-content active" id="tab-request"></div>
        <div class="tab-content" id="tab-session"></div>
        <div class="tab-content" id="tab-environment"></div>
        <div class="console-panel">
            <div class="console-header">Debug Console</div>
            <div class="console-output" id="console-output"></div>
            <div class="console-input-wrapper">
                <span class="prompt">&gt;</span>
                <input type="text" class="console-input" id="console-input" placeholder="Type PHP expression and press Enter..." autocomplete="off" spellcheck="false">
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    var frames = {$framesJson};
    var requestInfo = {$requestJson};
    var getParams = {$getJson};
    var postParams = {$postJson};
    var sessionData = {$sessionJson};
    var envData = {$envJson};
    var contextId = "{$contextId}";
    var activeFrame = 0;

    function escapeHtml(str) {
        if (typeof str !== 'string') str = String(str);
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function renderSidebar() {
        var sidebar = document.getElementById('sidebar');
        var html = '';
        for (var i = 0; i < frames.length; i++) {
            var f = frames[i];
            var label = f['class'] ? f['class'] + '::' + f['function'] : f['function'] || '(main)';
            var shortPath = f.file.split('/').slice(-2).join('/');
            html += '<div class="frame' + (i === activeFrame ? ' active' : '') + '" data-index="' + i + '">';
            html += '<div class="frame-method">' + escapeHtml(label) + '</div>';
            html += '<div class="frame-location">' + escapeHtml(shortPath) + ':' + f.line + '</div>';
            html += '</div>';
        }
        sidebar.innerHTML = html;
        sidebar.querySelectorAll('.frame').forEach(function(el) {
            el.addEventListener('click', function() {
                activeFrame = parseInt(this.getAttribute('data-index'));
                renderSidebar();
                renderCode();
            });
        });
    }

    function renderCode() {
        var panel = document.getElementById('code-panel');
        var f = frames[activeFrame];
        if (!f || !f.source || Object.keys(f.source).length === 0) {
            panel.innerHTML = '<div class="code-header">No source available</div>';
            return;
        }
        var html = '<div class="code-header"><span class="filepath">' + escapeHtml(f.file) + '</span></div>';
        html += '<table class="code-table">';
        var keys = Object.keys(f.source).map(Number).sort(function(a,b){return a-b;});
        for (var j = 0; j < keys.length; j++) {
            var num = keys[j];
            var isHighlight = (num === f.line);
            html += '<tr' + (isHighlight ? ' class="highlight"' : '') + '>';
            html += '<td class="line-num">' + num + '</td>';
            html += '<td class="line-code">' + escapeHtml(f.source[String(num)]) + '</td>';
            html += '</tr>';
        }
        html += '</table>';
        panel.innerHTML = html;
    }

    function buildTable(data) {
        if (!data || Object.keys(data).length === 0) return '<div style="padding:16px;color:#6a6a8a;">Empty</div>';
        var html = '<table>';
        for (var key in data) {
            html += '<tr><td>' + escapeHtml(key) + '</td><td>' + escapeHtml(typeof data[key] === 'object' ? JSON.stringify(data[key]) : data[key]) + '</td></tr>';
        }
        html += '</table>';
        return html;
    }

    function renderTabs() {
        var reqHtml = buildTable(requestInfo);
        if (Object.keys(getParams).length > 0) {
            reqHtml += '<div style="padding:8px 16px;color:#53a8e2;font-weight:600;border-top:1px solid #2a2a4a;">GET Parameters</div>' + buildTable(getParams);
        }
        if (Object.keys(postParams).length > 0) {
            reqHtml += '<div style="padding:8px 16px;color:#53a8e2;font-weight:600;border-top:1px solid #2a2a4a;">POST Parameters</div>' + buildTable(postParams);
        }
        document.getElementById('tab-request').innerHTML = reqHtml;
        document.getElementById('tab-session').innerHTML = buildTable(sessionData);
        document.getElementById('tab-environment').innerHTML = buildTable(envData);

        document.querySelectorAll('.tabs .tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tabs .tab').forEach(function(t) { t.classList.remove('active'); });
                document.querySelectorAll('.tab-content').forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                document.getElementById('tab-' + this.getAttribute('data-tab')).classList.add('active');
            });
        });
    }

    function initConsole() {
        var input = document.getElementById('console-input');
        var output = document.getElementById('console-output');
        var history = [];
        var historyIndex = -1;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                var code = this.value.trim();
                history.unshift(code);
                historyIndex = -1;
                appendOutput('> ' + code, '');
                this.value = '';

                fetch('/_leap/debug/console', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({code: code, contextId: contextId})
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.error) {
                        appendOutput(data.error, 'error');
                    }
                    if (data.output) {
                        appendOutput(data.output, 'result');
                    }
                })
                .catch(function(err) {
                    appendOutput('Console request failed: ' + err.message, 'error');
                });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (historyIndex < history.length - 1) {
                    historyIndex++;
                    this.value = history[historyIndex];
                }
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (historyIndex > 0) {
                    historyIndex--;
                    this.value = history[historyIndex];
                } else {
                    historyIndex = -1;
                    this.value = '';
                }
            }
        });

        function appendOutput(text, cls) {
            var line = document.createElement('div');
            line.className = 'output-line' + (cls ? ' ' + cls : '');
            line.textContent = text;
            output.appendChild(line);
            output.scrollTop = output.scrollHeight;
        }
    }

    renderSidebar();
    renderCode();
    renderTabs();
    initConsole();
})();
</script>
</body>
</html>
HTML;
        } catch (\Throwable $renderError) {
            $this->renderFallbackPage($e, $renderError);
        }

        $this->handling = false;
    }

    /**
     * Render a clean production error page
     */
    public function renderProductionPage(\Throwable $e): void
    {
        $statusCode = 500;
        $statusText = 'Internal Server Error';

        if ($e instanceof LeapHttpException) {
            $statusCode = $e->statusCode;
        }
        if ($e instanceof LeapNotFoundException) {
            $statusText = 'Page Not Found';
        } elseif ($e instanceof LeapHttpException && $e->statusCode === 405) {
            $statusText = 'Method Not Allowed';
        }

        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: text/html; charset=UTF-8');
        }

        $refId = substr(md5($e->getMessage() . $e->getFile() . $e->getLine() . time()), 0, 12);

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$statusCode} - {$statusText}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
<div class="text-center">
    <h1 class="display-1 fw-bold text-muted">{$statusCode}</h1>
    <h2 class="mb-3">{$statusText}</h2>
    <p class="text-muted mb-4">Something went wrong. Please try again later.</p>
    <p class="text-muted small">Reference ID: <code>{$refId}</code></p>
    <a href="/" class="btn btn-primary">Go Home</a>
</div>
</body>
</html>
HTML;
    }

    /**
     * Fallback for when the debug page itself fails
     */
    private function renderFallbackPage(\Throwable $original, ?\Throwable $renderError = null): void
    {
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=UTF-8');
        }

        echo "=== FaltLeap Error Handler ===\n\n";
        echo "Exception: " . get_class($original) . "\n";
        echo "Message: " . $original->getMessage() . "\n";
        echo "File: " . $original->getFile() . ":" . $original->getLine() . "\n\n";
        echo "Stack Trace:\n" . $original->getTraceAsString() . "\n";

        if ($renderError) {
            echo "\n--- Additionally, the debug page failed to render ---\n";
            echo "Render Error: " . $renderError->getMessage() . "\n";
            echo "In: " . $renderError->getFile() . ":" . $renderError->getLine() . "\n";
        }
    }
}
