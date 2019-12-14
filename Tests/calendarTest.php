<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/calendar.class.php";


class calendarTest extends TestCase
{

    private function printErrorMessage($testName, $optionalMsg='')
    {
        return "teacherTest: error in " . $testName . "." . $optionalMsg;
    }

    public function testValidate_date()
    {
        // test normal behavior
        $this->assertTrue(calendar::validate_date('2019-12-25 11:12:10'), $this->printErrorMessage("testValidate_date"));
        // test wrong behavior
        $this->assertFalse(calendar::validate_date('2019-17-25 11:12:10'), $this->printErrorMessage("testValidate_date"));
    }

    public function testIs_holiday()
    {
        // test xmas
        $this->assertTrue(calendar::is_holiday('2019-12-25'), $this->printErrorMessage("testIs_holiday"));
        // test sunday
        $this->assertTrue(calendar::is_holiday('2019-11-10'), $this->printErrorMessage("testIs_holiday"));
        // test not holiday
        $this->assertFalse(calendar::is_holiday('2019-11-13'), $this->printErrorMessage("testIs_holiday"));
    }

    public function testBy_the_end_of_the_week()
    {
        // test before previous sunday
        $this->assertFalse(calendar::by_the_end_of_the_week(strtotime('2019-11-01 11:12:10'),strtotime('2019-11-07 11:12:10')), $this->printErrorMessage("testBy_the_end_of_the_week"));
        // test after sunday
        $this->assertFalse(calendar::by_the_end_of_the_week(strtotime('2019-11-12 11:12:10'),strtotime('2019-11-07 11:12:10')), $this->printErrorMessage("testBy_the_end_of_the_week"));
        // test too early = false
        $this->assertFalse(calendar::by_the_end_of_the_week(strtotime('2019-11-05 11:12:10'),strtotime('2019-11-08 11:12:10')), $this->printErrorMessage("testBy_the_end_of_the_week"));
        // test ok
        $this->assertTrue(calendar::by_the_end_of_the_week(strtotime('2019-11-08 11:12:10'),strtotime('2019-11-07 11:12:10')), $this->printErrorMessage("testBy_the_end_of_the_week"));
    }

    public function testGet_hours_per_school_day()
    {
        $this->assertEquals(6, calendar::get_hours_per_school_day(), $this->printErrorMessage("testGet_hours_per_school_day"));
    }

    public function testTimestamp_to_date()
    {

        $timestamp = mktime(10,11,12,3,8,2019);
        $date = calendar::timestamp_to_date($timestamp);

        $this->assertEquals('Mar', $date['month'], $this->printErrorMessage("testTimestamp_to_date"));
        $this->assertEquals('08', $date['day'], $this->printErrorMessage("testTimestamp_to_date"));
        $this->assertEquals('10:11', $date['time'], $this->printErrorMessage("testTimestamp_to_date"));
    }
}
