<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jarekkozak\kie;

use Httpful\Request;
use yii\base\Object;

/**
 * Description of KieClient
 *
 * @author Jarosław Kozak <jaroslaw.kozak68@gmail.com>
 */
class KieClient extends Object
{
    public $context;
    public $container;
    public $username;
    public $password;

    /* @var $response \Httpful\Response */
    protected $response;

    const SERVER_PATH     = '/services/rest/server';
    const CONTAINERS_PATH = '/containers';

    /**
     * GET command
     * @return bool true if ok 
     */
    public function GET($url)
    {
        $this->response = Request::get($url)
            ->authenticateWith($this->username, $this->password)
            ->expectsType('xml')
            ->send();
        if($this->isOk()){
            return true;
        }
        return false;
    }

    /**
     * POST command
     * @return bool true if ok
     */
    public function POST($url,$data)
    {
        $this->response = Request::post($url,$data,'xml')
            ->authenticateWith($this->username, $this->password)
            ->expectsType('xml')
            ->send();
        if($this->isOk()){
            return true;
        }
        return false;
    }

    /**
     * Gets server url + add server path if necessary
     * @param string $path
     * @return string
     */
    public function getServerUrl($path = null)
    {
        $url = $this->context.self::SERVER_PATH;
        if ($path !== null) {
            $url .= '/'.$path;
        }
        return $url;
    }

    /**
     * Checks if call was succesful
     * @return type
     */
    public function isOk()
    {
        return $this->response != null && !$this->response->hasErrors();
    }

    /**
     * Gets server info
     * @return boolean|array FALSE in case of error array with data
     */
    public function getServerInfo()
    {
        $this->GET($this->getServerUrl());
        if (!$this->isOk()) {
            return FALSE;
        }
        $result = $this->getKieResponse();
        if(!$result->isSuccess()){
            return FALSE;
        }
        return $result->getData()['kie-server-info'];
    }

    public function getKieResponse()
    {
        if ($this->isOk()) {
            return new KieResponse(['body' => $this->response->body]);
        }
        return FALSE;
    }
}