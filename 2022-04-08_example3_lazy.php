<?php

class Example3
{
    public static function array_map(callable $func, array $values): array
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = $func($value);
        }
        return $result;
    }

    public static function lazy_array_map(callable $func, array $values): Generator
    {
        foreach ($values as $value) {
            yield $func($value);
        }
    }

    public static function main(){
        $array1 =                        self::array_map(function($x){ return $x * 2; }, [1, 2, 3]);
        $array2 = iterator_to_array(self::lazy_array_map(function($x){ return $x * 2; }, [1, 2, 3]));
        var_dump($array1 === $array2); # => true
    }

}

Example3::main();

?>