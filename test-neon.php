<?php
$dsn = 'pgsql:host=ep-green-dawn-a40yh4gg-pooler.us-east-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require';
$pdo = new PDO($dsn, 'neondb_owner', 'npg_ltmGMs60FEyK');
echo $pdo->query('SELECT version()')->fetchColumn();
