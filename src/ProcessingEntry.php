<?php

namespace goodben\banking\Core;

/**
* Class ProcessingEntry.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
class ProcessingEntry {

    public function __construct(
        public EntryInterface $entry,
        public WalletInterface $wallet,
    )
    {
        
    }
}