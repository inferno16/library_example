<?php

namespace Traits;

trait Iterator
{
    protected $pos = 0;

    public function current()
    {
        return $this->data[$this->pos];
    }

    public function next(): void
    {
        ++$this->pos;
    }

    public function key(): int
    {
        return $this->pos;
    }

    public function valid(): bool
    {
        return isset($this->data[$this->pos]);
    }

    public function rewind(): void
    {
        $this->pos = 0;
    }
}
