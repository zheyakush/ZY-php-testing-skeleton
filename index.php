<?php

ini_set('display_errors', 1);
require_once "./Test.php";
session_start();
/** @var Test $test */
$test = new Test();

switch ($test->getCurrentStep()) {
    case 2:
        $file = "view/step2.php";
        break;
    case 3:
        $file = "view/step3.php";
        break;
    case 1:
    default:
        $file = "view/step1.php";
}


/**
 * @param $filename
 * @return string
 */
function includeOutput($filename)
{
    ob_start();
    include $filename;
    $contents = ob_get_contents();
    ob_end_clean();

    return $contents;
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>
        <?php echo $test->getPageTitle(); ?>
    </title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<div class="content">
    <?php echo includeOutput($file) ?>
</div>
</body>
</html>
