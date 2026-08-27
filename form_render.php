<?php
require_once __DIR__ . '/data_config.php';

/**
 * Renders the full survey HTML page (form or, when $errors is non-empty,
 * the same form with sticky values and an error banner).
 *
 * @param array $errors list of error message pairs ['en' => ..., 'ko' => ...]
 * @param array $old     previously submitted $_POST values, for sticky fields
 */
function render_survey_page(array $errors, array $old): void
{
    global $SURVEY_QUESTIONS;
    $old_val = static fn(string $key): string => htmlspecialchars($old[$key] ?? '', ENT_QUOTES, 'UTF-8');
    $is_checked = static fn(string $key, string $code): string => (($old[$key] ?? '') === $code) ? ' checked' : '';
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ICOLD 2027 Daejeon Survey / 설문조사</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<input type="radio" id="lang-en" name="lang-toggle" checked>
<input type="radio" id="lang-ko" name="lang-toggle">

<header class="site-header">
    <div class="eyebrow">ICOLD 2027 · Daejeon, Korea</div>
    <h1>
        <span class="lang-en">Visitor Interest Survey</span>
        <span class="lang-ko">참가자 관심사 설문조사</span>
    </h1>
    <p>
        <span class="lang-en">Help us plan the ICOLD 2027 program and tours. This survey takes about 1 minute.</span>
        <span class="lang-ko">ICOLD 2027 프로그램과 투어 기획을 위한 설문입니다. 약 1분 정도 소요됩니다.</span>
    </p>
    <div class="lang-switch">
        <label for="lang-en">English</label>
        <label for="lang-ko">한국어</label>
    </div>
</header>

<main>
    <?php if (!empty($errors)): ?>
    <div class="error-banner">
        <?php foreach ($errors as $e): ?>
            <div><span class="lang-en"><?= htmlspecialchars($e['en'], ENT_QUOTES, 'UTF-8') ?></span><span class="lang-ko"><?= htmlspecialchars($e['ko'], ENT_QUOTES, 'UTF-8') ?></span></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="submit.php" method="post" id="survey-form">
        <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
        <input type="hidden" name="lang_used" id="lang_used_field" value="en">

        <?php foreach ($SURVEY_QUESTIONS as $qi => $q): ?>
        <section class="question-card">
            <span class="q-index">Q<?= $qi + 1 ?></span>
            <h2>
                <span class="lang-en"><?= htmlspecialchars($q['title_en'], ENT_QUOTES, 'UTF-8') ?></span>
                <span class="lang-ko"><?= htmlspecialchars($q['title_ko'], ENT_QUOTES, 'UTF-8') ?></span>
            </h2>

            <div class="rank-label">
                <span class="lang-en">1st choice</span><span class="lang-ko">1순위</span>
                <span class="hint"> — <span class="lang-en">required</span><span class="lang-ko">필수</span></span>
            </div>
            <div class="option-grid">
                <?php foreach ($q['options'] as $opt): $fieldName = $q['code'] . '_1'; ?>
                <div class="option-card">
                    <input type="radio" name="<?= $fieldName ?>" id="<?= $fieldName . '_' . $opt['code'] ?>" value="<?= $opt['code'] ?>"<?= $is_checked($fieldName, $opt['code']) ?>>
                    <label for="<?= $fieldName . '_' . $opt['code'] ?>">
                        <img src="images/<?= htmlspecialchars($opt['img'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy">
                        <div class="option-thumb-text">
                            <span class="option-code"><?= strtoupper($opt['code']) ?></span>
                            <span class="lang-en"><?= htmlspecialchars($opt['en'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="lang-ko"><?= htmlspecialchars($opt['ko'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="rank-label">
                <span class="lang-en">2nd choice</span><span class="lang-ko">2순위</span>
                <span class="hint"> — <span class="lang-en">optional</span><span class="lang-ko">선택</span></span>
            </div>
            <div class="option-grid">
                <?php foreach ($q['options'] as $opt): $fieldName = $q['code'] . '_2'; ?>
                <div class="option-card">
                    <input type="radio" name="<?= $fieldName ?>" id="<?= $fieldName . '_' . $opt['code'] ?>" value="<?= $opt['code'] ?>"<?= $is_checked($fieldName, $opt['code']) ?>>
                    <label for="<?= $fieldName . '_' . $opt['code'] ?>">
                        <img src="images/<?= htmlspecialchars($opt['img'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy">
                        <div class="option-thumb-text">
                            <span class="option-code"><?= strtoupper($opt['code']) ?></span>
                            <span class="lang-en"><?= htmlspecialchars($opt['en'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="lang-ko"><?= htmlspecialchars($opt['ko'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="other-field">
                <label for="<?= $q['code'] ?>_other">
                    <span class="lang-en">Other (please specify)</span>
                    <span class="lang-ko">기타 (직접 입력)</span>
                </label>
                <input type="text" maxlength="200" name="<?= $q['code'] ?>_other" id="<?= $q['code'] ?>_other" value="<?= $old_val($q['code'] . '_other') ?>">
            </div>
        </section>
        <?php endforeach; ?>

        <div class="submit-row">
            <button type="submit" class="submit-btn">
                <span class="lang-en">Submit</span>
                <span class="lang-ko">제출하기</span>
            </button>
        </div>
        <p class="footer-note">
            <span class="lang-en">Your responses are anonymous and used only for ICOLD 2027 planning purposes.</span>
            <span class="lang-ko">응답은 익명으로 수집되며 ICOLD 2027 행사 기획 목적으로만 사용됩니다.</span>
        </p>
    </form>
</main>

<script>
(function () {
    var enRadio = document.getElementById('lang-en');
    var koRadio = document.getElementById('lang-ko');
    var field = document.getElementById('lang_used_field');
    if (!enRadio || !koRadio || !field) { return; }
    enRadio.addEventListener('change', function () { field.value = 'en'; });
    koRadio.addEventListener('change', function () { field.value = 'ko'; });

    // Prevent picking the same option for 1st and 2nd choice (soft UX guard; server re-validates).
    document.querySelectorAll('.question-card').forEach(function (card) {
        var radios = card.querySelectorAll('input[type=radio]');
        radios.forEach(function (r) {
            r.addEventListener('change', function () {
                var isFirst = /_1$/.test(r.name);
                var pairName = isFirst ? r.name.replace(/_1$/, '_2') : r.name.replace(/_2$/, '_1');
                var pairSame = card.querySelector('input[name="' + pairName + '"][value="' + r.value + '"]');
                if (pairSame && pairSame.checked) {
                    pairSame.checked = false;
                }
            });
        });
    });
})();
</script>
</body>
</html>
    <?php
}
