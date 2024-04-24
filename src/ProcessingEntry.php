<?php

namespace goodben\banking\Core;

class ProcessingEntry {

    public function __construct(
        public EntryInterface $entry,
        public WalletInterface $wallet,
    )
    {
        
    }
}