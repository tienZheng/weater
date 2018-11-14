<?php
/**
 * Created by PhpStorm.
 * User: zhengcheng
 * Date: 2018/11/14
 * Time: 11:17 AM
 */

namespace Tien\Weather;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    protected $defer = true;


    public function register()
    {
        $this->app->singleton(Weather::class, function() {
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }


    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}