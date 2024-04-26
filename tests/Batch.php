<?php

namespace goodben\banking\Core\Test;

use goodben\banking\Core\BatchInterface;
use goodben\banking\Core\Test\Authorization;
use goodben\banking\Core\AuthorizationInterface;

class Batch implements BatchInterface {

    public ?Authorization $auth;
    public array $entries;

    public function isDoubleEntry(): bool
    {
        return true;
    }

    public function buildAuthorization(): AuthorizationInterface
    {
        return $this->auth;
    }

    public function getEntries(): iterable
    {
        return $this->entries;
    }
}