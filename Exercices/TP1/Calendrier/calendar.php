<html>
<head>
<title>Calendrier</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="calendar.css" />
</head>
<body>
    <div class="flex">
        <table>
            <thead>
                <tr>
                    <th>Dayname</th>
                    <th>Day</th>
                    <th>?</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $month = isset($_GET['month']) ? $_GET['month'] : date('F');
                    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                    $datestr = "1"." ".$month." ".$year;
                    for ($daynum=1; $daynum<date('t', strtotime($datestr)); $daynum++) {
                        $datestr2 = $daynum." ".$month." ".$year;
                ?>
                    <tr>
                        <td><?php echo date('l', strtotime($datestr2)); ?></td>
                        <td><?php echo $daynum; ?></td>
                        <td></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        <form method="get" action="calendar.php" id="month-form">
            <div class="form-content">
                <label for="month">Month : </label>
                <select id="month" name="month" form="month-form">
                    <option value="January" <?php echo $month=="January" ? 'selected' : ''; ?>>January</option>
                    <option value="February" <?php echo $month=="February" ? 'selected' : ''; ?>>February</option>
                    <option value="March" <?php echo $month=="March" ? 'selected' : ''; ?>>March</option>
                    <option value="April" <?php echo $month=="April" ? 'selected' : ''; ?>>April</option>
                    <option value="May" <?php echo $month=="May" ? 'selected' : ''; ?>>May</option>
                    <option value="June" <?php echo $month=="June" ? 'selected' : ''; ?>>June</option>
                    <option value="July" <?php echo $month=="July" ? 'selected' : ''; ?>>July</option>
                    <option value="August" <?php echo $month=="August" ? 'selected' : ''; ?>>August</option>
                    <option value="September" <?php echo $month=="September" ? 'selected' : ''; ?>>September</option>
                    <option value="October" <?php echo $month=="October" ? 'selected' : ''; ?>>October</option>
                    <option value="November" <?php echo $month=="November" ? 'selected' : ''; ?>>November</option>
                    <option value="December" <?php echo $month=="December" ? 'selected' : ''; ?>>December</option>
                </select><br />
                <label for="year">Year : </label>
                <input type="number" id="year" name="year" min="0" value=<?php echo $year; ?> /><br />
                <input type="Submit" value="Valider" />
            </div>
        </form>
    </div>
</body>
</html>