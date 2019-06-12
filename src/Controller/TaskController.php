<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CountriesHelper;
use App\Service\CurrencyHelper;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
/*
 * @Route("task/index")
 */
class TaskController extends AbstractController
{
    private $_log;
    private $_countries;
    private $_currency;
    
    public function __construct () {
        $this->_log = new Log();
        $this->_countries = new CountriesHelper();
//        $this->_currency = new CurrencyHelper();
    }
            
    public function index() {
        
        $data = $this->_countries->getAllCountries();
        if (!$data) {
            return false;
        }
        $data = $this->_countries->getDataFromCountries($data);
        return $this->render('task/index.html.twig', [
                    'data' => json_decode($data),
        ]);
    }

    /*
 * @Route("task/getForm")
 */
    public function getForm(Request $request) {
   
        if(!$request->getMethod() == 'POST') {
            return $this->redirectToRoute('app_task');
        }
        $data = $request->request->all();
        $countries_data = $this->_countries->getDataFromCountries($this->_countries->getAllCountries());
        
        if (!is_numeric($data['from_currency'])) {
            $error['amount'] = "Please enter valid cash amount !";
        }
        if($data['from_currency'] < 0) {
             $error['amount'] = "Cash amount must be greater than 0 !";
        }
        if(!empty($error)) {
               return $this->render('task/index.html.twig', [
                   'error' => $error,
                   'data' => json_decode($countries_data),
                   ]);
        }
     
        $departure_country = $this->_countries->getSingleCountry($data['departure']);
        $arrival_country = $this->_countries->getSingleCountry($data['arrival']);
      
        $data['departure'] = $departure_country->body->currencies[0]->code;
        $data['arrival'] = $arrival_country->body->currencies[0]->code;

        $converted_data = (Array)json_decode(CurrencyHelper::convert($data['departure'], $data['arrival'], $data['from_currency']));
        
        $converted_data['departure_capital'] = $departure_country->body->capital;
        $converted_data['arrival_capital'] = $arrival_country->body->capital;
        $converted_data['cash'] = round($converted_data['cash'],2);
  
        $entityManager = $this->getDoctrine()->getManager();
        $log = $this->_log->save($converted_data);

        $entityManager->persist($log);
        $entityManager->flush();

        return $this->render('task/index.html.twig', [
                    'data' => json_decode($countries_data),
                    'calculation' => $converted_data,
        ]);
    }
    
    public function getAll() {
        
//        $em = $this->getDoctrine()->getManager();
//        $records = $em->getRepository(Log::class)->findAll();
        $records = $this->getDoctrine()->getRepository(Log::class)->findall();
        
        return $this->render('task/getAll.html.twig', [
                    'data' => $records,
        ]);
    }
}