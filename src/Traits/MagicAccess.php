<?php

namespace Traits;

use Helpers\StringHelper;
use RuntimeException;

/**
 * @method array getData()
 * @method $this setData(array $data)
 */
trait MagicAccess
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        [$method, $key] = StringHelper::splitAt($name, 3);
        if (!in_array($method, ['get', 'set'])) {
            throw new RuntimeException("Method $method not allowed");
        }

        return $this->$method(lcfirst($key), ...$arguments);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    public function __isset($name)
    {
        if ($name === 'data') {
            return isset($this->data);
        }
        return isset($this->data[$name]);
    }

    /**
     * @param $key
     * @return mixed
     */
    private function get($key)
    {
        if ($key === 'data') {
            return $this->data;
        }

        return $this->data[$key];
    }

    private function set($key, $value): self
    {
        if ($key === 'data') {
            $this->data = $value;
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

}
