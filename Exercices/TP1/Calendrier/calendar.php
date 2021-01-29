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
                <?php 
                    $eventday = isset($_GET['day']) ? $_GET['day'] : 0;
                    $event = isset($_GET['event']) ? $_GET['event'] : '';
                    $month = isset($_GET['month']) ? $_GET['month'] : date('F');
                    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                ?>
                <tr>
                    <th colspan="7"><?php echo $month; ?></th>
                </tr>
                <tr>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php 
                    $datestr = "1"." ".$month." ".$year;
                    for ($daynum=1; $daynum<=date('t', strtotime($datestr)); $daynum++) {
                        $datestr = $daynum." ".$month." ".$year;
                        $day = date('N', strtotime($datestr)); // From 1 to 7
                        if ($daynum == 1) {
                            for ($i=1; $i<$day; $i++) {
                ?>
                    <td></td>
                <?php 
                            }
                        }
                        if ($daynum != 1 && $day == 1) {
                ?>
                </tr>
                <tr>
                <?php 
                        }
                        if ($daynum == $eventday) {
                ?> 
                    <td class="event" title=<?php echo $event ?>>
                        <?php echo $daynum; ?>
                    </td>
                <?php 
                        }
                        else {
                ?>
                    <td>
                        <?php echo $daynum; ?>
                    </td>
                <?php
                        }
                    }
                    for ($i=$day; $i<7; $i++) {
                ?>
                    <td></td>
                <?php 
                    }
                ?>
                </tr>
            </tbody>
        </table>
        <div class="forms">
            <form method="get" action="calendar.php" id="month-form">
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
                <input type="number" id="year" name="year" value=<?php echo $year; ?> /><br />
                <p><strong class="form-title">Add an event</strong></p>
                <label for="day">Day : </label>
                <input type="number" id="day" name="day" min="1" max=<?php $maxday = date('t', strtotime($datestr)); echo "'$maxday'" ?> /><br />
                <label for="event">Event : </label>
                <input type="text" id="event" name="event" /><br />
                <input type="Submit" value="Valider" />
            </form>
        </div>
    </div>
</body>
</html>