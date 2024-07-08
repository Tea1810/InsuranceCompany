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
}
if(isset($_POST['deleteCustomer'])){
    $customerController->DeleteCustomer();
}
if(isset($_POST['updateCustomer'])){
    $customerController->EditCustomer();
}
if(isset($_POST['saveInsurance'])) {
    $insuranceController->CreateInsurance();
}
if(isset($_POST['deleteInsurance'])) {
    $insuranceController->DeleteInsurance();
}
if(isset($_POST['updateInsurance'])){
    $insuranceController->EditInsurance();
}