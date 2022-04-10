<?php
require_once 'vendor/autoload.php';

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

        // 奇数の場合は2乗した値に置き換えて！
        $list2 = [];
        foreach ($list as $x){
            $list2[] = self::is_odd($x)? $x ** 2: $x;
        }

        // 偶数・奇数で配列を分けてほしい！
        $odd_list = [];
        foreach ($list2 as $x){
            if(self::is_odd($x)){
                $odd_list[] = $x;
            }
        }
        $even_list = [];
        foreach ($list2 as $x){
            if(self::is_even($x)){
                $even_list[] = $x;
            }
        }

        // 分けた配列の各小計を出してほしい！
        $odd_sum = 0;
        foreach($odd_list as $x){
            $odd_sum += $x;
        }
        $even_sum = 0;
        foreach($even_list as $x){
            $even_sum += $x;
        }
    }
}

Example1::main();

?>
