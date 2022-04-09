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

    public static function main(){
        $list = [1,2,3,4,5,6,7,8,9,10];
        $odd_list = [];  $even_list = [];
        $odd_sum = 0;    $even_sum = 0;
        foreach($list as &$x){
            // 奇数の場合は2乗した値に置き換えて！
            if(self::is_odd($x)){
                $x = $x ** 2;
            }
            // 偶数・奇数で配列を分けてほしい！
            // 分けた配列の各小計を出してほしい！
            if(self::is_odd($x)){
                $odd_list[] = $x;
                $odd_sum += $x;
            }
            if(self::is_even($x)){
                $even_list[] = $x;
                $even_sum += $x;
            }
        }
    }
}

Example1::main();

?>