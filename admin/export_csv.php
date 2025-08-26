<?php
require __DIR__.'/../config.php'; require __DIR__.'/../helpers.php'; require_login();
$q = trim($_GET['q'] ?? '');
$where=''; $params=[];
if ($q!==''){ $where="WHERE student_name LIKE :q OR notes LIKE :q OR communication LIKE :q OR social LIKE :q OR academic LIKE :q OR adaptive LIKE :q OR food_drink LIKE :q OR send_in LIKE :q"; $params[':q']="%$q%"; }
$stmt = db()->prepare("SELECT * FROM reports $where ORDER BY report_date DESC, id DESC");
$stmt->execute($params);
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=brody_reports.csv');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','date','student','communication','social','academic','adaptive','specialists','food_drink','bathroom','send_in','notes','created_at']);
while($r=$stmt->fetch()){
  fputcsv($out, [
    $r['id'],$r['report_date'],$r['student_name'],$r['communication'],$r['social'],$r['academic'],$r['adaptive'],
    implode('|', json_decode($r['specialists']??'[]', true) ?: []),
    $r['food_drink'],
    implode('|', json_decode($r['bathroom']??'[]', true) ?: []),
    $r['send_in'],$r['notes'],$r['created_at']
  ]);
}
