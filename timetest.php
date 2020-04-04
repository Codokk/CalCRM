<div>
D: 
<?php
$datetime = new DateTime();
$datetime = $datetime->format(DateTime::ATOM);
$datetime = explode("+", $datetime);
$datetime = $datetime[0];
$datetime .= ".000Z";
echo $datetime;
?>
</div>
<div id="b">
D: 
</div>
<script>
document.getElementById("b").innerHTML += new Date().toISOString();
</script>