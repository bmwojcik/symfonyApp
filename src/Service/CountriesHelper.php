<?php

namespace App\Service;
use Httpful\Request;

class CountriesHelper {
    
    private $_api_url = 'https://restcountries.eu';
    private $_all_countries_url = '/rest/v2/all';
    private $_single_country_url = '/rest/v2/alpha/';
    
    
    public function __construct($api_url = null) {
        if($api_url) {
            $this->_api_url = $api_url;
        }
    }
    
    /*
     * Metoda odpowiadajaca za pobranie wszystkich krajów
     * 
     * @return Response obiect
     * @return bool
     */
    public function getAllCountries() {
                
        $url = $this->_api_url.$this->_all_countries_url;
        
        try {
            $response = \Httpful\Request::get($url)->send();
            if ($response->code == 200) {
                return $response;
            }
            throw new \Exception("Couldn't get data !");
        } catch (Exception $ex) {
            echo $ex->getMessage();
            // miejsce na logi
        }
        return false;
      
    }
    /*
     * Metoda wydobywająca z obiektu typu Response interesujące nas dane czyli
     * @kraj
     * @kod kraju
     * @walute
     */

    public function getDataFromCountries($data) {
        if (!$data) {
            return false;
        }
        $returnData = array();
        if ($data->body) {
            foreach ($data->body as $country) {
                $element['name'] = $country->name;
                $element['capital'] = $country->capital;
                $element['currency'] = $country->currencies[0]->code;
                $element['alpha'] = $country->alpha3Code;
                array_push($returnData, $element);
            }
            sort($returnData);
            return json_encode($returnData);
        }
    }
    
    public function getSingleCountry($data) {
        if (!$data) {
            return false;
        }
        $url = $this->_api_url.$this->_single_country_url.$data;
        try {
            $response = \Httpful\Request::get($url)->send();
            if ($response->code == 200) {
                return $response;
            }
            throw new \Exception("Couldn't get data !<br />".print_r($response));
        } catch (Exception $ex) {
            echo $ex->getMessage();
            // miejsce na logi
        }
        return false;
    }

}