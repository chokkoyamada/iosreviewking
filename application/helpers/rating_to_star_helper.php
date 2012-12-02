<?php

function rating_to_star($star){
    $star_values = explode('.',$star);
    $result = "";
    for($i=0; $i<$star_values[0]; $i++){
        $result .= "&#9733;";
    }
    if(isset($star_values[1])){
        $result .= "&#9734;";
    }
    return $result;
}
