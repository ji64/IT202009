<?php
$nums = array(1,2,3,6,7,24,25,28,45,56,89,111,121,130);

for($i = 0; $i<count($nums);$i++){
    echo "$nums[$i] ";
}

echo "<br>\n";

for($i = 0; $i<count($nums);$i++){
    if($nums[$i]%2 == 0){
        echo "$nums[$i] ";
    }
}
?>