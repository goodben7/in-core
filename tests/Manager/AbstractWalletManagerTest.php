<?php

namespace goodben\banking\Core\Test\Manager;

use goodben\banking\Core\Codes;
use PHPUnit\Framework\TestCase;
use goodben\banking\Core\Test\Batch;
use goodben\banking\Core\Test\Entry;
use goodben\banking\Core\Test\Wallet;
use goodben\banking\Core\Test\Operation;
use goodben\banking\Core\Test\Authorization;
use goodben\banking\Core\Test\SchemaManager;
use goodben\banking\Core\Test\WalletManager;
use goodben\banking\Core\Exception\EntryException;
use goodben\banking\Core\Exception\WalletException;
use goodben\banking\Core\Storage\WalletStorageLayer;
use goodben\banking\Core\Manager\AbstractSchemaManager;
use goodben\banking\Core\Exception\AuthorizationException;



class AbstractWalletManagerTest extends TestCase {

    /** @var WalletStorageLayer */
    private $storage;
    /** @var AbstractSchemaManager */
    private $schema;

    public function setUp():void {
        
        $this->storage = $this
            ->getMockForAbstractClass(WalletStorageLayer::class)
        ;

        $this->storage->method('saveWallet')->will(
            $this->returnCallback(function ($w) {
                return $w;
            })
        );

        $this->storage->method('saveAuthorization')->will(
            $this->returnCallback(function ($a) {
                return $a;
            })
        );

        $this->storage->method('saveEntry')->will(
            $this->returnCallback(function ($e) {
                return $e;
            })
        );

        $this->schema = new SchemaManager($this->storage, Entry::class);
        ;
    }

    /** @test */
    public function testOpen() {
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $w = new Wallet();

        $w = $manager->openWallet($w);

        $this->assertFalse($w->isClosed());
        $this->assertNotNull($w->getCreatedAt());
        $this->assertEquals($w->getWalletId(),'WA01');
    }

    /** @test */
    public function testClosedWalletNotFound() {
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(WalletException::class);

        $manager->closeWallet('FOOBAR');
    }

    /** @test */
    public function testClosedWalletClosed() {
        $w = new Wallet();
        $w->setClosed(true);
        $this->storage->method('getWallet')->willReturn($w);
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(WalletException::class);

        $manager->closeWallet('FOOBAR');
    }

    /** @test */
    public function testClosed() {
        $w = new Wallet();
        $w->setClosed(false);
        $this->storage->method('getWallet')->willReturn($w);
        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $w = $manager->closeWallet('FOOBAR');

        $this->assertNotNull($w->getClosedAt());
        $this->assertTrue($w->isClosed());
    }

    /** @test */
    public function testExecuteWhenCurrencyMismatch() {
        $op = new Entry();
        $op->setType(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');

        $w = new Wallet();
        $w->setCurrency('USD');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(EntryException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op, $w]);
    }

    /** @test */
    public function testExecuteWhenAmountIsUnset() {
        $op = new Entry();
        $op->setType(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');
        $op->setAmount("");

        $w = new Wallet();
        $w->setCurrency('CDF');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(EntryException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op, $w]);
    }

