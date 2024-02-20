<?php
declare(strict_types=1);

class Storage
{
    protected array $store = [];


    public function get(string $key, \Closure $call, int $ttl)
    {
        $time = time();

        if (isset($this->store[$key]) && $this->store[$key]['ttl'] <= $time) {
            unset($this->store[$key]);
        }

        return $this->store[$key] ??= [
            'ttl' => $time + $ttl,
            'data' => $call()
        ];
    }
}


function callWithCache(string $path, string $method, array $body, int $ttl): void
{

    $store = new Storage();


    $store->get("$path.$method." . md5(json_encode($body)), function () {
        // some query
    }, $ttl);

}

callWithCache('https://youtube.com', 'Post', body, 300);


$observable = new Observable();

$unsubscribe1 = $observable->subscribe(function () {
    echo "aa\n";
});

$unsubscribe2 = $observable->subscribe(function () {
    echo "bb\n";
});

$observable->notify();

$unsubscribe1();

