<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\TaskController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class TaskControllerTest extends WebTestCase
{
    /*
     * test na dzialanie akcji task/index
     */
    public function testShowTask()
    {
        $client = static::createClient();

        $client->request('GET', '/task/index');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /*
     * test na dzialanie przeslania formularza
     */
    public function testFormAction() 
    {
         $client = static::createClient();

        $client->request('POST', '/task/getForm',['departure' => 'test_val','arrival' => 'test_value' , 'from_currency' => '' ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    /*
     * testy na przesylanie przez formularz przykladowych danych
     * 
     * @return RUB
     */
    public function testCheckRequestTaskRub() 
    {
            $client = static::createClient();
            $crawler =   $client->request('GET', '/task/index');
            $form = $crawler->selectButton('submit')->form();
            $form['departure']->select('POL');
            $form['arrival']->select('RUS');
            $form['from_currency']->setValue('123');
            $client->submit($form);
            $this->assertContains('RUB', $client->getResponse()->getContent());

    }
    /*
     * @return USD
     */
     public function testCheckRequestTaskUsd() 
    {
            $client = static::createClient();
            $crawler =   $client->request('GET', '/task/index');
            $form = $crawler->selectButton('submit')->form();
            $form['departure']->select('RUS');
            $form['arrival']->select('USA');
            $form['from_currency']->setValue('123');
            $client->submit($form);
            $this->assertContains('USD', $client->getResponse()->getContent());

    }
    
         public function testCheckRequestTaskFalse() 
    {
            $client = static::createClient();
            $crawler =   $client->request('GET', '/task/index');
            $form = $crawler->selectButton('submit')->form();
            $form['departure']->select('RUS');
            $form['arrival']->select('USA');
            $form['from_currency']->setValue('123');
            $client->submit($form);
            $this->assertNotContains('USDS', $client->getResponse()->getContent());

    }
}