<?php

namespace mgcode\curl;

class HttpExecute extends \yii\base\Component
{
    public $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Expect:')); // for lightpd
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 5);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'DC Internal');
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
    }

    public function setOpt($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
    }

    public function execGet($url)
    {
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_NOBODY, false);
        return $this->_exec();
    }

    public function execPost($url, $post)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($this->curl, CURLOPT_NOBODY, false);
        return $this->_exec();
    }

    public function check($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_NOBODY, true);
        return $this->_exec();
    }

    public function fileExists($url)
    {
    }

    public function getFileContents($url)
    {
        curl_setopt($this->curl, CURLOPT_POST, 0);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        return $this->_exec();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    protected  function _exec()
    {
        $data = curl_exec($this->curl);
        $out = array(
            'code' => intval(curl_getinfo($this->curl, CURLINFO_HTTP_CODE)),
            'data' => $data,
            'contentType' => curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE),
            'info' => curl_getinfo($this->curl),
            'error' => curl_error($this->curl),
        );
        return $out;
    }
}