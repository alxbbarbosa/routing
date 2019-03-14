<?php

function toJSON($data)
{

    if (!is_array($data)) {
        $data = (array) $data;
    }

    return json_encode($data, JSON_FORCE_OBJECT);
}

function isJSON($data)
{
    return is_string($data) && is_array(json_decode($data, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
