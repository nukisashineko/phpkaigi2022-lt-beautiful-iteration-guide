<?php

class Example1 {
    private static function is_odd(int $x): bool
    {
        return $x % 2 === 1;
    }

    private  static function is_even(int $x): bool
    {
        return $x % 2 === 0;
    }

    private static function array_map__is_odd_then_power_2(array $list): array{
        $list2 = [];
        foreach ($list as $x){
            $list2[] = self::is_odd($x)? $x ** 2: $x;
        }
        return $list2;
    }
    private static function array_sum(array $list){
        $sum = 0;
        foreach($list as $x){
            $sum += $x;
        }
        return $sum;
    }

}

Example1::main();

?>