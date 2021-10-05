<?php

namespace App\Util\Logger;


/**
 * Class Context
 * @package App\Util\Monolog
 * @property $line
 * @property $file
 * @property $code
 * @property $trace
 */
class Context
{
    private array $context = [];

    public function setLine(int $line): Context
    {
        $this->__set('line', $line);
        return $this;
    }

    public function setFile(string $file): Context
    {
        $this->__set('file', $file);
        return $this;
    }

    public function setCode(int $code): Context
    {
        $this->__set('code', $code);
        return $this;
    }

    public function setTraceString(string $trace): Context
    {
        $this->__set('trace', $trace);
        return $this;
    }

    public function __set($name, $value)
    {
        $this->context[$name] = $value;
    }

    public function __get($name)
    {
        return $this->context[$name];
    }

    public function __isset($name): bool
    {
        return isset($this->context[$name]);
    }

    public function toArray(): array
    {
        return $this->context;
    }
}
