<?php
    session_start();
    echo json_encode(Array("result"=>true,"id"=>session_id()));
?>
