<?php
require_once __DIR__ . '/../vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (isset($_POST['date'], $_POST['facility_name'], $_POST['facility_activity'], $_POST['facility_address'], $_POST['mobile'], $_POST['commercial_register'], $_POST['no_civil_registry'], $_POST['internal_cameras'], $_POST['external_cameras'], $_POST['recording_device'], $_POST['recording_duration'], $_POST['storage_disk'], $_POST['display'], $_POST['other_specifications'])) {
    $mpdf = new \Mpdf\Mpdf([
        'margin_top' => 40,
        'mode' => 'utf-8',
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/../fonts/Tajawal',
        ]),
        'fontdata' => $fontData + [ // lowercase letters only in font key
            'tajawal' => [
                'R' => 'Tajawal-Regular.ttf',
                'B' => 'Tajawal-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font' => 'tajawal'
    ]);

    // $mpdf->autoLangToFont = true;
    $mpdf->autoScriptToLang = true;

    $content = file_get_contents('./certificate.html');

    $search = [
        '__DATE__',
        '__SERIAL_NUMBER__',
        '__FACILITY_NAME__',
        '__FACILITY_ADDRESS__',
        '__FACILITY_ACTIVITY__',
        '__MOBILE__',
        '__COMMERCIAL_REGISTER__',
        '__INTERNAL_CAMERAS__',
        '__EXTERNAL_CAMERAS__',
        '__RECORDING_DEVICE__',
        '__RECORDING_DURATION__',
        '__STORAGE_DISK__',
        '__DISPLAY__',
        '__OTHER_SPECIFICATIONS__',
    ];

    $replaced = [
        $_POST['date'],
        $_POST['serial_number'],
        $_POST['facility_name'],
        $_POST['facility_address'],
        $_POST['facility_activity'],
        $_POST['mobile'],
        $_POST['commercial_register'],
        $_POST['internal_cameras'],
        $_POST['external_cameras'],
        $_POST['recording_device'],
        $_POST['recording_duration'],
        $_POST['storage_disk'],
        $_POST['display'],
        $_POST['other_specifications'],
    ];
    $html = str_replace($search, $replaced, $content);

    $mpdf->WriteHTML($html);

    $pdfFilePath = "pdf/" . $_POST['serial_number'] . "-" . time() . "-download.pdf";
    $mpdf->Output($pdfFilePath, "F");

    echo json_encode([
        'filepath' => $pdfFilePath,
        'filename' => $_POST['serial_number'],
    ]);
}
