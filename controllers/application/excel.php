<?php

function columnNumberToLetter($num) {
    $letter = '';
    while ($num > 0) {
        $mod = ($num - 1) % 26;
        $letter = chr(65 + $mod) . $letter;
        $num = intval(($num - $mod) / 26);
    }
    return $letter;
}

// Проверка авторизации пользователя
if (user() === false) {
    redirect('/account/login');
}

if (user()['type'] == '1' || user()['type'] == '3') {
    $applications = array_reverse(R::findAll('applications'));
} else {
    $branch_code = R::findOne('branches', 'id = ?', [user()['branch_id']])['code'];
    $applications = array_reverse(R::findAll('applications', 'bank_branch_code = ?', [$branch_code]));
}

// Определение заголовков
$headers = [
    'No.',
    'ФИО',
    'PINFL',
    'Сумма кредита',
    'Код BXO',
    'Создано',
    'Статус'
];

// Определение ширины колонок (значения в единицах Excel, примерно равны символам)
$columnWidths = [
    30,    // No.
    150,   // ФИО
    100,   // PINFL
    120,   // Сумма кредита
    100,   // Код BXO
    150,   // Создано
    100    // Статус
];

// Начало построения XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
$xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

// Определение стилей
$xml .= '  <Styles>' . "\n";
$xml .= '    <Style ss:ID="BoldHeader">' . "\n";
$xml .= '      <Font ss:Bold="1"/>' . "\n";
$xml .= '      <Borders>' . "\n";
$xml .= '        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
$xml .= '      </Borders>' . "\n";
$xml .= '    </Style>' . "\n";
$xml .= '    <Style ss:ID="Currency">' . "\n";
$xml .= '      <NumberFormat ss:Format="#,##0.00"/>' . "\n"; // Формат для чисел без "UZS"
$xml .= '    </Style>' . "\n";
$xml .= '    <Style ss:ID="CustomDate">' . "\n";
$xml .= '      <NumberFormat ss:Format="yyyy-mm-dd hh:mm:ss"/>' . "\n"; // Формат даты и времени
$xml .= '    </Style>' . "\n";
$xml .= '  </Styles>' . "\n";

// Начало рабочего листа
$xml .= '  <Worksheet ss:Name="Applications">' . "\n";
$xml .= '    <Table>' . "\n";

// Добавление ширины колонок
foreach ($columnWidths as $index => $width) {
    $xml .= '      <Column ss:Index="' . ($index + 1) . '" ss:Width="' . $width . '"/>' . "\n";
}

// Добавление заголовков в первую строку
$xml .= '      <Row ss:Index="1">' . "\n";
$columnIndex = 1;
foreach ($headers as $header) {
    $cell = '        <Cell ss:Index="' . $columnIndex . '" ss:StyleID="BoldHeader">';
    $cell .= '<Data ss:Type="String">' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
    $xml .= $cell;
    $columnIndex++;
}
$xml .= '      </Row>' . "\n";

// Функция для преобразования статуса
function getStatusText($status) {
    switch ($status) {
        case '1':
            return 'Принято';
        case '2':
            return 'Отказано';
        case '3':
            return 'Кредит выдан';
        case '4':
            return 'Отменено';
        default:
            return 'В ожидании';
    }
}

// Добавление данных строк
$serialNumber = 1;
$rowIndex = 2; // Начинаем со второй строки, т.к. первая - заголовки
foreach ($applications as $application) {
    $xml .= '      <Row ss:Index="' . $rowIndex . '">' . "\n";
    $columnIndex = 1;

    // No.
    $cellValue = $serialNumber;
    $xml .= '        <Cell ss:Index="' . $columnIndex . '"><Data ss:Type="Number">' . $cellValue . '</Data></Cell>' . "\n";
    $columnIndex++;

    // ФИО (Конкатенация lastname, firstname, fathername)
    $fio = $application['lastname'] . ' ' . $application['firstname'] . ' ' . $application['fathername'];
    $xml .= '        <Cell ss:Index="' . $columnIndex . '"><Data ss:Type="String">' . htmlspecialchars($fio, ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
    $columnIndex++;

    // PINFL
    $xml .= '        <Cell ss:Index="' . $columnIndex . '"><Data ss:Type="String">' . htmlspecialchars($application['pinfl'], ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
    $columnIndex++;

    // Сумма кредита
    $creditSum = number_format((float)$application['credit_sum'], 2, '.', ''); // Форматирование числа
    $xml .= '        <Cell ss:Index="' . $columnIndex . '" ss:StyleID="Currency"><Data ss:Type="Number">' . $creditSum . '</Data></Cell>' . "\n";
    $columnIndex++;

    // Код BXO
    $xml .= '        <Cell ss:Index="' . $columnIndex . '"><Data ss:Type="String">' . htmlspecialchars($application['bank_branch_code'], ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
    $columnIndex++;

    // Создано
    $date = date('Y-m-d\TH:i:s', strtotime($application['created_at']));
    $xml .= '        <Cell ss:Index="' . $columnIndex . '" ss:StyleID="CustomDate"><Data ss:Type="DateTime">' . $date . '</Data></Cell>' . "\n";
    $columnIndex++;

    // Статус
    $statusValue = isset($application['bxo_status']) ? $application['bxo_status'] : '';
    $statusText = getStatusText($statusValue);
    $xml .= '        <Cell ss:Index="' . $columnIndex . '"><Data ss:Type="String">' . htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
    $columnIndex++;

    $xml .= '      </Row>' . "\n";
    $serialNumber++;
    $rowIndex++;
}

$xml .= '    </Table>' . "\n";
$xml .= '  </Worksheet>' . "\n";
$xml .= '</Workbook>';

// Установка заголовков для скачивания XML файла совместимого с Excel
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="applications_export_' . date('Y-m-d') . '.xls"');

// Вывод XML
echo $xml;
exit();
?>
