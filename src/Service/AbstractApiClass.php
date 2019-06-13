<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

/**
 * Description of AbstractApiClass
 *
 * @author BMW
 */
abstract class AbstractApiClass {
    //put your code here
    protected $_api_url;
    
    public function __construct($apiUrl) {
        $this->_api_url = $apiUrl;
    }
    
    public function setApiUrl(string $apiUrl) {
        $this->_api_url = $apiUrl;
    }
    public function getApiUrl() {
        return $this->_api_url;
    }
}
