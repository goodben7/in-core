<?php

namespace goodben\banking\Core;

use goodben\banking\Core\BatchInterface;

final class AuthorizationBatch  implements BatchInterface
{
    protected bool $_isDoubleEntry;
    /** @var array<Entry>  */
    protected iterable $_entries;

    public function __construct(private AuthorizationInterface $auth, iterable $entries,bool $isDoubleEntry = true)
    {
        $this->_isDoubleEntry = $isDoubleEntry;
        $this->_entries = $entries;
    }

    public function isDoubleEntry(): bool
    {
        return $this->_isDoubleEntry;
    }

    public function getEntries(): iterable
    {
        return $this->_entries;
    }

    public function buildAuthorization(): AuthorizationInterface
    {
        return $this->auth;
    }
}
