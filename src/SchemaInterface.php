<?php

namespace goodben\banking\Core;

interface SchemaInterface
{
    /**
     * @return string
    */
    public function getSchemaId(): string;
    /**
     * @param string $id
    */
    public function setSchemaId(string $id);
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
    public function getCode(): string;
    /**
     * @param string $code
    */
    public function setCode(string $code);
}
