<?php

namespace goodben\banking\Core\Test;

use goodben\banking\Core\WalletInterface;
use goodben\banking\Core\Manager\AbstractWalletManager;

class WalletManager extends AbstractWalletManager {

    protected function generateWalletIdFor(WalletInterface $wallet): string
    {
        return "WA01";
    }

    protected function getNextAuthorizationId(): string
    {
        return "001";
    }

}