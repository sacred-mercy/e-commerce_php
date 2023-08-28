<?php

require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/functions/getSafeValue.php';

class AddressModel {
    function getAddress($id){
        $id = getSafeValue($id);
        
        $query = "SELECT * FROM address WHERE user_id = $id AND is_active = 't'";

        $result = pg_query($GLOBALS['db'], $query);

        if ($result === false) {
            return array(
                'error' => "Query execution failed: " . pg_last_error($GLOBALS['db']),
                'statusCode' => '400'
            );
        }

        return array(
            'address' => pg_fetch_all($result),
            'statusCode' => '200'
        );
    }

    function addAddress($userId, $addressObj){
        $line1 = getSafeValue($addressObj['line1']);
        $line2 = getSafeValue($addressObj['line2']);
        $district = getSafeValue($addressObj['district']);
        $state = getSafeValue($addressObj['state']);
        $pincode = getSafeValue($addressObj['pincode']);

        $query = "INSERT INTO address (user_id, line1, line2, district, state, pincode) VALUES ($userId, '$line1', '$line2', '$district', '$state', '$pincode')";

        $result = pg_query($GLOBALS['db'], $query);

        if ($result === false) {
            return array(
                'error' => "Query execution failed: " . pg_last_error($GLOBALS['db']),
                'statusCode' => '400'
            );
        }

        return array(
            'message' => 'Address added successfully',
            'statusCode' => '200'
        );
    }
}