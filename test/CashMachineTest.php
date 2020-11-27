<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CashMachineTest extends TestCase
{

    public function testCashMachineFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/CashMachine.php', "You should have a /src/CashMachine.php file");
    }

    /**
     * @depends testCashMachineFileExists
     */
    public function testCashMachineClassExists(): void
    {
        require_once __DIR__ . '/../src/CashMachine.php';
        $this->assertTrue(class_exists("CashMachine"), "You should have a CashMachine class");
    }

    /**
     * @depends testCashMachineClassExists
     */
    public function testAddCashExists(): CashMachine
    {
        $atm = new CashMachine();
        $this->assertTrue(method_exists($atm, "addCash"), "Your CashMachine class should have a addCash method");
        return $atm;
    }

    /**
     * @depends testAddCashExists
     */
    public function testGetRemainingCashExists(CashMachine $atm): CashMachine
    {
        $this->assertTrue(method_exists($atm, "getRemainingCash"), "Your CashMachine class should have a getRemainingCash method");
        return $atm;
    }

    /**
     * @depends testGetRemainingCashExists
     */
    public function testEmptyCashMachine(CashMachine $atm): CashMachine
    {
        $atm = new CashMachine();
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "A new CashMachine should have 0 of every possible bills"
        );
        return $atm;
    }

    /**
     * @depends testEmptyCashMachine
     */
    public function testAddZero(CashMachine $atm): void
    {
        $this->assertTrue($atm->addCash(200, 0), "You should get true when adding 0 of an existing bill");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding 0 of an existing bill should let the CashMachine unchanged"
        );
    }

    /**
     * @depends testEmptyCashMachine
     */
    public function testAddNegative(): void
    {
        $atm = new CashMachine();
        $this->assertFalse(
            $atm->addCash(100, -1),
            "You should get false when adding a negative number of an existing bill"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding a negative number of an existing bill should let the CashMachine unchanged"
        );
    }

    /**
     * @depends testEmptyCashMachine
     */
    public function testAddWrongBill(): void
    {
        $atm = new CashMachine();
        $this->assertFalse($atm->addCash(42, 10), "You get false when adding non existing bills");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding non existing bills should remain the CashMachine unchanged"
        );
    }

    /**
     * @depends testEmptyCashMachine
     */
    public function testSuccessiveSameAdds(): void
    {
        $atm = new CashMachine();
        // Add a 500
        $this->assertTrue($atm->addCash(500, 1), "You should get true when adding one 500");
        $this->assertSame(
            [500 => 1, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 500 to an empty machine should change the remaining cash accordingly"
        );
        // Add two 500
        $this->assertTrue($atm->addCash(500, 2), "You should get true when adding two 500");
        $this->assertSame(
            [500 => 3, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding two 500 to a one 500 machine should change the remaining cash accordingly"
        );
        // Add ten 500
        $this->assertTrue($atm->addCash(500, 10), "You should get true when adding two 500");
        $this->assertSame(
            [500 => 13, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding ten 500 to a three 500 machine should change the remaining cash accordingly"
        );
    }

    /**
     * @depends testEmptyCashMachine
     */
    public function testSuccessiveDifferentAdds(): void
    {
        $atm = new CashMachine();
        // Add a 200
        $this->assertTrue($atm->addCash(200, 1), "You should get true when adding one 200");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 200 to an empty machine should change the remaining cash accordingly"
        );
        // Add a 100
        $this->assertTrue($atm->addCash(100, 1), "You should get true when adding one 100");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 1, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 100 to a one 200 machine should change the remaining cash accordingly"
        );
        // Add a 50
        $this->assertTrue($atm->addCash(50, 1), "You should get true when adding one 50");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 1, 50 => 1, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 50 to a one 200/100 machine should change the remaining cash accordingly"
        );
        // Add a 20
        $this->assertTrue($atm->addCash(20, 1), "You should get true when adding one 20");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 20 to a one 200/100/50 machine should change the remaining cash accordingly"
        );
        // Add a 10
        $this->assertTrue($atm->addCash(10, 1), "You should get true when adding one 10");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 1, 5 => 0],
            $atm->getRemainingCash(),
            "Adding one 10 to a one 200/100/50/20 machine should change the remaining cash accordingly"
        );
        // Add a 5
        $this->assertTrue($atm->addCash(5, 1), "You should get true when adding one 5");
        $this->assertSame(
            [500 => 0, 200 => 1, 100 => 1, 50 => 1, 20 => 1, 10 => 1, 5 => 1],
            $atm->getRemainingCash(),
            "Adding one 10 to a one 200/100/50/20 machine should change the remaining cash accordingly",
        );
    }

    /**
     * @depends testSuccessiveDifferentAdds
     */
    public function testWithdrawExists(CashMachine $atm): CashMachine
    {
        $this->assertTrue(method_exists($atm, "getRemainingCash"), "Your CashMachine class should have a getRemainingCash method");
        return $atm;
    }

    /**
     * @depends testWithdrawExists
     */
    public function testSimpleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(50, 2);
        $this->assertSame([50 => 1], $atm->withdraw(50));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 1, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    /**
     * @depends testWithdrawExists
     */
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

    /**
     * @depends testWithdrawExists
     */
    public function testMultipleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 1], $atm->withdraw(100));
        $this->assertSame([500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        $this->assertSame([100 => 1], $atm->withdraw(100));
        $this->assertSame([500 => 0, 200 => 0, 100 => 98, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    /**
     * @depends testWithdrawExists
     */
    public function testNonOptimapWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(20, 10);
        $this->assertSame([20 => 4], $atm->withdraw(80));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 6, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    /**
     * @depends testWithdrawExists
     */
    public function testMultipleNonOptimalWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 10], $atm->withdraw(1000));
        $this->assertSame([500 => 0, 200 => 0, 100 => 90, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
        $this->assertSame([100 => 5], $atm->withdraw(500));
        $this->assertSame([500 => 0, 200 => 0, 100 => 85, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    /**
     * @depends testWithdrawExists
     */
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

    /**
     * @depends testWithdrawExists
     */
    public function testRoundedWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame([100 => 1], $atm->withdraw(123));
        $this->assertSame([500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }

    /**
     * @depends testWithdrawExists
     */
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

    /**
     * @depends testWithdrawExists
     */
    public function testNotEnoughWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(10, 1);
        $atm->addCash(5, 1);
        $this->assertSame([10 => 1, 5 => 1], $atm->withdraw(40));
        $this->assertSame([500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0], $atm->getRemainingCash());
    }    
}