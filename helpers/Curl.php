<?php

namespace app\helpers;

class Curl
{
    private $_ch;
    private $response;

    public $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER         => true,
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_COOKIESESSION => true,
        CURLOPT_HTTPHEADER => array('Accept-Encoding:gzip'),
        CURLOPT_ENCODING => 'gzip,deflate',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116',
        CURLOPT_COOKIE => 'XDEBUG_SESSION=1',
    );

    public function __construct()
    {
        $this->init();
    }

    public function __destruct()
    {
        curl_close($this->_ch);
    }

    public function init()
    {
        try {
            $this->_ch = curl_init();
            $this->setOptions($this->options);

        } catch (\Exception $e) {
            throw new \Exception('curl not installed');
        }
    }

    private function exec($url)
    {
        $this->setOption(CURLOPT_URL, $url);
        $this->response = curl_exec($this->_ch);
        if (!curl_errno($this->_ch)) {
            $header_size = curl_getinfo($this->_ch, CURLINFO_HEADER_SIZE);
            $body = substr($this->response, $header_size);
            return $body;
        }

        return false;
    }

    public function get($url, $params = array())
    {
        $this->setOption(CURLOPT_HTTPGET, true);

        return $this->exec($this->buildUrl($url, $params));
    }

    public function post($url, $data = array())
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->exec($url);
    }

    public function buildUrl($url, $data = array())
    {
        $parsed = parse_url($url);
        isset($parsed['query']) ? parse_str($parsed['query'], $parsed['query']) : $parsed['query'] = array();
        $params = isset($parsed['query']) ? array_merge($parsed['query'], $data) : $data;
        $parsed['query'] = ($params) ? '?' . http_build_query($params) : '';
        if (!isset($parsed['path'])) {
            $parsed['path']='/';
        }

        $parsed['port'] = isset($parsed['port'])?':'.$parsed['port']:'';

        return $parsed['scheme'].'://'.$parsed['host'].$parsed['port'].$parsed['path'].$parsed['query'];
    }

    public function setOptions($options = array())
    {
        curl_setopt_array($this->_ch, $options);

        return $this;
    }

    public function setOption($option, $value)
    {
        curl_setopt($this->_ch, $option, $value);

        return $this;
    }

    public function getHeaders()
    {
        $headers = array();

        $header_arr = explode("\r\n\r\n",$this->response);
        $header_text = $header_arr[1];

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getError()
    {
        return curl_error($this->_ch);
    }

}