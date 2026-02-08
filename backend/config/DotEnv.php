<?php
class DotEnv {
    protected $path;
    protected $vars = [];

    public function __construct($path) {
        $this->path = $path;
    }

    public function load() {

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                if (preg_match('/^"(.+)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.+)'$/", $value, $matches)) {
                    $value = $matches[1];
                }

                if (!array_key_exists($name, $_ENV)) {
                    putenv("{$name}={$value}");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                    $this->vars[$name] = $value;
                }
            }
        }

        return $this;
    }
    public static function get($key, $default = null) {
        $value = getenv($key);
        
        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }

        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') return true;
            if ($lower === 'false') return false;
            if ($lower === 'null') return null;
        }

        return $value;
    }

    public static function has($key) {
        return self::get($key) !== null;
    }

    public static function required($key) {
        $value = self::get($key);
        return $value;
    }
}
