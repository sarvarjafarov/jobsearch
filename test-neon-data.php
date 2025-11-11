<?php
$dsn = 'pgsql:host=ep-something.eu-central-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require';
printf("Connecting using DSN: %s\n", $dsn);
$pdo = new PDO($dsn, 'neondb_owner', 'npg_ltmGMs60FEyK');
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id serial primary key, name text not null)");
$pdo->exec("INSERT INTO migrations (name) VALUES ('test-migration')");
$rows = $pdo->query('SELECT * FROM migrations')->fetchAll(PDO::FETCH_ASSOC);
var_dump($rows);
