<?php
require_once 'vendor/autoload.php';

use GuzzleHttp\Promise\PromiseInterface;


class Example2 {
    private static function is_odd(int $x): bool
    {
        return $x % 2 === 1;
    }

    private  static function is_even(int $x): bool
    {
        return $x % 2 === 0;
    }

    private static function lazy__array_filter__is_even(Generator $list2): Generator
    {
        foreach ($list2 as $x) {
            if (self::is_even($x)) {
                yield $x;
            }
        }
    }

    private static function lazy__array_filter__is_odd(Generator $list2): Generator
    {
        foreach ($list2 as $x) {
            if (self::is_odd($x)) {
                yield  $x;
            }
        }
    }

    private static function promise__is_odd_then_power_2(\GuzzleHttp\Client $client, int $x)
    : \GuzzleHttp\Promise\PromiseInterface {
        return  self::is_odd($x)?
            $client->requestAsync('GET', "http://example.com/power?x={$x}"):
            $client->requestAsync('GET', "http://example.com/identity?x={$x}");
    }

    private static function lazy__array_map__is_odd_then_power_2(array $list): Generator{
        $client = new \GuzzleHttp\Client();

        $chunked_list = array_chunk($list, 10);
        foreach ($chunked_list as $_list){
            $promises = [];
            foreach($_list as $x){
                $promises[] = self::promise__is_odd_then_power_2($client, $x);
            }
            $responses = \GuzzleHttp\Promise\all($promises)->wait();
            foreach ($responses as $response) {
               yield (int)json_decode($response->getBody()->getContents(), true)['result'];
            }
        }
    }

    private static function array_sum(array $list){
        $sum = 0;
        foreach($list as $x){
            $sum += $x;
        }
        return $sum;
    }

   private static function lazy__array_take(Generator $list, int $count): Generator{
        $i = 0;
        foreach($list as $x){
            if($count <= $i){
                break;
            }
            yield $x;
           $i += 1;
        }
    }

    public static function main()
    {
        $list = range(1, 100);

        // 奇数の場合は2乗した値に置き換えて！
        $list2 = self::lazy__array_map__is_odd_then_power_2($list);
        $list3 = self::lazy__array_map__is_odd_then_power_2($list);

        // 偶数・奇数で配列を分けてほしい！
        // 先頭５つまで
        $odd_list = iterator_to_array(self::lazy__array_take(self::lazy__array_filter__is_odd($list2),5));
        $even_list =  iterator_to_array(self::lazy__array_take(self::lazy__array_filter__is_even($list3),5));

        // 分けた配列の各小計を出してほしい！
        $odd_sum = self::array_sum($odd_list);
        $even_sum = self::array_sum($even_list);
    }

}

Example2::main();

?>
