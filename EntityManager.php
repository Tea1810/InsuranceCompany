<?php

include('index.php');

if(isset($_POST['saveService'])) {
    $serviceController->CreateService();
}
if(isset($_POST['deleteService'])){
    $serviceController->DeleteService();
}
if(isset($_POST['updateService'])){
    $serviceController->EditService();
}
if(isset($_POST['saveCustomer'])) {
    $customerController->CreateCustomer();
    header('Location: /insurances');
}

if(isset($_POST['saveInsurance'])) {
    $insuranceController->CreateInsurance();
    header('Location: /insurances');
}
if(isset($_POST['deleteInsurance'])) {
    $insuranceController->DeleteInsurance();
    header('Location: /insurances');
}
if(isset($_POST['updateInsurance'])){
    $insuranceController->EditInsurance();
    header('Location: /insurances');
}
