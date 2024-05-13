<?php

namespace goodben\banking\Core;

/**
* Interface BatchInterface.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
interface BatchInterface {

    public function isDoubleEntry(): bool;
    /**
    * @return array<EntryInterface> 
    */
    public function getEntries(): iterable;

    public function buildAuthorization(): AuthorizationInterface;
}