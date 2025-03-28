<?php

echo "Test Oracle!";
$username = 'programmer';
$password = 'prog';
// Delphi dagi '172.16.53.235:1521:qqb' ni PHP formatida, odatda SID o'rniga 
// xost va portni ko'rsatib, SID ni qo'shib ko'rsatish lozim:
$connection_string = '//172.16.53.235:1521/qqb';
$charset = 'CL8MSWIN1251';

$conn = oci_connect($username, $password, $connection_string, $charset);

if (!$conn) {
    $e = oci_error();
    echo "Oracle bazasiga ulanishda xatolik: " . htmlentities($e['message']);
    exit;
}

echo "Oracle bazasiga muvaffaqiyatli ulandingiz!";
?>
<script>
console.log('test oracle 2');
</script>
