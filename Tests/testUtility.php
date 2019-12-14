<?php

require_once 'testConfig.php';
// todo rearrange this for our project

/**
 * To be used in setUp() and tearDown() methods for tests
 *
 * @param null
 *
 * @return null
 */
function createTestDatabase() {
    $filename = '../softeng2Final.sql';

    $mysqli = new mysqli(DBAddr, DBUser, DBPassword);


    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_errno);
    }

    /*if (!$mysqli->select_db("testsofteng2")) {
        echo "Test database does not exists";

    } else {
        echo "Test database already exists";
    }*/

    $mysqli->query("CREATE DATABASE testsofteng2;");
    $mysqli->query("USE testsofteng2;");
    $templine = '';
    $lines = file($filename);

    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        $templine .= $line;

        if (substr(trim($line), -1, 1) == ';') {
            $mysqli->query($templine) or print('Error performing query \'< strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');

            $templine = '';
        }
    }

    $mysqli->close();
    return;
}

function createTables() {
    $filename = '../softeng2Final.sql';

    $mysqli = new mysqli(DBAddr, DBUser, DBPassword, 'testsofteng2');

    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_errno);
    }

    if ($result = $mysqli->query("SHOW TABLES LIKE 'User'")) {
        if($result->num_rows == 1) {
            echo "Table already exists";
        }
        else {
            echo "Creating tables";
            $templine = '';
            $lines = file($filename);

            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;

                $templine .= $line;

                if (substr(trim($line), -1, 1) == ';') {
                    $mysqli->query($templine) or print('Error performing query \'< strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');

                    $templine = '';
                }
            }


        }
    }

    $mysqli->close();
    return;
}

/**
 * To be used in setUp() and tearDown() methods for tests
 *
 * @param null
 *
 * @return null
 */
function dropTestDatabase() {
    $mysqli = TestsConnectMySQL();

    if ($mysqli->query("DROP DATABASE " . DBName) === TRUE)
        echo "Database " . DBName . " dropped successfully";
    else
        echo "Unable to drop database " . DBName . ". ERROR: " . $mysqli->error;
}

function dropTables() {
    $mysqli = TestsConnectMySQL();

    $res = $mysqli->query("
        SET FOREIGN_KEY_CHECKS = 0;
        SET GROUP_CONCAT_MAX_LEN=32768;
        SET @tables = NULL;
        SELECT GROUP_CONCAT('`', table_name, '`') INTO @tables
          FROM information_schema.tables
          WHERE table_schema = 'testsofteng2';
        SELECT IFNULL(@tables,'dummy') INTO @tables;
        
        SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
        PREPARE stmt FROM @tables;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        SET FOREIGN_KEY_CHECKS = 1;
    ");

    if ($res === TRUE)
        echo "Tables dropped successfully";
    else
        echo "Unable to drop tables. ERROR: " . $mysqli->error;
}

/**
 * @param the query you want to perform (SELECT **aggregate function** ... >> NO GROUP BY
 *
 * @return value returned by the query
 */
function perform_SELECT_return_single_value($sql) {
    $conn = TestsConnectMySQL();

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
    $conn = TestsConnectMySQL();

    if ($result = $conn->query($sql)) {
        return true;
    }
    else {
        printf("Error message: %s\n", $conn->error);
        return false;
    }
}

/**
 * Creates a connection to the DB for testing
 * Connection is to be closed by the caller function
 *
 * @param null
 *
 * @return mysqli connection
 */
function TestsConnectMySQL() {
    $mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_errno);
        exit();
    }
    return $mysqli;
}
