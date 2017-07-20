<?php

$date = new DateTime(date("d/m/Y", strtotime("01/12/1998")));

echo $date->format("d/m/Y");