    /** @test */
    public function testExecuteWhenCurrencyIsInvalid() {
        $op = new Entry();
        $op->setType(Codes::OPERATION_TYPE_CASH_IN);
        $op->setAmount(110.3);
        $op->setCurrency("");

        $w = new Wallet();
        $w->setCurrency('CDF');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(EntryException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op, $w]);
    }

    /** @test */
    public function testExecuteWhenAmountIsNegative() {
        $op = new Entry();
        $op->setType(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');
        $op->setAmount(-110.3);

        $w = new Wallet();
        $w->setCurrency('CDF');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(EntryException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op, $w]);
    }

    /** @test */
    public function testExecuteWhenRateIsNotSet() {
        $op = new Entry();
        $op->setType(Codes::OPERATION_TYPE_CASH_IN);
        $op->setCurrency('CDF');
        $op->setTransactionCurrency('USD');
        $op->setAmount(110.3);

        $w = new Wallet();
        $w->setCurrency('CDF');

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(EntryException::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        $execute->invokeArgs($manager, [$op, $w]);
    }

    /* @test */
    public function testExecute() {
        $in = new Entry();
        $in->setType(Codes::OPERATION_TYPE_CASH_IN);
        $in->setCurrency('CDF');
        $in->setAmount(1500);
        $in->setTransactionCurrency('CDF');
        $in->setTransactionCurrency(1500);
        $in->setAppliedRate(1);

        $out = new Entry();
        $out->setType(Codes::OPERATION_TYPE_CASH_OUT);
        $out->setCurrency('CDF');
        $out->setAmount(500);
        $out->setTransactionCurrency('CDF');
        $out->setTransactionCurrency(500);
        $out->setAppliedRate(1);

        $w = new Wallet();
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $execute = self::getProtectedMethod(WalletManager::class, 'execute');

        /** @var Entry */
        $in = $execute->invokeArgs($manager, [$in, $w]);

        $this->assertNotNull($in->getExecutedAt());
        $this->assertNotNull($w->getBalanceUpdatedAt());
        $this->assertEquals($in->getBalance(), $w->getBalance());
        $this->assertEquals($in->getBalance(), 2500);

        $out = $execute->invokeArgs($manager, [$out, $w]);
        $this->assertEquals($out->getBalance(), $w->getBalance());
        $this->assertEquals($out->getBalance(), 2000);
    }

    /** @test */
    public function testRunWhenAuthorizationNotPending() {
        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_REFUSED);

        $batch = new Batch();


        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(AuthorizationException::class);

        $auth = $manager->run($batch, $auth);
    }

    /** @test */
    public function testRunWhenWalletNotFound() {
        $in = new Entry();
        $in->setType(Codes::OPERATION_TYPE_CASH_IN);
        $in->setCurrency('CDF');
        $in->setAmount(1500);
        $in->setTransactionCurrency('CDF');
        $in->setTransactionCurrency(1500);
        $in->setAppliedRate(1);
        $in->setWalletId("WA01");

        $out = new Entry();
        $out->setType(Codes::OPERATION_TYPE_CASH_OUT);
        $out->setCurrency('CDF');
        $out->setAmount(500);
        $out->setTransactionCurrency('CDF');
        $out->setTransactionCurrency(500);
        $out->setAppliedRate(1);
        $out->setWalletId("WA02");

        $w = new Wallet();
        $w->setWalletId("WA01");
        $w->setCurrency('CDF');
        $w->setBalance(1000);
        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setPlatformId("PL01");

        $batch = new Batch();
        $batch->auth = $auth;
        $batch->entries = [$in, $out];

        $this->storage->method('findAllWalletsById')->willReturn(["WA01" => $w]);


        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(AuthorizationException::class);

        $auth = $manager->run($batch);
    }

    /** @test */
    public function testRunWhenCreditAndDebitNotBalanced() {
        $in = new Entry();
        $in->setType(Codes::OPERATION_TYPE_CASH_IN);
        $in->setCurrency('CDF');
        $in->setAmount(1500);
        $in->setTransactionCurrency('CDF');
        $in->setTransactionCurrency(1500);
        $in->setAppliedRate(1);
        $in->setWalletId("WA01");

        $out = new Entry();
        $out->setType(Codes::OPERATION_TYPE_CASH_OUT);
        $out->setCurrency('CDF');
        $out->setAmount(500);
        $out->setTransactionCurrency('CDF');
        $out->setTransactionCurrency(500);
        $out->setAppliedRate(1);
        $out->setWalletId("WA02");

        $w = new Wallet();
        $w->setWalletId("WA01");
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $w2 = new Wallet();
        $w2->setWalletId("WA02");
        $w2->setCurrency('CDF');
        $w2->setBalance(1000);

        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setPlatformId("PL01");
        

        $batch = new Batch();
        $batch->auth = $auth;
        $batch->entries = [$in, $out];

        $this->storage->method('findAllWalletsById')->willReturn([
            "WA01" => $w,
            "WA02" => $w2,
        ]);


        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $this->expectException(AuthorizationException::class);

        $auth = $manager->run($batch);
    }

    /** @test */
    public function testRun() {
        $in = new Entry();
        $in->setType(Codes::OPERATION_TYPE_CASH_IN);
        $in->setCurrency('CDF');
        $in->setAmount(1500);
        $in->setTransactionCurrency('CDF');
        $in->setTransactionCurrency(1500);
        $in->setAppliedRate(1);
        $in->setWalletId("WA01");

        $out = new Entry();
        $out->setType(Codes::OPERATION_TYPE_CASH_OUT);
        $out->setCurrency('CDF');
        $out->setAmount(1500);
        $out->setTransactionCurrency('CDF');
        $out->setTransactionCurrency(1500);
        $out->setAppliedRate(1);
        $out->setWalletId("WA02");

        $w = new Wallet();
        $w->setWalletId("WA01");
        $w->setCurrency('CDF');
        $w->setBalance(1000);

        $w2 = new Wallet();
        $w2->setWalletId("WA02");
        $w2->setCurrency('CDF');
        $w2->setBalance(1000);

        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_PENDING);
        $auth->setPlatformId("PL01");
        

        $batch = new Batch();
        $batch->auth = $auth;
        $batch->entries = [$in, $out];

        $this->storage->method('findAllWalletsById')->willReturn([
            "WA01" => $w,
            "WA02" => $w2,
        ]);


        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $auth = $manager->run($batch);

        $this->assertEquals(Codes::AUTH_STATUS_ACCEPTED, $auth->getStatus());
    }

    /** @test */
    public function testAuthorizeWhenPreviousAuthExist() {
        $auth = new Authorization();
        $auth->setStatus(Codes::AUTH_STATUS_REFUSED);

        $p = new Operation();
        $p->requestId = "R01";
        $p->setOperationCode("PYT");

        $this->storage->method('findPreviousAuthorization')->willReturn($auth);

        $manager = new WalletManager($this->schema, $this->storage, Authorization::class);

        $result = $manager->authorize($p);

        $this->assertEquals(Codes::AUTH_STATUS_REFUSED, $result->getStatus());
    }

    protected static function getProtectedMethod($class, $name) {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}