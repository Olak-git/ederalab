
<?php 
    $db = $router->getDb();
    function totalCommandesRecues($year = null, $month = null) {
        global $db;
        $params = [];
        $sql = 'SELECT COUNT(id) n  
                FROM commande 
                WHERE archive = 0 
                AND valide = 0';
        if($year !== null) {
            $sql .= ' AND EXTRACT(YEAR FROM date_envoie) = :year';
            $params['year'] = $year;
        }
        if($month !== null) {
            $sql .= ' AND EXTRACT(MONTH FROM date_envoie) = :month';
            $params['month'] = $month;
        }
        return $db->query($sql, $params)->fetchColumn();
    }
    function totalCommandesLivrees($year = null, $month = null) {
        global $db;
        $params = [];
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 2';
        if($year !== null) {
            $sql .= ' AND EXTRACT(YEAR FROM date_envoie) = :year';
            $params['year'] = $year;
        }
        if($month !== null) {
            $sql .= ' AND EXTRACT(MONTH FROM date_envoie) = :month';
            $params['month'] = $month;
        }
        return $db->query($sql, $params)->fetchColumn();
    }
    function totalCommandesEnattente() {
        global $db;
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 0';
        return $db->query($sql)->fetchColumn();
    }
    function totalCommandesAnnulees() {
        global $db;
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = -1';
        return $db->query($sql)->fetchColumn();
    }
    

    $r1 = totalCommandesRecues();
    $r2 = totalCommandesLivrees();
    $r3 = totalCommandesEnattente();
    $r4 = totalCommandesAnnulees();
?>
<a href="gestion-suivi-commandes-recues.php" class="btn-block<?= $code == 1 ? ' active' : ''; ?>">
    <span class="title">Commandes reçues</span>
    <span class="count"><?= $r1 < 10 ? '0' . $r1 : $r1; ?></span>
</a>
<a href="gestion-suivi-commandes-livrees.php" class="btn-block<?= $code == 2 ? ' active' : ''; ?>">
    <span class="title">Commandes livrées</span>
    <span class="count"><?= $r2 < 10 ? '0' . $r2 : $r2; ?></span>
</a>
<a href="gestion-suivi-commandes-en-attente.php" class="btn-block<?= $code == 3 ? ' active' : ''; ?>">
    <span class="title">Commandes en attente</span>
    <span class="count"><?= $r3 < 10 ? '0' . $r3 : $r3; ?></span>
</a>
<a href="gestion-suivi-commandes-annulees.php" class="btn-block<?= $code == 4 ? ' active' : ''; ?>">
    <span class="title">Commandes annulée</span>
    <span class="count"><?= $r4 < 10 ? '0' . $r4 : $r4; ?></span>
</a>