<?php
namespace App\Utilities;

/**
 * Created by PhpStorm.
 * User: noibilism
 * Date: 6/26/19
 * Time: 9:56 PM
 */
class Redis
{
    private $redis;

    public function __construct()
    {
        $this->redis = \Illuminate\Support\Facades\Redis::connection();
    }

    public function set($key, $data) {
        $result = $this->redis->set($key, $data);
        return $result;
    }

    public function put($key, $data){
        $result = $this->redis->setex($key, 60*60*24, $data);
        return $result;
    }

    public function putForAnHour($key, $data){
        $result = $this->redis->setex($key, 60*60*1, $data);
        return $result;
    }

    public function get($key){
        $result = $this->redis->get($key);
        return $result;
    }

    public function delete($key){
        $result = $this->redis->del($key);
        return $result;
    }

    public function putFor($key, $data, $time)
    {
        return $this->redis->setex($key, $time, $data);
    }

}