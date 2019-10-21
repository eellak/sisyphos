<?php require_once('nucommon.php'); ?>
<?php

$jsonID         = $_GET['i'];
$t              = nuRunQuery("SELECT deb_message AS json FROM zzzsys_debug WHERE zzzsys_debug_id = ? ", array($jsonID));
$r              = db_fetch_object($t);
$JSON           = json_decode($r->json);

$style          = nuBuildStyle($JSON);
$table          = nuBuildTable($JSON);


$h .=  "
        
<html>
        
<style>


table    {border-width:1px;border-style:solid;border-color:grey}
tr       {border-width:1px;border-style:solid;border-color:grey}
th       {border-width:1px;border-style:solid;border-color:grey;background-color:lightgrey}
td       {border-width:1px;border-style:solid;border-color:grey}
.id      {border-width:0px;border-style:none;width:1px;font-size:0px}
$style


</style>

<body>

$table

</body>
</html>

";
print $h;

nuRunQuery("DROP TABLE $JSON->records");

function nuBuildStyle($J){
    
    $h  = '';
    $a['l']  = 'left';
    $a['r']  = 'right';
    $a['c']  = 'center';
    
    for($i = 0 ; $i < count($J->objects) ; $i++){
        
        $A   = $a[$J->objects[$i]->align];
        $W   = $J->objects[$i]->width . 'px';

        $h .= ".s$i      {text-align:$A;width:$W}\n";
    }

    return $h;
    
}



function nuBuildTable($J){
    
    $h              = '';
    $h             .=  "<table>";
    $h             .=  "<tr>";
    $h             .=  "<th class='id'>ID</th>";
    
    for($i = 0 ; $i < count($J->objects) ; $i++){
        
        $w          = $J->objects[$i]->width . 'px';
        $v          = $J->objects[$i]->title;
        $h         .=  "<th class='s$i'>$v</th>";
    }
    
    $h             .=  "</tr>";
    
    $T              = nuRunQuery("SELECT * FROM $J->records");

    while($R = db_fetch_row($T)){

        $h         .=  "<tr>";
        $h         .=  "<td class='id'>".$R[0]."</td>";
        for($i = 0 ; $i < count($J->objects) ; $i++){

            $w      = $J->objects[$i]->width . 'px';
            $v      = $R[$i+1];
            $h     .=  "<td class='s$i'>$v</td>";
        }
        $h         .=  "</td>";
        $h         .=  "</tr>";
        
    }
    
    
    $h             .=  "</table>";
    
    return $h;
    
}



    
    nuRunQuery("DELETE FROM zzzsys_debug WHERE zzzsys_debug_id = ? ", array($jsonID));
	
?>
