<?php

require_once 'testConfig.php';
// todo rearrange this for our project

/**
 * @param the query you want to perform (SELECT **aggregate function** ... >> NO GROUP BY
 *
 * @return value returned by the query
 */
function perform_SELECT_return_single_value($sql) {
    $conn = connectMySQLTests();

    if ($result = $conn->query($sql)) {
        $row = $result->fetch_array();
        $value = $row[0];

        $result->close();
        return  $value;
    } else {
        printf("Error message: %s\n", $conn->error);
    }
}

/**
 * @param query you want to perform containing --only-- INSERT or DELETE statement
 *
 * @return bool according if the operation succeded
 */
function perform_INSERT_or_DELETE($sql) {
    $conn = connectMySQLTests();

    if ($result = $conn->query($sql)) {
        return true;
    }
    else {
        printf("Error message: %s\n", $conn->error);
        return false;
    }
}


function get_serviceID_by_service_name($service_name) {
    return perform_SELECT_return_single_value("SELECT ID FROM Service WHERE Name = '{$service_name}'");
}

// protected and not private so that every inheriting class can access this method
function connectMySQLTests() {
    $mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_errno);
        exit();
    }
    return $mysqli;
}