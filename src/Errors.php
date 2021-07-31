<?php

/**
 * Exceptions Helper
 *
 */

namespace JDOUnivers\Helpers;

class Errors {

    //1xxx: general errors
    //2xxx: level2 errors
    //3xxx: academie errors
    const CODE_SESSIONKEY = 1004;
    const CODE_EMAILHASNOACCOUNT = 1005;
    const CODE_ACCOUNTNOTACTIVATED = 1006;
    const CODE_SLUGUNKNOWN = 3001;
    const CODE_FORMATIONNOTBOUGHT = 3002;

    public $Error;

    function __construct($field, $e) {
        if (DEV) {
            $this->Error = array(
                "Field" => $field,
                "Message" => $e->getMessage(),
                "Code" => $e->getCode(),
                "File" => $e->getFile(),
                "Line" => $e->getLine(),
                "Trace" => $e->getTraceAsString()
            );
        } else {
            $this->Error = array(
                "Field" => $field,
                "Message" => $e->getMessage(),
                "Code" => $e->getCode()
            );
        }
    }

}
