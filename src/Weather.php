<?php

namespace Tien\Weather;

use GuzzleHttp\Client;
use Tien\Weather\Exceptions\InvalidArgumentException;
use Tien\Weather\Exceptions\HttpException;
use Tien\Weather\Exceptions\Exception;

class Weather
{

    /**
     * [$key description]
     * @var [type]
     */
    protected $key;

    protected $guzzleOptions = [];

    /**
     * @Author   Tien
     * @DateTime 2018-11-13T22:55:21+0800
     * @param    string
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }



    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * @Author   Tien
     * @DateTime 2018-11-13T23:11:07+0800
     * @param    array
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }


    /**
     * @param string $city
     * @param string $type
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getWeather(string $city, string $type = 'base', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: '.$format);
        }

        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): '.$type);
        }

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => \strtolower($format),
            'extensions' =>  \strtolower($type),
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }



}