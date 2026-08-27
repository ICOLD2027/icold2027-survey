<?php
require_once __DIR__ . '/data_config.php';
require_once __DIR__ . '/form_render.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Honeypot: bots fill every field, real users never see/fill this one.
if (trim($_POST['website'] ?? '') !== '') {
    render_thanks_page();
    exit;
}

$errors = [];
$values = [];

foreach ($SURVEY_QUESTIONS as $q) {
    $code = $q['code'];
    $validCodes = array_map(static fn($o) => $o['code'], $q['options']);

    $choice1 = trim((string)($_POST["{$code}_1"] ?? ''));
    $choice2 = trim((string)($_POST["{$code}_2"] ?? ''));
    $other = trim((string)($_POST["{$code}_other"] ?? ''));
    if (function_exists('mb_substr')) {
        $other = mb_substr($other, 0, 200);
    } else {
        $other = substr($other, 0, 200);
    }

    if ($choice1 === '' || !in_array($choice1, $validCodes, true)) {
        $errors[] = [
            'en' => 'Please select a 1st choice for question "' . $q['title_en'] . '".',
            'ko' => '"' . $q['title_ko'] . '" 문항의 1순위를 선택해 주세요.',
        ];
        $choice1 = '';
    }

    if ($choice2 !== '' && !in_array($choice2, $validCodes, true)) {
        $choice2 = '';
    }

    if ($choice1 !== '' && $choice2 !== '' && $choice1 === $choice2) {
        $errors[] = [
            'en' => 'For question "' . $q['title_en'] . '", the 1st and 2nd choice must be different.',
            'ko' => '"' . $q['title_ko'] . '" 문항의 1순위와 2순위는 서로 다른 항목이어야 합니다.',
        ];
    }

    $values["{$code}_1"] = $choice1;
    $values["{$code}_2"] = $choice2;
    $values["{$code}_other"] = $other;
}

if (!empty($errors)) {
    render_survey_page($errors, $_POST);
    exit;
}

$lang = ($_POST['lang_used'] ?? 'en') === 'ko' ? 'ko' : 'en';

$pdo = survey_db();
$stmt = $pdo->prepare(
    'INSERT INTO responses
        (created_at, lang, q1_choice1, q1_choice2, q1_other, q2_choice1, q2_choice2, q2_other, q3_choice1, q3_choice2, q3_other)
     VALUES
        (:created_at, :lang, :q1c1, :q1c2, :q1o, :q2c1, :q2c2, :q2o, :q3c1, :q3c2, :q3o)'
);
$stmt->execute([
    ':created_at' => gmdate('Y-m-d H:i:s'),
    ':lang' => $lang,
    ':q1c1' => $values['q1_1'], ':q1c2' => $values['q1_2'], ':q1o' => $values['q1_other'],
    ':q2c1' => $values['q2_1'], ':q2c2' => $values['q2_2'], ':q2o' => $values['q2_other'],
    ':q3c1' => $values['q3_1'], ':q3c2' => $values['q3_2'], ':q3o' => $values['q3_other'],
]);

render_thanks_page();

function render_thanks_page(): void
{
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank you / 감사합니다 — ICOLD 2027</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="thanks-wrap">
    <h1>Thank you! / 감사합니다!</h1>
    <p>Your response has been recorded.<br>응답이 정상적으로 제출되었습니다.</p>
    <a class="back-link" href="index.php">&larr; Back / 돌아가기</a>
</div>
</body>
</html>
    <?php
}
