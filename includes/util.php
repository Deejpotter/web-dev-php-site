<?php

function sanitize_input($data)
{
    $data = trim($data);
    $data = htmlentities($data);
    return $data;
}