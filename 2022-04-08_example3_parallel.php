<?php

class Example3
{
    private static function identity_async(int $x): \GuzzleHttp\Promise{
        $defer = new \GuzzleHttp\Promise();
        $defer->resolve($x);
        return $defer;
    }

    private static function normal_processing(array $list): array {
        $responses = [];
        foreach($list as $x){
            $promise = self::identity_async($x);
            $responses[] = \GuzzleHttp\Promise\all([$promise])->wait();
        }

        $result = [];
        foreach ($responses as $response) {
            $result[] = (int)$response->getBody()->getContents();
        }
        return $result;
    }

    private static function parallel_processing(array $list): array {
        $promises = array_map([self::class, 'identity_async'], $list);
        $responses = \GuzzleHttp\Promise\all($promises)->wait();

        $result = [];
        foreach ($responses as $response) {
            $result[] = (int)$response->getBody()->getContents();
        }
        return $result;
    }

    private static function lazy__parallel_processing(array $list): Generator{
        $promises = array_map([self::class, 'identity_async'], $list);
        $responses = \GuzzleHttp\Promise\all($promises)->wait();
        foreach ($responses as $response) {
            yield (int)$response->getBody()->getContents();
        }
    }

    public static function main()
    {
        $array1 = self::normal_processing( [1, 2, 3]);
        $array2 = self::parallel_processing( [1, 2, 3]);
        $array3 = iterator_to_array(self::lazy__parallel_processing( [1, 2, 3]));
        var_dump($array1 === $array2); # => true
        var_dump($array2 === $array3); # => true
    }
}

Example3::main();
?>
