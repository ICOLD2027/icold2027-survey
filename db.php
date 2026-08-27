<?php
// DB connection + schema bootstrap.
//
// If a DATABASE_URL env var is present (e.g. Render's managed Postgres),
// use that -- it survives web-service restarts/redeploys, unlike the
// container's local disk. Otherwise fall back to a local SQLite file,
// for hosts that give this app persistent disk (typical shared PHP hosting).

function survey_migrate(PDO $pdo): void
{
    try {
        $pdo->exec('ALTER TABLE responses ADD COLUMN email TEXT');
    } catch (PDOException $e) {
        // Column already exists on a previously-created table -- fine.
    }
}

function survey_db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $databaseUrl = getenv('DATABASE_URL');

    if ($databaseUrl) {
        $parts = parse_url($databaseUrl);
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=prefer',
            $parts['host'],
            $parts['port'] ?? 5432,
            ltrim($parts['path'], '/')
        );
        $pdo = new PDO($dsn, $parts['user'], $parts['pass'] ?? '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS responses (
                id SERIAL PRIMARY KEY,
                created_at TEXT NOT NULL,
                lang TEXT NOT NULL,
                q1_choice1 TEXT, q1_choice2 TEXT, q1_other TEXT,
                q2_choice1 TEXT, q2_choice2 TEXT, q2_other TEXT,
                q3_choice1 TEXT, q3_choice2 TEXT, q3_other TEXT
            )'
        );
        survey_migrate($pdo);

        return $pdo;
    }

    $dataDir = __DIR__ . '/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0775, true);
    }

    $dbPath = $dataDir . '/survey.sqlite';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS responses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            created_at TEXT NOT NULL,
            lang TEXT NOT NULL,
            q1_choice1 TEXT, q1_choice2 TEXT, q1_other TEXT,
            q2_choice1 TEXT, q2_choice2 TEXT, q2_other TEXT,
            q3_choice1 TEXT, q3_choice2 TEXT, q3_other TEXT
        )'
    );
    survey_migrate($pdo);

    return $pdo;
}
