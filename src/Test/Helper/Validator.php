<?php
namespace Test\Helper;

class Validator {
    public static function validateLoginData($username, $password) {
        return !empty($username) && !empty($password);
    }
}