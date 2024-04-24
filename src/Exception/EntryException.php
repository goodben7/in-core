<?php

namespace goodben\banking\Core\Exception;

use goodben\banking\Core\EntryInterface;

class EntryException extends \Exception 
{
    /** @var EntryInterface|null $operation */
    protected ?EntryInterface $operation;

    public function __construct($message, EntryInterface $operation = null) {
        parent::__construct($message);
        $this->operation = $operation;
    }

    /**
     * Get the value of operation
     * @return EntryInterface
    */ 
    public function getOperation(): ?EntryInterface
    {
        return $this->operation;
    }
}