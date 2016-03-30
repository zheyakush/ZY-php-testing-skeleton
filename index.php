<?php

ini_set('display_errors', 1);
require_once "./Test.php";
session_start();
/** @var Test $test */
$test = new Test();


switch ($test->getCurrentStep()) {
    case 2:
        if (isAjax()) {
            $html = includeOutput("view/step2/question-content.php");
            echo json_encode([
                "step" => $test->getCurrentStep(),
                "html" => $html
            ]);
            return;
        } else {
            $file = "view/step2.php";
        }
        break;
    case 3:
        if (isAjax()) {
            $html = includeOutput("view/step3.php");
            echo json_encode([
                "step" => $test->getCurrentStep(),
                "html" => $html
            ]);
            return;
        } else {
            $file = "view/step3.php";
        }
        break;
    case 1:
    default:
        if (isAjax() && $test->isReviewMode()) {

            $html = includeOutput("view/step3.php");
            echo json_encode([
                "redirect" => true
            ]);
            return;
        } else {
            $file = "view/step1.php";
        }
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

/**
 * @return bool
 */
function isAjax()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) {

        header('Content-Type: application/json');
        return true;
    }

    return false;
}

/**
 * @param $file
 */
function ajaxResponse($file)
{
    global $test;
    $html = includeOutput($file);
    echo json_encode([
        "step" => $test->getCurrentStep(),
        "html" => $html
    ]);
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
    <script src="js/timer.js"></script>
</head>
<body>
<?php echo includeOutput($file) ?>
</div>
</body>
</html>
