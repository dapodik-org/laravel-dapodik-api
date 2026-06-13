<?php

namespace Dapodik\Laravel\API\Contracts;

use Illuminate\Support\Collection;

interface ResponseContract
{
    public function toArray(): array;

    public function toCollection(): Collection;

    public function toJson(): false|string;
}
