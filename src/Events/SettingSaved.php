<?php

namespace ShreejaDigital\Settings\Events;

class SettingSaved
{
    public function __construct(public string $key, public mixed $value) {}
}
