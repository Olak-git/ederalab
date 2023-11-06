<?php
namespace src\traits;

trait Meithods
{
    public function formatage($str)
    {
        $new_str = $str[0];
        for($i = 1; $i < strlen($str); $i++) {
            if(preg_match('#[A-Z]+#', $str[$i])) {
                $new_str .= '_'.strtolower($str[$i]);
            } else {
                $new_str .= $str[$i];
            }
        }
        return strtolower($new_str);
    }
}