<?php
namespace App\Services\Meneame\Request;

use App\Services\Meneame\Cache\Cache;
use App\Services\Meneame\Parser\Xml;

class Curl
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $attach = array();

    /**
     * @var resource
     */
    private $curl;

    /**
     * @param string $host
     * @param array  $attach
     *
     * @return self
     */
    public function __construct($host, array $attach = [])
    {
        $this->host = $host;
        $this->attach = $attach;
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @return string
     */
    public function get($url, $data = [])
    {
        $key = Cache::key($url, $data);

        if (Cache::exists($key)) {
            return Cache::get($key);
        }

        $this->init($url);

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query(array_merge($data, $this->attach)));

        return $this->exec($key);
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @return array
     */
    public function getJson($url, $data = [])
    {
        return json_decode($this->get($url, $data));
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @return array
     */
    public function getXml($url, $data = [])
    {
        $xml = Xml::fromString(preg_replace('#<(/?)[a-zA-Z0-9]+:#', '<$1', $this->get($url, $data)));

        $items = array();

        foreach ($xml['rss']['channel'][0]['item'] as $item) {
            $items[] = Xml::stringToObject($item['@value']);
        }

        return $items;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    private function init($url)
    {
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->host.$url);
        curl_setopt($this->curl, CURLOPT_FAILONERROR, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, false);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0');

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function exec($key)
    {
        return Cache::set($key, curl_exec($this->curl));
    }
}
