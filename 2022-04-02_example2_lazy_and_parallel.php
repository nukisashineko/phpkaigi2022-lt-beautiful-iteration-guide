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

    private static function identityAsync(int $x): \GuzzleHttp\Promise{
        $defer = new \GuzzleHttp\Promise();
        $defer->resolve($x);
        return $defer;
    }

    private static function promise__is_odd_then_power_2(\GuzzleHttp\Client $client, int $x)
    : \GuzzleHttp\Promise {
        return  self::is_odd($x)?
            $client->requestAsync('GET', "https://example.com/math/power/2/{$x}"):
            self::identityAsync($x);
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
                yield (int)$response->getBody()->getContents();
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

}

Example1::main();

?>