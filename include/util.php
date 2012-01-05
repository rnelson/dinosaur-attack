<?php

// http://www.php.net/manual/en/class.domelement.php#93340
function getTextFromNode($Node, $Text = "") { 
    if ($Node->tagName == null) 
        return $Text.$Node->textContent; 

    $Node = $Node->firstChild; 
    if ($Node != null) 
        $Text = getTextFromNode($Node, $Text); 

    while($Node->nextSibling != null) { 
        $Text = getTextFromNode($Node->nextSibling, $Text); 
        $Node = $Node->nextSibling; 
    } 
    return $Text; 
}