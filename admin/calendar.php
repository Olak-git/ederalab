<?php

    use src\Services\CalendarService;
    include '../autoload.php';

    $year = date('Y');
    $month = date('m');

    if(isset($_GET['y'])) {
        $year = $_GET['y'];
    }

    if(isset($_GET['m'])) {
        $month = $_GET['m'];
    }

    $cal = new CalendarService($year, $month);
    $weeks = $cal->getWeeks();

    var_dump($weeks);
?>
<style>
    table {
        border-collapse: collapse;
    }
    td, th {
        max-width: 40px;
        height: 25px;
        padding: 1rem;
        position: relative;
        border: 1px solid silver;
        word-break: break-all;
        text-align: center;
    }
</style>
<div style="width:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <a href="<?= 'calendar.php?y=' . $cal->getPreviousYear() . '&amp;m=' . $cal->getPreviousMonth(); ?>">prev</a>
        <span><?= $cal->getYear() . ' / ' . $cal->getMonth(); ?></span>
        <a href="<?= 'calendar.php?y=' . $cal->getNextYear() . '&amp;m=' . $cal->getNextMonth(); ?>">next</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Lun</th>
                <th>Mar</th>
                <th>Mer</th>
                <th>Jeu</th>
                <th>Ven</th>
                <th>Sam</th>
                <th>Dim</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($weeks as $k => $week): ?>
                <tr>
                <?php foreach($week as $ki => $day): ?>
                    <td>
                        <?= $day ?>
                        <?= !is_null($day) && strtotime($cal->getYear() . '-' . $cal->getMonth() . '-' . $day) == strtotime(date('Y-m-d')) ? '/ default' : ''; ?>
                    </td>
                <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
