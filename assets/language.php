<?php 

$messages = array (
'en_US'=> array(
'Estimate' => 'Estimate',
'Accept Estimate' => 'Accept Estimate',
'biscuit' => 'biscuit',
'candy' => 'candy',
'potato chips' => 'potato chips',
'cookie' => 'cookie',
'corn' => 'corn',
'eggplant' => 'eggplant'
),

'pt_PT'=> array(
'Estimate' => 'Orçamento',
'Accept Estimate' => 'Aceitar Orçamento',
'biscuit' => 'biscuit',
'candy' => 'candy',
'potato chips' => 'potato chips',
'cookie' => 'cookie',
'corn' => 'corn',
'eggplant' => 'eggplant'
)

);

function msg($s) {
    global $LANG;
    global $messages;
    
    if (isset($messages[$LANG][$s])) {
        return $messages[$LANG][$s];
    } else {
        error_log("l10n error:LANG:" . 
            "$lang,message:'$s'");
    }
}
?>