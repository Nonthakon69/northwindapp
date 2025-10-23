<?php 
  function getEdit($pdo,$tbName,$pkName,$pkValue){
   $stmt = $pdo->prepare("SELECT * FROM $tbName WHERE $pkName = :param_pid ;");
   $stmt->execute([':param_pid' => $pkValue]);
   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return count(value: $rows)==0? null : $rows[0];
}
  // function getInsert($pdo, $tbName) {
  //   $stmt = $pdo->prepare("SELECT * FROM $tbName LIMIT 1;");
  //   $stmt->execute([]);
  //   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //   return count(value: $rows) == 0 ? null : $rows[0];
  // }

 function getNewID($pdo,$tbName,$pkName){
   $stmt = $pdo->prepare("SELECT MAX($pkName) + 1 as NewID from $tbName;");
   $stmt->execute();
   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return count(value: $rows)==0? null : $rows[0]['NewID'];
}

?>