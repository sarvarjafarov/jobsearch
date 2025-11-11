<?php
$dsn = 'pgsql:host=52.20.107.131;port=5432;dbname=neondb;sslmode=require';
$pdo = new PDO($dsn, 'neondb_owner', 'npg_ltmGMs60FEyK');
echo $pdo->query('SELECT version()')->fetchColumn();
