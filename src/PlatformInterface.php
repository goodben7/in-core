<?php

namespace goodben\banking\Core;

/**
* Interface PlatformInterface.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
interface PlatformInterface
{
    /**
     * @return string
    */
    public function getPlatformId(): string;
    /**
     * @param string $pif
    */
    public function setPlatformId(string $pif);
    /**
     * @return string
    */
    public function getLabel(): string;
    /**
     * @param string $label
    */
    public function setLabel(string $label);
    /**
     * @return string
    */
    public function getMode(): string;
    /**
     * @param string $label
    */
    public function setMode(string $label);
}
