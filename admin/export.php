<?php
session_start();
require_once __DIR__ . '/../admin_config.php';
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$pdo = survey_db();
$rows = $pdo->query('SELECT * FROM responses ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="icold2027_survey_export.csv"');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel opens Korean text correctly

if (!empty($rows)) {
    fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
} else {
    fputcsv($out, ['id', 'created_at', 'lang', 'q1_choice1', 'q1_choice2', 'q1_other', 'q2_choice1', 'q2_choice2', 'q2_other', 'q3_choice1', 'q3_choice2', 'q3_other']);
}
fclose($out);
