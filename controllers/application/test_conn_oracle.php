<?php
// Ulanish parametrlari
$username = "your_username";
$password = "your_password";
$connection_string = "
  (DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.49.235)(PORT = 1521))
    (CONNECT_DATA =
      (SID = qqb)
    )
  )
";

// Ulanishni amalga oshirish
$conn = oci_connect($username, $password, $connection_string);

if (!$conn) {
	$e = oci_error();
	echo "Ulanishda xatolik: " . $e['message'];
	exit;
}

echo "Oracle bilan ulanish muvaffaqiyatli!";

// Ulanishni yopish
oci_close($conn);
