<?php

require_once dirname(__DIR__) . '/models/addressModel.php';

class AddressController{
    function getAddress($id){
        $addressModel = new AddressModel(); 
        $address = $addressModel->getAddress($id);
        return $address;
    }

    function addAddress($userId, $addressObj){
        $addressModel = new AddressModel();
        $address = $addressModel->addAddress($userId, $addressObj);
        return $address;
    }
}
