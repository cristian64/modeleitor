<?php
require_once("minilibreria.php");

$dia = DateTime::createFromFormat('d/m/Y', getPost("dia"));

echo ENReserva::comprobanteDia($dia);
?>
