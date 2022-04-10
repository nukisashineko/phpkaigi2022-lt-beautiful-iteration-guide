<?php

use GuzzleHttp\Promise\PromiseInterface;


class Example3 {
    private static function is_odd(int $x): bool
    {
        return $x % 2 === 1;
    }

    private  static function is_even(int $x): bool
    {
        return $x % 2 === 0;
    }

    private static function promise__is_odd_then_power_2(\GuzzleHttp\Client $client, int $x)
    : \GuzzleHttp\Promise\PromiseInterface {
        return  self::is_odd($x)?
            $client->requestAsync('GET', "http://example.com/power?x={$x}"):
            $client->requestAsync('GET', "http://example.com/identity?x={$x}");
    }

    private static function array_map__is_odd_then_power_2(array $list): array {
        $client = new \GuzzleHttp\Client();
        $result = [];
        foreach ($list as $x){
            $promise = self::promise__is_odd_then_power_2($client, $x);
            $responses = \GuzzleHttp\Promise\all([$promise])->wait();
            foreach ($responses as $response) {
                $result[] = (int)json_decode($response->getBody()->getContents(), true)['result'];
            }
        }
        return $result;
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

    public static function main(){
        $array1 =                        self::array_map__is_odd_then_power_2([1, 2, 3]);
        $array2 = iterator_to_array(self::lazy__array_map__is_odd_then_power_2([1, 2, 3]));
        var_dump($array1 === $array2); # => true
    }
}

Example3::main();

?>
