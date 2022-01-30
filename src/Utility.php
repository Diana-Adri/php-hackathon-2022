<?php

namespace App;

use DateTime;

class Utility
{
    //randomly generated keys - random.org
    private const ADMIN_KEYS = [
        '3FdpSrH93Z',
        'XdTeeOIB3g',
        'RnHQ03tXCl',
        'E5048pykBb',
        '9nDaKG2cHh',
        'ejmlRCDgwp',
        'hGh5tEyFx8',
        'V5UHUYQFY3',
        'RFNaix6qAy',
        'dBPeokXhMk',
    ];

    //specific allowed sports
    private const SPORT_TYPE = [
        'PILATES',
        'CYCLING',
        'INTERVALS',
        'ABS',
        'SQUASH'
    ];

    private const OPEN_TIME = "06:00";
    private const CLOSING_TIME = "22:00";

    public static function checkClosingTime($end_time)
    {
        $end_time_formatted = DateTime::createFromFormat('H:i', $end_time)->format("d-M-Y H:i:s");
        $closing_time_formatted = DateTime::createFromFormat('H:i', self::CLOSING_TIME)->format("d-M-Y H:i:s");
        $opening_time_formatted = DateTime::createFromFormat('H:i', self::OPEN_TIME)->format("d-M-Y H:i:s");

        if ($end_time_formatted <= $closing_time_formatted && $end_time_formatted >= $opening_time_formatted) {
            return true;
        }
        return false;
    }

    public static function checkOpeningTime($start_time)
    {
        $start_time_formatted = DateTime::createFromFormat('H:i', $start_time)->format("d-M-Y H:i:s");
        $opening_time_formatted = DateTime::createFromFormat('H:i', self::OPEN_TIME)->format("d-M-Y H:i:s");
        $closing_time_formatted = DateTime::createFromFormat('H:i', self::CLOSING_TIME)->format("d-M-Y H:i:s");

        if ($start_time_formatted >= $opening_time_formatted && $start_time_formatted <= $closing_time_formatted) {
            return true;
        }
        return false;
    }

    public static function validateTimeDifference($start_time, $end_time)
    {
        $start_time_formatted = DateTime::createFromFormat('H:i', $start_time)->format("d-M-Y H:i:s");
        $end_time_formatted = DateTime::createFromFormat('H:i', $end_time)->format("d-M-Y H:i:s");
        if ($end_time_formatted > $start_time_formatted) {
            return true;
        }
        return false;

    }


    /**
     * Check if key is hardcoded admin key
     *
     * @param string $admin_key
     * @return bool
     */
    public static function checkAdminKey(string $admin_key)
    {
        if (in_array($admin_key, self::ADMIN_KEYS)) {
            return true;
        }
        return false;
    }

    /**
     * Check if sport is in hardcoded sport list
     *
     * @param string $sport_key
     * @return bool
     */
    public static function checkSportKey(string $sport_key)
    {
        if (in_array($sport_key, self::SPORT_TYPE)) {
            return true;
        }
        return false;
    }

    /**
     * Allow rooms from 1 to 10
     *
     * @param int $room_id
     * @return bool
     */
    public static function checkRoomKey(int $room_id)
    {
        if ($room_id > 0 && $room_id <= 10) {
            return true;
        }
        return false;
    }

    /**
     * Check number is valid CNP
     *
     * @param int $cnp
     * @return bool
     */
    public static function validateCNP(int $cnp): bool
    {
        if (strlen($cnp) == 13) {
            return true;
        }
        return false;
    }
}