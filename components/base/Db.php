<?php

namespace components\base;

class Db {

    // RedBeanPHP class object
    private static $RedBean = null;

    /**
     * Returns an object of the RedBeanPHP class
     * @return object
     */
    public static function getConnection() {

        if (self::$RedBean != null) {
            return self::$RedBean;
        }

        $paramsPath = ROOT_DIR . '/config/db.php';
        $params = include($paramsPath);

        $redBeanPhpPath = ROOT_DIR . '/components/base/RedBeanPHP.php';
        include($redBeanPhpPath);
        \R::setup("mysql:host={$params['host']};dbname={$params['dbname']}", 
                                      $params['user'], $params['password'], false);
        \R::ext('xdispense', function( $type ){
            return \R::getRedBean()->dispense( $type );
        });
        if (\R::testConnection()) {
            self::$RedBean = new \R;
            return self::$RedBean;
        }
        return false;
    }
}