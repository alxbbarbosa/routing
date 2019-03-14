<?php

function redirect($route = null){
    
    if(is_null($route) || $route == '/') {
        return header("location:/");
    }
    
    return header("location:{$houte}");
    
}