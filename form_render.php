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
    <div class="eyebrow">
        <span class="lang-en">ICOLD 2027 · The 95th ICOLD Annual Meeting · Daejeon, South Korea</span>
        <span class="lang-ko">ICOLD 2027 · 2027년 국제대댐회 제95차 연차회의 · 대한민국 대전</span>
    </div>
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
            <?php if (!empty($q['note_en']) || !empty($q['note_ko'])): ?>
            <p class="q-note">
                <span class="lang-en"><?= htmlspecialchars($q['note_en'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <span class="lang-ko"><?= htmlspecialchars($q['note_ko'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            </p>
            <?php endif; ?>

            <div class="rank-legend">
                <span class="legend-chip legend-chip-first">1st</span>
                <span class="legend-text"><span class="lang-en">1st click = 1st choice</span><span class="lang-ko">첫 번째 클릭 = 1순위</span></span>
                <span class="legend-chip legend-chip-second">2nd</span>
                <span class="legend-text"><span class="lang-en">2nd click = 2nd choice</span><span class="lang-ko">두 번째 클릭 = 2순위</span></span>
                <span class="legend-text legend-text-muted"><span class="lang-en">Click again to deselect</span><span class="lang-ko">다시 클릭 = 선택 해제</span></span>
            </div>
            <div class="rank-hint" id="<?= $q['code'] ?>_hint">
                <span class="lang-en">Click a card to select your 1st choice.</span>
                <span class="lang-ko">카드를 클릭하면 1순위로 선택됩니다.</span>
                <span class="rank-hint-required"><span class="lang-en"> (1st required, 2nd optional)</span><span class="lang-ko"> (1순위 필수 · 2순위 선택)</span></span>
            </div>
            <div class="option-grid" data-field="<?= $q['code'] ?>">
                <input type="hidden" name="<?= $q['code'] ?>_1" id="<?= $q['code'] ?>_1_hidden" value="<?= $old_val($q['code'] . '_1') ?>">
                <input type="hidden" name="<?= $q['code'] ?>_2" id="<?= $q['code'] ?>_2_hidden" value="<?= $old_val($q['code'] . '_2') ?>">
                <?php foreach ($q['options'] as $opt):
                    $isFirst = ($old[$q['code'] . '_1'] ?? '') === $opt['code'];
                    $isSecond = ($old[$q['code'] . '_2'] ?? '') === $opt['code'];
                    $cardClass = 'option-card' . ($isFirst ? ' is-first' : ($isSecond ? ' is-second' : ''));
                ?>
                <div class="<?= $cardClass ?>" data-code="<?= $opt['code'] ?>" tabindex="0" role="button" aria-pressed="<?= ($isFirst || $isSecond) ? 'true' : 'false' ?>">
                    <img src="images/<?= htmlspecialchars($opt['img'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy">
                    <span class="rank-badge"><?= $isFirst ? '1st' : ($isSecond ? '2nd' : '') ?></span>
                    <div class="option-thumb-text">
                        <span class="option-code"><?= strtoupper($opt['code']) ?></span>
                        <span class="lang-en"><?= htmlspecialchars($opt['en'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="lang-ko"><?= htmlspecialchars($opt['ko'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
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

        <section class="question-card">
            <h2>
                <span class="lang-en">Your email</span>
                <span class="lang-ko">이메일</span>
            </h2>
            <div class="other-field">
                <label for="email">
                    <span class="lang-en">Email address</span>
                    <span class="lang-ko">이메일 주소</span>
                    <span class="hint"> — <span class="lang-en">required</span><span class="lang-ko">필수</span></span>
                </label>
                <input type="email" maxlength="200" name="email" id="email" required value="<?= $old_val('email') ?>">
            </div>
        </section>

        <div class="submit-row">
            <button type="submit" class="submit-btn">
                <span class="lang-en">Submit</span>
                <span class="lang-ko">제출하기</span>
            </button>
        </div>
        <p class="footer-note">
            <span class="lang-en">Your responses are used only for ICOLD 2027 planning purposes and are not shared with third parties.</span>
            <span class="lang-ko">응답은 ICOLD 2027 행사 기획 목적으로만 사용되며 제3자에게 제공되지 않습니다.</span>
        </p>
        <p class="footer-note">Source: 한국관광데이터랩 「2025 국가별 방한관광시장 분석」</p>
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

    // Single-row 1st/2nd choice picker: click a card once for 1st choice,
    // click a different card for 2nd choice, click a selected card again to
    // deselect it, and click a third card to replace the 2nd choice.
    document.querySelectorAll('.option-grid[data-field]').forEach(function (grid) {
        var field = grid.getAttribute('data-field');
        var hidden1 = document.getElementById(field + '_1_hidden');
        var hidden2 = document.getElementById(field + '_2_hidden');
        var hint = document.getElementById(field + '_hint');
        var cards = grid.querySelectorAll('.option-card');

        function updateHint() {
            if (!hint) { return; }
            var en = hint.querySelector('.lang-en');
            var ko = hint.querySelector('.lang-ko');
            if (!hidden1.value) {
                en.textContent = 'Click a card to select your 1st choice.';
                ko.textContent = '카드를 클릭하면 1순위로 선택됩니다.';
            } else if (!hidden2.value) {
                en.textContent = 'Click another card to select your 2nd choice (optional).';
                ko.textContent = '다른 카드를 클릭하면 2순위로 선택됩니다 (선택 사항).';
            } else {
                en.textContent = 'Click a selected card again to change your choices.';
                ko.textContent = '선택된 카드를 다시 클릭하면 선택이 바뀌거나 해제됩니다.';
            }
        }

        function render() {
            cards.forEach(function (card) {
                var code = card.getAttribute('data-code');
                var badge = card.querySelector('.rank-badge');
                card.classList.remove('is-first', 'is-second');
                if (hidden1.value !== '' && code === hidden1.value) {
                    card.classList.add('is-first');
                    card.setAttribute('aria-pressed', 'true');
                    badge.textContent = '1st';
                } else if (hidden2.value !== '' && code === hidden2.value) {
                    card.classList.add('is-second');
                    card.setAttribute('aria-pressed', 'true');
                    badge.textContent = '2nd';
                } else {
                    card.setAttribute('aria-pressed', 'false');
                    badge.textContent = '';
                }
            });
            updateHint();
        }

        function handleClick(card) {
            var code = card.getAttribute('data-code');
            if (hidden1.value === code) {
                hidden1.value = '';
            } else if (hidden2.value === code) {
                hidden2.value = '';
            } else if (hidden1.value === '') {
                hidden1.value = code;
            } else if (hidden2.value === '') {
                hidden2.value = code;
            } else {
                hidden2.value = code;
            }
            render();
        }

        cards.forEach(function (card) {
            card.addEventListener('click', function () { handleClick(card); });
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    handleClick(card);
                }
            });
        });

        render();
    });
})();
</script>
</body>
</html>
    <?php
}
