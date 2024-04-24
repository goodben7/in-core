<?php

namespace goodben\banking\Core;

interface BatchInterface {

    public function isDoubleEntry(): bool;
    /**
     * @return array<EntryInterface> 
     */
    public function getEntries(): iterable;

    public function buildAuthorization(): AuthorizationInterface;
}