<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;
use Httpful\Request;

/**
 * Description of CurrencyHelper
 *
 * @author BMW
 */



class CurrencyHelper {
    
    private static $_api_url = 'http://apilayer.net/api';
    private static $_live = '/live';
    private static $_convert = '/convert?';
    
    private static $_api_key = '311558e559f5c8fdad073db27954d222';
    
    
    public function __construct($api_key = null,$api_url = null) {
        if($api_url) {
            $this->_api_url = $api_url;
        }
        if($api_key) {
            $this->_api_key = $api_key;
        }

             
    }
    public static function convert($from,$to,$amount) {
        
        try {
            $url = self::$_api_url . self::$_live .
                    '?access_key=' . self::$_api_key;
            $response = \Httpful\Request::get($url)->send();
            if ($response->code != 200) {
                 throw new \Exception("An error occured !<br />".$response->body->error->info);
            }
            
            if(!$response->body) {
                 throw new \Exception("An error occured !");
            }
            if(isset($response->body->error)) {
                 throw new \Exception("An error occured !<br />".print_r($response->body->error));
            }
            // nazwy walut w odpowiedzi (USDUSD)
            $from_currency = 'USD'.$from;
            $to_currency = 'USD'.$to;
           
            // przeliczniki ze wskazanej na USD oraz z docelowego na USD
            $from_to_usd_ratio = $response->body->quotes->$from_currency;
            $to_usd_ratio = $response->body->quotes->$to_currency;
            if(!$from_to_usd_ratio || !$to_usd_ratio) {
                throw new \Exception("Country not found in API");
            }
            
            //przeliczenia
            $from_to_usd = $amount/$from_to_usd_ratio;
            $cash = $from_to_usd*$to_usd_ratio;
         
            $returnData = func_get_args();
            $returnData['cash'] = $cash;
            
            return json_encode($returnData);
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            // miejsce na logi
        }
        return false;
    }


}
