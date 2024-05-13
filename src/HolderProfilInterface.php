<?php

namespace goodben\banking\Core;

/**
* Interface HolderProfilInterface.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
interface HolderProfilInterface  
{
    /**
     * @return string
     */
    public function getCode(): string;
    /**
     * @param string $code
     */
    public function setCode(string $code);
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
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);
}
