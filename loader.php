<?php
    spl_autoload_register( function( $class_name ) {
        $file_name = 'classes/'.$class_name . '.php';
        if( file_exists( $file_name ) ) {
            require $file_name;
        }
        else{
            $file_name = '../classes/'.$class_name . '.php';
            if( file_exists( $file_name ) ) {
                require $file_name;
            }
        }
    });
