<?php
namespace src\Vendor;

interface Storage
{
    public function __sleep();
    public function __wakeup();
}