# A cash machine simulator

## Skills & concepts

* Arithmetic
* Arrays
* OOP

## Set-up

This Dojo uses PHPunit for unit testing. After cloning the repository, you need to install it using composer :
```bash
composer install
```

## Goals

The goal of this dojo is to simulate a cash machine.

We're assuming that we are dealing with euros so the possible bills the machine can contains and delivers are :

- 500
- 200
- 100
- 50
- 20
- 10
- 5

### Step 1

Define a CashMachine class in the src/CashMachine.php file.

### Step 2

Add a *addCash* method to this CashMachine class having two parameters :

- The bill value
- The number of bill added to the machine

and returning true if the bills were successfully added to the machine and false for problems.

For examples if you try to add a negative number of bills, or if the bill value is not one of the possible euro bills.

### Step 3

Add a *getRemainingCash* method returning an array containing the number of each bills in the machine.

For example, a machine containing 10 of each of the 100, 50 and 10 bills returns this array :  

```php 
[500 => 0, 200 => 0, 100 => 10, 50 => 10, 20 => 0, 10 => 10, 5 => 0] 
```

### Step 4

Add a *withdraw* method having an amount of wanted cash as parameter and returning the array of obtained bills.

The machine delivers the minimum number of bills according to the bills it contains and the maximum amount it can possibly deliver.

For example, a 235 withdraw from a machine containing a lot of every bills should return this array :  

```php 
[200 => 1, 20 => 1, 10 => 1, 5 => 1] 
```

But if the machine contains only 10's and 5's bills it will returns 
```php 
[10 => 23, 5 => 1] 
```

If the machine doesn't have enough, it delivers the maximum possible.
For example a 55 withdrawn, from a machine containing one 10 and one 5 will return
```php 
[10 => 1, 5 => 1] 
```

If the amount is not a 5 multiple, the remain is simply not delivered.
For example a 8 withdrawn, supposed there's at least one 5 in the machine will return
```php 
[5 => 1] 
```

And of course a withdraw of O or less returns an empty array.

## Test

You can run some predefined tests on your function using 
```bash
vendor/bin/phpunit test
```