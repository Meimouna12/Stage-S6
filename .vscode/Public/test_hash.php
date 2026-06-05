<?php

$hash = password_hash('password', PASSWORD_DEFAULT);

echo "Hash généré :<br>";
echo $hash;
echo "<br><br>";

var_dump(password_verify('password', $hash));