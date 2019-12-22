<?php

require './class/phpqrcode.php';

QRcode::png('http://'.$_SERVER['HTTP_HOST'].'/index.php?id='.$_GET['id'].'&type='.$_GET['type'], false, 'L', 15);