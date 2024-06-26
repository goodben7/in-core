<?php

namespace goodben\banking\Core\Storage;

use goodben\banking\Core\Exception\StorageLayerException;


trait FindByCallResolver {

    /**
     * Resolves a findBy magic method call to the proper existent method at `WalletStorageLayer`.
     *
     * @param string $method    The method to call
     * @param string $by        The property name used as condition
     * @param array  $arguments The arguments to pass at method call
     *
     * @throws StorageLayerException
     *
     * @return mixed
    */
    protected function resolveFindByCall($method, $by, $arguments) {
        if (! $arguments) {
            throw new StorageLayerException("You need to pass a parameter to '".$method.$by."'");
        }

        $prop = lcfirst($by);

        return $this->$method([$prop => $arguments[0]]);
    }
} 