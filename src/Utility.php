<?php

namespace App;

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
    private const SPORT_TYPE =[
        'PILATES',
        'CYCLING',
        'INTERVALS',
        'ABS',
        'SQUASH'
    ];


    /**
     * Check if key is hardcoded admin key
     *
     * @param string $admin_key
     * @return bool
     */
    public static function checkAdminKey(string $admin_key){
        if(in_array($admin_key,self::ADMIN_KEYS)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Check if sport is in hardcoded sport list
     *
     * @param string $sport_key
     * @return bool
     */
    public static function checkSportKey(string $sport_key){
        if(in_array($sport_key,self::SPORT_TYPE)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Allow rooms from 1 to 10
     *
     * @param int $room_id
     * @return bool
     */
    public static function checkRoomKey(int $room_id){
        if($room_id > 0 && $room_id <= 10){
            return true;
        }else{
            return false;
        }
    }
}