<?php

use Controller\CustomerController;
use Controller\InsuranceController;
use Controller\ServiceController;

require_once('vendor/autoload.php');
require_once('db.php');

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
]);

$serviceController = new ServiceController($entityManager,$twig);
$customerController=new CustomerController($entityManager,$twig);
$insuranceController=new InsuranceController($entityManager,$twig);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

if($pathParts[0]=="services"){
    if(sizeof($pathParts)==1)
        echo $serviceController->DisplayServices();
    elseif($pathParts[1]=="new")
        echo $twig->render('Service/new.html.twig');
    else
        echo $serviceController->DisplayEditService($pathParts[1]);
}

if($pathParts[0]=="customers"){
    if(sizeof($pathParts)==1)
        echo $customerController->DisplayCustomers();
    elseif($pathParts[1]=="new")
        echo $customerController->DisplayNewCustomer();
    else
        echo $customerController->DisplayEditCustomer($pathParts[1]);
}

if($pathParts[0]=="insurances"){
    if(sizeof($pathParts)==1){
        $sortBy=isset($_POST['sort'])? $_POST['sort']: null;
        echo $insuranceController->DisplayInsurances($sortBy);
    }
    elseif($pathParts[1]=="new")
        echo $insuranceController->DisplayNewInsurance();
    else
        echo $insuranceController->DisplayEditInsurance($pathParts[1]);
}
