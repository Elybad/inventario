<?php

class encrip { 
private function encriptar($contra){
    $sha1= sha1($contra);

    return $sha1;
}


}


?>