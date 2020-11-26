<?php

/**
 * A cash machine simulator
 */

declare(strict_types=1);

class CashMachine
{
    private $availableBills = [
        500 => 0,
        200 => 0,
        100 => 0,
        50 => 0,
        20 => 0,
        10 => 0,
        5 => 0,
    ];

    public function addCash(int $bill, int $number): bool
    {
        if ($number >= 0 && isset($this->availableBills[$bill])) {
            $this->availableBills[$bill] += $number;
            return true;
        }
        return false;
    }

    public function getRemainingCash(): array
    {
        return $this->availableBills;
    }

    public function withdraw(int $amount): array
    {
        $result = [];
        foreach ($this->availableBills as $bill => $availableNumber) {
            $maxNumber = intdiv($amount, $bill);
            if ($maxNumber > 0) {
                if ($maxNumber <= $availableNumber) {
                    $this->availableBills[$bill] -= $maxNumber;
                    $result[$bill] = $maxNumber;
                    $amount %= $bill;
                } else {
                    if ($availableNumber > 0) {
                        $result[$bill] = $availableNumber;
                        $amount -= $availableNumber * $bill;
                        $this->availableBills[$bill] = 0;
                    }
                }
            }
        }
        return $result;
    }
}