<?php
namespace Pranto;

require ROOT . '/pranto/country/geoip2.phar';
use GeoIp2\Database\Reader;

class Country
{
    private $reader, $record;

    public function __construct()
    {
        $this->reader = new Reader(ROOT . '/pranto/country/GeoLite2-Country.mmdb');

        if ($_SERVER["REMOTE_ADDR"] == "::1") {
            $this->record = $this->reader->country("8.8.8.8");
        } else {
            $this->record = $this->reader->country($_SERVER["REMOTE_ADDR"]);
        }
    }

    public function get_country_code()
    {
        return $this->record->country->isoCode;
    }

    public function get_country_name()
    {
        return $this->record->country->name;
    }
}
