<?php

namespace Traits;

trait Countable
{
    public function count(): int
    {
        return count($this->data);
    }
}
