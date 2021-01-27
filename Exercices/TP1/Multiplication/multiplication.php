<html>
<head>
<title>Table de multiplication</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="multiplication.css" />
</head>

<body>
    <table>
        <tbody>
            <?php 
                if (isset($_GET['rows'])) {
                    $rows = $_GET['rows'];
                } else {
                    $rows = 10;
                }

                if (isset($_GET['cols'])) {
                    $cols = $_GET['cols'];
                } else {
                    $cols = 10;
                }

                for ($i=1; $i<=$rows; $i++) {
                    if (isset($_GET['highlighted']) && $i == $_GET['highlighted']) {
            ?>
            <tr class="highlighted">
            <?php
                    } else {         
            ?>
            <tr>
                <?php 
                    }
                    for ($j=1; $j<=$cols; $j++) {
                ?>
                <td><?php echo $i*$j; ?></td>
                <?php 
                    }
                ?>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

</body>
</html>

