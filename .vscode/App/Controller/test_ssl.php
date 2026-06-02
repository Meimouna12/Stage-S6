<?php

$ch = curl_init('https://api.stripe.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$result = curl_exec($ch);

if ($result === false) {
    echo curl_error($ch);
} else {
    echo "SSL OK";
}

curl_close($ch);