<?php

class CashMachine
{
    private array $availableBills = [
        500 => 0,
        200 => 0,
        100 => 0,
        50 => 0,
        20 => 0,
        10 => 0,
        5 => 0
    ];

    public function addCash(int $billValue, int $numberBills)
    {
        if (isset($this->availableBills[$billValue]) && $numberBills >= 0) {
            $this->availableBills[$billValue] += intval($numberBills);
            return true;
        }

        return false;
    }

    public function getRemainingCash()
    {
        return $this->availableBills;
    }

    public function withdraw($amountToWithdraw)
    {
        $deliveredBills = [];

        if ($amountToWithdraw < 0) return $deliveredBills;

        foreach ($this->availableBills as $bill => $quantityAvailable) {
            $nbBills = min($quantityAvailable, intdiv($amountToWithdraw, $bill));

            if ($nbBills > 0) {
                $this->availableBills[$bill] -= $nbBills;
                $deliveredBills[$bill] = $nbBills;
                $amountToWithdraw -=  $bill * $nbBills;
            }
        }

        return $deliveredBills;
    }
}
