<?php

/**
 * Class Printer
 */
class Printer
{
    /**
     * @param $msg
     */
    public function output($msg)
    {
        if (!$msg) {
            return;
        }

        echo $msg;
    }
}
