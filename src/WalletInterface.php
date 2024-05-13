<?php

namespace goodben\banking\Core;

/**
* Interface WalletInterface.
* 
* @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
*/
interface WalletInterface
{
    /**
     * @return double
    */
    public function getBalance(): string;
    /**
     * @param double $balance
    */
    public function setBalance(string $balance);
    /**
     * @return string
    */
    public function getCurrency(): string;
    /**
     * @param string $currency
    */
    public function setCurrency(string $currency);
    /**
     * @return string
    */
    public function getName(): ?string;
    /**
     * @param string $name
    */
    public function setName(?string $name);
    /**
     * @return \DateTimeImmutable
    */
    public function getCreatedAt(): \DateTimeImmutable;
    /**
     * @param \DateTimeImmutable $date
    */
    public function setCreatedAt(\DateTimeImmutable $date);
    /**
     * @return ?\DateTimeImmutable
    */
    public function getBalanceUpdatedAt(): ?\DateTimeImmutable;
    /**
     * @param ?\DateTimeImmutable $date
    */
    public function setBalanceUpdatedAt(?\DateTimeImmutable $date);
    /**
     * @return string
    */
    public function getWalletProfileId():?string;
    /**
     * @param string $type
     */
    public function setWalletProfileId(?string $profileId);
    /**
     * @return string
    */
    public function getHolderId():?string;
    /**
     * @param string $holder
    */
    public function setHolderId(?string $holder);
    /**
     * @return string
    */
    public function getWalletId(): string;
    /**
     * @param string $id
    */
    public function setWalletId(string $id);
    /**
     * @return string
    */
    public function getWalletPublicId(): ?string;
    /**
     * @param string $id
     */
    public function setWalletPublicId(?string $id);
    /**
     * @return boolean
    */
    public function isClosed(): bool;
    /**
     * @param boolean $closed
    */
    public function setClosed(bool $closed);
    /**
     * @return ?\DateTimeImmutable
    */
    public function getClosedAt(): ?\DateTimeImmutable;
    /**
     * @param ?\DateTimeImmutable $date
    */
    public function setClosedAt(?\DateTimeImmutable $date);
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
    public function getGlCode(): ?string;
    /**
     * @param double $code
    */
    public function setGlCode(?string $code);
    /**
     * @return string
    */
    public function getWalletTypeId():?string;
    /**
     * @param string $type
    */
    public function setWalletTypeId(?string $profileId);
}
