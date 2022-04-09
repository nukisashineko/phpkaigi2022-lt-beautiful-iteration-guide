<?php

class Example1
{
    private static function is_odd(int $x): bool
    {
        return $x % 2 === 1;
    }

    private static function is_even(int $x): bool
    {
        return $x % 2 === 0;
    }

    private static function array_map__is_odd_then_power_2(array $list): array
    {
        $list2 = [];
        foreach ($list as $x) {
            $list2[] = self::is_odd($x) ? $x ** 2 : $x;
        }
        return $list2;
    }

    private static function array_filter__is_even(array $list2): array
    {
        $even_list = [];
        foreach ($list2 as $x) {
            if (self::is_even($x)) {
                $even_list [] = $x;
            }
        }
        return $even_list;
    }

    private static function array_filter__is_odd(array $list2): array
    {
        $odd_list = [];
        foreach ($list2 as $x) {
            if (self::is_odd($x)) {
                $odd_list [] = $x;
            }
        }
        return $odd_list;
    }


    private static function array_sum(array $list): array
    {
        $sum = 0;
        foreach ($list as $x) {
            $sum += $x;
        }
        return $sum;
    }

    public static function main()
    {
        $list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        // 奇数の場合は2乗した値に置き換えて！
        $list2 = self::array_map__is_odd_then_power_2($list);

        // 偶数・奇数で配列を分けてほしい！
        $odd_list = self::array_filter__is_odd($list2);
        $even_list = self::array_filter__is_even($list2);

        // 分けた配列の各小計を出してほしい！
        $odd_sum = self::array_sum($odd_list);
        $even_sum = self::array_sum($even_list);

        $a = '';
    }
}

Example1::main();

?>
