<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\Request;

/** ----------------------------------------------------------------------------------
 * Function: isValidEmail
 * @param   string  $email
 *
 * @return  bool
 *
 * Author: https://gitlab.com/rahaman-m/
 * ------------------------------------------------------------------------------- */
function isValidEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Function: isValidName
 * @param   string $name
 */
function isValidName($name) {
    if( !empty($name) && preg_match('/^[a-zA-Z\s]{1,200}$/', $name) ) {
        return true;
    }
    return false;
}


/** ----------------------------------------------------------------------------------
 * Function: slugify
 * @param   string  $string
 *
 * @return  string   - slug.
 *
 * Author: https://gitlab.com/rahaman-m/
 * ------------------------------------------------------------------------------- */
function slugify($string){
    $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
}

