<?php 

namespace goodben\banking\Core\Test\Manager;

use PHPUnit\Framework\TestCase;


use goodben\banking\Core\Test\Entry;
use goodben\banking\Core\Instruction;
use goodben\banking\Core\Test\Wallet;
use goodben\banking\Core\Test\Operation;
use goodben\banking\Core\SchemaInterface;
use goodben\banking\Core\Test\SchemaManager;
use goodben\banking\Core\Storage\WalletStorageLayer;



class AbstractSchemaManagerTest extends TestCase
{
    /** @var SchemaManager */
    private $manager;
    

    public function setUp():void {

        /** @var WalletStorageLayer $storage */
        $storage = $this
            ->getMockForAbstractClass(WalletStorageLayer::class)
        ;

        /** @var SchemaInterface $schmOne */
        $schmOne = $this
            ->getMockBuilder(SchemaInterface::class)
            ->getMock()
        ;

        $storage
            ->method('getInstructions')
            ->willReturn([
                new Instruction(1,'t.amount / 2','t.currency','D','FIRST TRANSACTION','SCHM01','WA001', Instruction::AMOUNT_COMPUTED|Instruction::CURRENCY_COMPUTED),
                new Instruction(2,'t.amount','t.currency','C','upper(t.description)','SCHM01','publicId("20220022")', Instruction::AMOUNT_COMPUTED|Instruction::CURRENCY_COMPUTED|Instruction::LABEL_COMPUTED|Instruction::WALLET_COMPUTED),
            ])
        ;

        $storage
            ->method('findSchemaBy')
            ->willReturn($schmOne)
        ;

        $storage
            ->method('getSchema')
            ->willReturn($schmOne)
        ;

        $w = new Wallet();
        $w->setWalletId("WA002");
        $w->setGlCode("17100");
        $w->setWalletPublicId("20220022");

        $storage
            ->method('findWalletBy')
            ->willReturn($w)
        ;

        $storage
            ->method('getWallet')
            ->willReturn($w)
        ;

        $this->manager = new SchemaManager($storage, Entry::class);
        $this->manager->registerFx('upper',new UpperFx);
    }
    public function TestGetSchemaFor() {
        $p = new Operation('TX1');
        $p->amount = 10;
        $p->currency = "USD";
        $p->walletId = "WA002";
        $ops = $this->manager->getSchemaFor($p);

        $this->assertCount(2, $ops);
        $op = $ops[0];

        $this->assertEquals($op->getTransactionCurrency(),$p->getCurrency());
        $this->assertEquals($op->getTransactionAmount(),5);
        $this->assertEquals($op->getWalletId(),'WA001');
        $this->assertEquals($op->getType(),'D');

        $op = $ops[1];
        $this->assertEquals($op->getTransactionAmount(),$p->getTransactionAmount());
        $this->assertEquals($op->getLabel(),strtoupper($p->getDescription()));
        $this->assertEquals($op->getWalletId(), "WA002");
    }
    
}

class UpperFx {

    public function __invoke($a, $label) {
        return \strtoupper($label);
    }
}