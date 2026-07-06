<?php
require __DIR__ . '/../app/bootstrap.php';
$c = new AkunController();
$aksi = $_GET['aksi'] ?? '';
if ($aksi === 'tambah') { $c->store(); }
elseif ($aksi === 'edit') { $c->update(); }
elseif ($aksi === 'hapus') { $c->hapus(); }
else { die('Aksi tidak dikenali.'); }
