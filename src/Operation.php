<?php

namespace goodben\banking\Core;

abstract class Operation  
{
    protected ?string $authorizationId;

    public function getAuthorizationId(): string {
        return $this->authorizationId;
    }

    public function setAuthorizationId(string $authorizationId) {
        $this->authorizationId = $authorizationId;
    }

    abstract function getSchemaId(): ?string;

    abstract function getOperationId(): ?string;

    abstract function getOperationCode(): ?string;

    abstract function getAuthorizationRequestId(): ?string;

    abstract function getHolderId(): ?string;

    abstract function getPlatformId(): ?string;

    abstract function hasDoubleEntrySupport(): bool;

    abstract function getDescription(): ?string;

    abstract function getTransactionAmount(): string;

    abstract function getCurrency(): string;

    abstract function getCommissionCurrency(): string;

    abstract function getCommissionAmount(): string;
}
