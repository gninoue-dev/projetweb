<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Plateforme Examens</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Plateforme Examens</h1>
    <nav>
      <a href="index.php">Accueil</a>
      <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'etudiant') { ?>
        <a href="examen.php">Examen</a>
        <a href="resultats.php">Résultats</a>
      <?php } ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'examinateur') { ?>
        <a href="questions.php?id_exam=1">Questions</a>
        <a href="rapport.php?id_exam=1">Rapport</a>
      <?php } ?>
      <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
        <a href="admin.php">Utilisateurs</a>
      <?php } ?>
      <?php if(isset($_SESSION['user_id'])) { ?>
        <a href="logout.php">Déconnexion</a>
      <?php } ?>
    </nav>
  </header>
  <main>
