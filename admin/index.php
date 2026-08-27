<?php
session_start();
require_once __DIR__ . '/../admin_config.php';
require_once __DIR__ . '/../data_config.php';
require_once __DIR__ . '/../db.php';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['username'])) {
    $u = (string)($_POST['username'] ?? '');
    $p = (string)($_POST['password'] ?? '');
    if (hash_equals(ADMIN_USERNAME, $u) && hash_equals(ADMIN_PASSWORD, $p)) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $loginError = true;
    }
}

$loggedIn = !empty($_SESSION['admin_logged_in']);

if (!$loggedIn) {
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — ICOLD 2027 Survey</title>
<link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
<div class="login-wrap">
    <h1>ICOLD 2027 Survey — Admin</h1>
    <?php if (!empty($loginError)): ?>
        <div class="error-banner">Incorrect username or password.</div>
    <?php endif; ?>
    <form method="post" action="index.php">
        <input type="text" name="username" placeholder="Username" autocomplete="username" required>
        <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
        <button type="submit">Log in</button>
    </form>
</div>
</body>
</html>
    <?php
    exit;
}

$pdo = survey_db();
$totalResponses = (int)$pdo->query('SELECT COUNT(*) FROM responses')->fetchColumn();
$latest = $pdo->query('SELECT created_at FROM responses ORDER BY id DESC LIMIT 1')->fetchColumn();

function fetch_counts(PDO $pdo, string $column): array
{
    $stmt = $pdo->query("SELECT $column AS code, COUNT(*) AS cnt FROM responses WHERE $column IS NOT NULL AND $column != '' GROUP BY $column");
    $out = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $out[$row['code']] = (int)$row['cnt'];
    }
    return $out;
}

function fetch_other_texts(PDO $pdo, string $column): array
{
    $stmt = $pdo->query("SELECT $column AS txt, created_at FROM responses WHERE $column IS NOT NULL AND TRIM($column) != '' ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard — ICOLD 2027 Survey</title>
<link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
<div class="admin-wrap">
    <div class="admin-header">
        <h1>ICOLD 2027 Survey — Response Statistics</h1>
        <div class="toolbar">
            <a class="btn" href="export.php">Export CSV</a>
            <a class="btn" href="index.php?logout=1">Log out</a>
        </div>
    </div>

    <div class="summary-row">
        <div class="summary-card">
            <div class="num"><?= $totalResponses ?></div>
            <div class="lbl">Total responses</div>
        </div>
        <div class="summary-card">
            <div class="num"><?= $latest ? htmlspecialchars((string)$latest, ENT_QUOTES, 'UTF-8') : '—' ?></div>
            <div class="lbl">Latest submission (UTC)</div>
        </div>
    </div>

    <?php foreach ($SURVEY_QUESTIONS as $qi => $q): ?>
        <?php
        $c1 = fetch_counts($pdo, $q['code'] . '_choice1');
        $c2 = fetch_counts($pdo, $q['code'] . '_choice2');
        $maxCount = 1;
        foreach ($q['options'] as $opt) {
            $maxCount = max($maxCount, $c1[$opt['code']] ?? 0, $c2[$opt['code']] ?? 0);
        }
        $others = fetch_other_texts($pdo, $q['code'] . '_other');
        ?>
        <div class="admin-q-card">
            <h2>Q<?= $qi + 1 ?>. <?= htmlspecialchars($q['title_en'], ENT_QUOTES, 'UTF-8') ?></h2>
            <div class="bar-legend">■ Navy = 1st choice &nbsp; ■ Gold = 2nd choice &nbsp; (bar length relative to the highest count in this question)</div>
            <?php foreach ($q['options'] as $opt):
                $n1 = $c1[$opt['code']] ?? 0;
                $n2 = $c2[$opt['code']] ?? 0;
                $w1 = round($n1 / $maxCount * 100);
                $w2 = round($n2 / $maxCount * 100);
                $label = strtoupper($opt['code']) . '. ' . $opt['en'];
            ?>
            <div class="bar-row">
                <div title="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(mb_strimwidth($label, 0, 34, '…'), ENT_QUOTES, 'UTF-8') ?></div>
                <div>
                    <div class="bar-track"><div class="bar-fill" style="width:<?= $w1 ?>%"></div></div>
                    <div class="bar-track" style="margin-top:3px;"><div class="bar-fill second" style="width:<?= $w2 ?>%"></div></div>
                </div>
                <div>1st: <?= $n1 ?><br>2nd: <?= $n2 ?></div>
            </div>
            <?php endforeach; ?>

            <?php if (!empty($others)): ?>
            <div class="other-list">
                <h3>"Other" free-text responses (<?= count($others) ?>)</h3>
                <ul>
                    <?php foreach ($others as $o): ?>
                        <li><?= htmlspecialchars($o['txt'], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="admin-q-card">
        <h2>Submitted emails (<?= $totalResponses ?>)</h2>
        <?php
        $emailRows = $pdo->query('SELECT id, created_at, email FROM responses ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if (empty($emailRows)): ?>
            <p style="color:var(--text-muted); font-size:13px;">No responses yet.</p>
        <?php else: ?>
        <div class="table-scroll">
            <table class="raw-table">
                <thead>
                    <tr><th>#</th><th>Submitted (UTC)</th><th>Email</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($emailRows as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars((string)$row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)$row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
