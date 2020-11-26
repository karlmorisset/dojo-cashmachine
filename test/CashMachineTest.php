<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../src/CashMachine.php";

final class CashMachineTest extends TestCase
{
    public function testEmptyCashMachine(): void
    {
        $atm = new CashMachine();
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testAddZero(): void
    {
        $atm = new CashMachine();
        $this->assertSame(true, $atm->addCash(200, 0));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testAddNegative(): void
    {
        $atm = new CashMachine();
        $this->assertSame(false, $atm->addCash(100, -1));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testAddWrongBill(): void
    {
        $atm = new CashMachine();
        $this->assertSame(false, $atm->addCash(42, 10));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testSuccessiveSameAdds(): void
    {
        $atm = new CashMachine();
        // Add a 500
        $this->assertSame(true, $atm->addCash(500, 1));
        $this->assertSame([500 => 1, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add two 500
        $this->assertSame(true, $atm->addCash(500, 2));
        $this->assertSame([500 => 3, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add ten 500
        $this->assertSame(true, $atm->addCash(500, 10));
        $this->assertSame([500 => 13, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testSuccessiveDifferentAdds(): void
    {
        $atm = new CashMachine();
        // Add a 500
        $this->assertSame(true, $atm->addCash(500, 1));
        $this->assertSame([500 => 1, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add a 200
        $this->assertSame(true, $atm->addCash(200, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add a 100
        $this->assertSame(true, $atm->addCash(100, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add a 50
        $this->assertSame(true, $atm->addCash(50, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add a 20
        $this->assertSame(true, $atm->addCash(20, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 0, 5 => 0], $atm->getRemainingCash());
        // Add a 10
        $this->assertSame(true, $atm->addCash(10, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 1, 5 => 0], $atm->getRemainingCash());
        // Add a 5
        $this->assertSame(true, $atm->addCash(5, 1));
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 1, 5 => 1], $atm->getRemainingCash());
    }

    public function testSimpleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(50, 2);
        $this->assertSame([50 => 1], $atm->withdraw(50));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 1, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testMultipleBillWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(500, 10);
        $atm->addCash(200, 10);
        $atm->addCash(100, 10);
        $atm->addCash(50, 10);
        $atm->addCash(20, 10);
        $atm->addCash(10, 10);
        $atm->addCash(5, 10);
        $this->assertSame([500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 2, 5 => 1], $atm->withdraw(895));
        $this->assertSame([500 => 9, 200 => 9, 100 => 9, 50 => 9, 20 => 8, 10 => 10, 5 => 9], $atm->getRemainingCash());
    }

    public function testMultipleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 1], $atm->withdraw(100));
        $this->assertSame([500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        $this->assertSame([100 => 1], $atm->withdraw(100));
        $this->assertSame([500 => 0, 200 => 0, 100 => 98, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testNonOptimapWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(20, 10);
        $this->assertSame([20 => 4], $atm->withdraw(80));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 6, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testMultipleNonOptimalWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 10], $atm->withdraw(1000));
        $this->assertSame([500 => 0, 200 => 0, 100 => 90, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        $this->assertSame([100 => 5], $atm->withdraw(500));
        $this->assertSame([500 => 0, 200 => 0, 100 => 85, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testSomeWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 10);
        $atm->addCash(10, 5);
        $atm->addCash(5, 100);
        $this->assertSame([100 => 3, 10 => 5, 5 => 1], $atm->withdraw(355));
        $this->assertSame([500 => 0, 200 => 0, 100 => 7, 50 => 0, 20 => 0, 10 => 0, 5 => 99], $atm->getRemainingCash());
        $this->assertSame([100 => 7, 5 => 31], $atm->withdraw(855));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 68], $atm->getRemainingCash());
    }

    public function testRoundedWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 1], $atm->withdraw(123));
        $this->assertSame([500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    public function testImpossibleWithdraw(): void
    {
        $atm = new CashMachine();
        $this->assertSame([], $atm->withdraw(5));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        $atm->addCash(10, 1);
        $this->assertSame([], $atm->withdraw(0));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 1, 5 => 0], $atm->getRemainingCash());
        $this->assertSame([], $atm->withdraw(-20));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 1, 5 => 0], $atm->getRemainingCash());
    }

    public function testNotEnoughWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(10, 1);
        $atm->addCash(5, 1);
        $this->assertSame([10 => 1, 5 => 1], $atm->withdraw(40));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    
}