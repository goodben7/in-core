<?php

namespace goodben\banking\Core\Storage;

use goodben\banking\Core\Instruction;
use goodben\banking\Core\EntryInterface;
use goodben\banking\Core\HolderInterface;
use goodben\banking\Core\SchemaInterface;
use goodben\banking\Core\WalletInterface;
use goodben\banking\Core\PlatformInterface;
use goodben\banking\Core\AuthorizationInterface;
use goodben\banking\Core\Exception\StorageLayerException;

/**
 * Abstract WalletStorageLayer.
 * 
 * @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
abstract class WalletStorageLayer
{
    use FindByCallResolver;
    /**
     * begin a transaction for a batch database operations
    */
    public abstract function beginTransaction();
    /**
     * validate database batch operations
    */
    public abstract function commit();
    /**
     * cancel database batch operations
    */
    public abstract function rollback();
    
    /**
     * save wallet in the storage layer
     * 
     * @param WalletInterface $wallet 
     * @param bool $autocommit
     * @return WalletInterface
     * @throws StorageLayerException
    **/
    public abstract function saveWallet(WalletInterface $wallet, bool $autocommit = true): WalletInterface;

    /**
     * save entry in the storage layer
     * 
     * @param EntryInterface $op 
     * @param bool $autocommit
     * @return EntryInterface
     * @throws StorageLayerException
    **/
    abstract public function saveEntry(EntryInterface $op, bool $autocommit = true): EntryInterface;

    /**
     * save holder in the storage layer
     * 
     * @param HolderInterface $holder 
     * @param bool $autocommit
     * @return HolderInterface
     * @throws StorageLayerException
    **/
    public abstract function saveHolder(HolderInterface $holder, bool $autocommit = true): HolderInterface;

    /**
     * save authorization in the storage layer
     * 
     * @param AuthorizationInterface $auth 
     * @param bool $autocommit
     * @return AuthorizationInterface
     * @throws StorageLayerException
    **/
    public abstract function saveAuthorization(AuthorizationInterface $auth, bool $autocommit = true): AuthorizationInterface;

    /**
     * getting wallet by criteria
     *
     * @param array $criteria
     * @return null|WalletInterface
     * @throws StorageLayerException
    **/
    public abstract function findWalletBy(array $criteria): ?WalletInterface;

    /**
     * getting entry by criteria
     *
     * @param array $criteria
     * @return null|EntryInterface
     * @throws StorageLayerException
    **/
    public abstract function findEntryBy(array $criteria): ?EntryInterface;

    /**
     * getting holder by criteria
     *
     * @param array $criteria
     * @return null|HolderInterface
     * @throws StorageLayerException
    **/
    public abstract function findHolderBy(array $criteria): ?HolderInterface;

    /**
     * getting authorization by criteria
     *
     * @param array $criteria
     * @return null|AuthorizationInterface
     * @throws StorageLayerException
    **/
    public abstract function findAuthorizationBy(array $criteria): ?AuthorizationInterface;

    /**
     * find previous authorization by request id
     *
     * @param string $requestId
     * @return null|AuthorizationInterface
     * @throws StorageLayerException
    **/
    public abstract function findPreviousAuthorization(string $requestId, string $operationCode): ?AuthorizationInterface;

    /**
     * find all wallets by their ids
     *
     * @param string[] $walletIds
     * @return array<string,WalletInterface>
     * @throws StorageLayerException
    **/
    public abstract function findAllWalletsById(array $walletIds): iterable;

    /**
     * getting schema by criteria
     *
     * @param array $criteria
     * @return null|SchemaInterface
     * @throws StorageLayerException
    **/
    public abstract function findSchemaBy(array $criteria): ?SchemaInterface;

    /**
     * getting platform by id
     *
     * @param string $id
     * @return null|PlatformInterface
     * @throws StorageLayerException
    **/
    public abstract function getPlatform($id): ?PlatformInterface;

    /**
     * getting schema by id
     *
     * @param string $id
     * @return null|SchemaInterface
     * @throws StorageLayerException
    **/
    public abstract function getSchema($id): ?SchemaInterface;

    /**
     * getting wallet by id
     *
     * @param string $id
     * @return null|WalletInterface
     * @throws StorageLayerException
    **/
    public abstract function getWallet($id): ?WalletInterface;

    /**
     * getting entries by criteria
     *
     * @param array $criteria
     * @return EntryInterface[]
     * @throws StorageLayerException
    **/
    public abstract function listEntryBy(array $criteria): iterable;

    /**
     * @param string $code;
     * @return Instruction[]
    */
    public abstract function getInstructions($code): iterable;

    /**
     * Adds support for magic method calls.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed The returned value from the resolved method.
     *
     * @throws StorageLayerException
     * @throws \BadMethodCallException If the method called is invalid
    */
    public function __call($method, $arguments) {
        if (0 === strpos($method, 'findWalletBy')) {
            return $this->resolveFindByCall('findWalletBy', substr($method, 12), $arguments);
        }

        if (0 === strpos($method, 'findEntryBy')) {
            return $this->resolveFindByCall('findEntryBy', substr($method, 11), $arguments);
        }

        if (0 === strpos($method, 'findAuthorizationBy')) {
            return $this->resolveFindByCall('findAuthorizationBy', substr($method, 19), $arguments);
        }

        if (0 === strpos($method, 'findHolderBy')) {
            return $this->resolveFindByCall('findHolderBy', substr($method, 12), $arguments);
        }

        if (0 === strpos($method, 'findSchemaBy')) {
            return $this->resolveFindByCall('findSchemaBy', substr($method, 12), $arguments);
        }

        throw new \BadMethodCallException("Undefined method '$method'");
    }
}
