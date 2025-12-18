<?php

namespace App\Services;

use App\Contracts\DBLogInterface;
use App\Jobs\PersistSystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Throwable;

class DBLogService implements DBLogInterface
{
    protected $loggedBy = null;
    protected $customLevel = null;

    public function log(string $level, string $message, array $context = []): void
    {
        if (!Config::get('dblog.enabled', true)) {
            return;
        }

        $level = $this->customLevel ? ($this->customLevel . ':' . $level) : $level;

        // Build basic payload
        $payload = $this->buildPayload($level, $message, $context);

        // Dispatch to queue or persist immediately
        if (Config::get('dblog.use_queue', true)) {
            // use queue job
            PersistSystemLog::dispatch($payload)
                ->onConnection(Config::get('dblog.queue_connection') ?: null)
                ->onQueue(Config::get('dblog.queue_name', 'default'));
        } else {
            // sync persist
            $job = new PersistSystemLog($payload);
            $job->handle();
        }

        // reset chain overrides after a call
        $this->resetChainOverrides();
    }

    // convenience wrappers
    public function emergency(string $message, array $context = []): void { $this->log('emergency', $message, $context); }
    public function alert(string $message, array $context = []): void { $this->log('alert', $message, $context); }
    public function critical(string $message, array $context = []): void { $this->log('critical', $message, $context); }
    public function error(string $message, array $context = []): void { $this->log('error', $message, $context); }
    public function warning(string $message, array $context = []): void { $this->log('warning', $message, $context); }
    public function notice(string $message, array $context = []): void { $this->log('notice', $message, $context); }
    public function info(string $message, array $context = []): void { $this->log('info', $message, $context); }
    public function debug(string $message, array $context = []): void { $this->log('debug', $message, $context); }

    public function withLoggedBy(string $name): self
    {
        $this->loggedBy = $name;
        return $this;
    }

    public function withCustomLevel(string $customLevel): self
    {
        $this->customLevel = $customLevel;
        return $this;
    }

    protected function resetChainOverrides(): void
    {
        $this->loggedBy = null;
        $this->customLevel = null;
    }

    protected function buildPayload(string $level, string $message, array $context = []): array
    {
        // Basic metadata
        try {
            $user = Auth::user();
            $userId = $user ? $user->getKey() : null;
            $loggedBy = $this->loggedBy ?: ($user ? ($user->name ?? 'user:'.$userId) : 'system');
        } catch (Throwable $e) {
            $userId = null;
            $loggedBy = $this->loggedBy ?: 'system';
        }

        $ip = Request::ip();
        $url = Request::fullUrl();
        $method = Request::method();
        $host = Request::getHost();
        $env = app()->environment();

        // If context contains exception, extract fields
        $file = $context['file'] ?? null;
        $line = $context['line'] ?? null;
        $stack = null;
        $description = $context['description'] ?? null;

        if (isset($context['exception']) && $context['exception'] instanceof Throwable) {
            $ex = $context['exception'];
            $message = $message ?: $ex->getMessage();
            $file = $file ?? $ex->getFile();
            $line = $line ?? $ex->getLine();
            $stack = $ex->getTraceAsString();
            // include exception class in description if not present
            $description = $description ?? get_class($ex);
        }

        // Mask sensitive keys in payload / context / headers
        $maskKeys = array_map('strtolower', (array) Config::get('dblog.mask_keys', []));
        $contextMasked = $this->maskRecursive($context, $maskKeys, Config::get('dblog.mask_with', '*****'));

        // headers & payload capture (masked)
        $headers = $this->maskRecursive(Request::header(), $maskKeys, Config::get('dblog.mask_with', '*****'));
        // request payload
        $payloadRaw = $this->maskRecursive(Request::except([]), $maskKeys, Config::get('dblog.mask_with', '*****'));

        // Build final payload array matching migration columns
        return [
            'level' => (string) $level,
            'message' => Str::limit((string) $message, 1000),
            'description' => $description ? (string) $description : null,
            'context' => $contextMasked ? $contextMasked : null,
            'file' => $file ? (string) $file : null,
            'line' => $line ? (int) $line : null,
            'stack' => $stack ? (string) $stack : null,
            'logged_by' => (string) $loggedBy,
            'user_id' => $userId,
            'ip_address' => $ip,
            'url' => $url ? (string) $url : null,
            'method' => $method ? (string) $method : null,
            'headers' => $headers ?: null,
            'payload' => $payloadRaw ?: null,
            'host' => $host ?: null,
            'env' => $env ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    protected function maskRecursive($data, array $maskKeys, string $maskWith)
    {
        if (empty($maskKeys) || $data === null) {
            return $data;
        }

        // If not arrayable, return as is
        if (!is_array($data) && !($data instanceof \ArrayAccess)) {
            return $data;
        }

        $result = [];

        foreach ($data as $key => $value) {
            $lower = strtolower((string)$key);
            $isSensitive = in_array($lower, $maskKeys, true);

            if ($isSensitive) {
                $result[$key] = $maskWith;
                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->maskRecursive($value, $maskKeys, $maskWith);
            } else {
                // mask values that look like emails/credit cards optionally
                $result[$key] = $this->maybeMaskValue($key, $value, $maskKeys, $maskWith);
            }
        }

        return $result;
    }

    protected function maybeMaskValue($key, $value, $maskKeys, $maskWith)
    {
        // If key contains sensitive substring
        if (is_string($key) && $this->keyLooksSensitive($key, $maskKeys)) {
            return $maskWith;
        }

        // Mask obvious email-like values if 'email' is in mask keys
        if (in_array('email', $maskKeys, true) && is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $maskWith;
        }

        // Keep the original otherwise
        return $value;
    }

    protected function keyLooksSensitive($key, $maskKeys)
    {
        $k = strtolower($key);
        foreach ($maskKeys as $s) {
            if (strpos($k, $s) !== false) return true;
        }
        return false;
    }
}
