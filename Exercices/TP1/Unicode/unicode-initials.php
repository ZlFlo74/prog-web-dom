<?php
    require_once('libunicodega.php');
?>
<html>
<head>
<title>Unicode Initials</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="unicode-initials.css" />
</head>
<body>
    <table>
        <tbody>
<?php
    $arrayOfWords = explode(" ", $_GET['mots']);

    foreach ($arrayOfWords as $word) {
        if (!empty($word)) {
?>
            <tr>
            <?php
                $code_without_prefix = hexdec(uni_ord($word[0], ''));
                $code_modulo_16 = $code_without_prefix % 16;
                $first_code_of_row = $code_without_prefix - $code_modulo_16;
                for ($code=$first_code_of_row; $code<$first_code_of_row+16; $code++) {
            ?>
                <td><?php echo get_unicode_name(dechex($code)); ?></td>
            <?php
                }
            ?>
            </tr>
<?php
        }
    }
?>
        </tbody>
    </table>
</body>
</html>