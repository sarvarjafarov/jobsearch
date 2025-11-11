<?php
$pdo = new PDO('pgsql:host=db.qtaimvjrvdgyshtpqqrh.supabase.co;port=5432;dbname=postgres', 'postgres', 'SUPABASE_PASSWORD_HERE');
var_dump($pdo->query('select 1')->fetch());
