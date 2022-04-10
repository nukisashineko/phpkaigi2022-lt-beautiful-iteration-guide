<?php
require_once 'vendor/autoload.php';

class Example2_Ex {
    private  static function is_user_deleted(int $user): bool
    {
        return $user['deleted_at'] !== null;
    }

    private static function lazy__array_map__convert_userinfo(array $list): Generator{
        $client = new \GuzzleHttp\Client();

        $chunked_list = array_chunk($list, 10);
        foreach ($chunked_list as $_list){
            $promises = [];
            foreach($_list as $x){
                $promises[] = $client->requestAsync('GET', "https://example.com/userinfo/{$x}");
            }
            $responses = \GuzzleHttp\Promise\all($promises)->wait();
            foreach ($responses as $response) {
                yield (int)json_decode($response->getBody()->getContents(), true)['user'];
            }
        }
    }

    private static function lazy__array_filter__is_user_deleted(Generator $list2): Generator{
        foreach ($list2 as $user){
            if(self::is_user_deleted($user)){
                yield $user;
            }
        }
    }

    private static function lazy__array_filter__is_user_current_member(Generator $list2): Generator{
        foreach ($list2 as $user){
            if(!self::is_user_deleted($user)){
                yield $user;
            }
        }
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

    private static function array_sum(array $list){
        $sum = 0;
        foreach($list as $x){
            $sum += $x;
        }
        return $sum;
    }

    private static function array_column(array $list, string $column_key): array
    {
        $column_list = [];
        foreach($list as $x){
            $column_list[]= $x[$column_key];
        }
        return $column_list;
    }

    public static function main(){
        $list = range(1, 100);

        // ユーザーに変換してほしい(API)
        $list2 = self::lazy__array_map__convert_userinfo($list);
        $list3 = self::lazy__array_map__convert_userinfo($list);

        // deleted_atで配列に分けてほしい！
        // 先頭各５個ずつまで
        $deleted_users =
            iterator_to_array(self::lazy__array_take(self::lazy__array_filter__is_user_deleted($list2), 5));
        $current_member_users =
            iterator_to_array(self::lazy__array_take(self::lazy__array_filter__is_user_current_member($list3), 5));

        // 課金額の各小計を出してほしい！
        $deleted_users_sum_billing_amount =
            self::array_sum(self::array_column($deleted_users, 'billing_amount'));
        $current_member_users_sum_billing_amount =
            self::array_sum(self::array_column($current_member_users, 'billing_amount'));
    }
}

Example2_Ex::main();

?>
