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
    public function testWithdrawExists(): void
    {
        $atm = new CashMachine();
        $this->assertTrue(method_exists($atm, "withdraw"), "Your CashMachine class should have a withdraw method");
    }

    /**
     * @depends testWithdrawExists
     */
    public function testSimpleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(50, 2);
        $this->assertSame([50 => 1], $atm->withdraw(50), "Withdraw 50 should get you one 50 if the machine have some");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 1, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 50 from a machine with two should let one remain"
        );
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
        $this->assertSame(
            [500 => 1, 200 => 1, 100 => 1, 50 => 1, 20 => 2, 5 => 1],
            $atm->withdraw(895),
            "Withdraw 895 should get you one of each 500/200/100/50/5 and two 20 if the machine have enough"
        );
        $this->assertSame(
            [500 => 9, 200 => 9, 100 => 9, 50 => 9, 20 => 8, 10 => 10, 5 => 9],
            $atm->getRemainingCash(),
            "Withdrawing 895 from a machine with ten of each bill should change the machine accordingly"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testMultipleWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame(
            [100 => 1],
            $atm->withdraw(100),
            "Withdraw 100 should get you one 100 if the machine have enough"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 100 from a machine with a hundred 100 should left 99 remaining"
        );
        $this->assertSame(
            [100 => 1],
            $atm->withdraw(100),
            "A second withdraw 100 should get you one 100 if the machine have enough"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 98, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing  a second 100 from a machine with a hundred 100 should left 98 remaining"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testNonOptimapWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(20, 10);
        $this->assertSame(
            [20 => 4],
            $atm->withdraw(80),
            "Withdraw 80 should get you four 20 if the machine have only 20s"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 6, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 80 from a machine with ten 20s should left six 20 remaining"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testMultipleNonOptimalWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame(
            [100 => 10],
            $atm->withdraw(1000),
            "Withdraw 1000 should get you ten 100s if the machine have only 100s"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 90, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 1000 from a machine with hundred 100s should left ninety 100s remaining"
        );
        $this->assertSame(
            [100 => 5],
            $atm->withdraw(500),
            "Withdraw 500 should get you five 100s if the machine have only 100s"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 85, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 500 from a machine with nintey 100s should left 85 remaining"
        );
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
        $this->assertSame(
            [100 => 3, 10 => 5, 5 => 1],
            $atm->withdraw(355),
            "Withdrawing 355 from a machine with 100/10/5 bills should get you three 100s, five 10s and one five"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 7, 50 => 0, 20 => 0, 10 => 0, 5 => 99],
            $atm->getRemainingCash(),
            "A machine with ten 100s, five 10s and a hundred 5s, after a 355 withdraw should have seven 100s, zero 10s and a nienty-nine 5s left"
        );
        $this->assertSame(
            [100 => 7, 5 => 31],
            $atm->withdraw(855),
            "Withdrawing 855 from a machine with seven 100s and ninety-nine 5s shoud get you seven 100s and thirty-one 5s"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 68],
            $atm->getRemainingCash(),
            "A machine with seven 100s and nienty-nine 5s, after a 855 withdraw should have only sixty-eight 5s left"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testRoundedWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(100, 100);
        $this->assertSame(
            [100 => 1],
            $atm->withdraw(123),
            "Withdrawing 123 from a machine with only 100s should get you one 100"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 99, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "A machine with hundred 100s after a 123 withdraw should have ninety-nine 100s left"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testImpossibleWithdraw(): void
    {
        $atm = new CashMachine();
        $this->assertSame([], $atm->withdraw(5), "Withdraw 5 from an empty machine should get you an empty array");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "An empty machine after a 5 withdraw should still be empty (0 of each bills)"
        );
        $atm->addCash(10, 1);
        $this->assertSame([], $atm->withdraw(0), "Withdraw 0 should get you an empty array");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 1, 5 => 0],
            $atm->getRemainingCash(),
            "A machine with one 10 should remain unchanged after a withdraw of 0"
        );
        $this->assertSame([], $atm->withdraw(-20), "A negative withdraw (-20) should get you an empty array");
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 1, 5 => 0],
            $atm->getRemainingCash(),
            "A machine with one 10 should remain unchanged after a withdraw of -20"
        );
    }

    /**
     * @depends testWithdrawExists
     */
    public function testNotEnoughWithdraw(): void
    {
        $atm = new CashMachine();
        $atm->addCash(10, 1);
        $atm->addCash(5, 1);
        $this->assertSame(
            [10 => 1, 5 => 1],
            $atm->withdraw(40),
            "Withdrawing 40 from a machine with one 10 and one 5 should get you one 10 and one 5"
        );
        $this->assertSame(
            [500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0],
            $atm->getRemainingCash(),
            "Withdrawing 40 from a machine with one 10 and one 5 should left the machine empty (0 of each bills)"
        );
    }
}