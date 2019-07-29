<?
header('Content-Type: '.$Adjunto['mime']);
header('Content-Disposition: attachment; filename='.$Adjunto['nombre']);
header('Content-Transfer-Encoding: binary');
readfile('.'.$Adjunto['url']);
?>