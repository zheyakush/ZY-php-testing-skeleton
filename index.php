<?php
ini_set('display_errors', 1);
require_once "./Test.php";
session_start();
$test = new Test();
$question = $test->getQuestion();
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>
        <?php switch ($test->getCurrentStep()): ?>
        <?php case 1: ?>
                Choose test
                <?php break; ?>
        <?php case 2: ?>
                Test: <?php echo $test->getCourseData("name") ?>
            <?php break; ?>
        <?php case 3: ?>
                Results
            <?php break; ?>
        <?php endswitch; ?>
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        (function($){
            $(document).ready(function() {
                $("body").keydown(function(e) {
                    var keyCode = (e.which) ? e.which : e.keyCode;
                    var keys = [49, 50, 51, 52, 53, 54, 55, 56, 57, 97, 98, 99, 100, 101, 102, 103, 104, 105];
                    var value = keys.indexOf(keyCode) >= 9 ? keys.indexOf(keyCode)-9 : keys.indexOf(keyCode);
                    if (value  != -1) {
                        var el = $('input[type=radio],input[type=checkbox]').eq(value);
                        if(!el.parents("div").hasClass("disabled")){
                            if (el.is(':checked') && !el.is(':radio')) {
                                el.prop("checked", false);
                            } else {
                                el.prop("checked", true);
                            }
                        }
                    }
                    if (keyCode == 13) {
                        $("form").submit();
                    }
                });
                $(".row").on("click", function(){
                    if($(this).hasClass("disabled")) {
                        return false;
                    }

                    $(".row").find("input[type='radio']").prop("chec1ked", false);
                    var el = $(this).find("input");
                    if (el.is(':checked') && !el.is(':radio')) {
                        el.prop("checked", false);
                    } else {
                        el.prop("checked", true);
                    }

                })
            });
            document.Test = {};
            document.Test.reset = function() {
                if (confirm("Current progress will be lost. Are you sure?")) {
                    window.location.replace(window.location.origin + window.location.pathname);
                } else {
                    return false;
                }
            };
        })(jQuery);
    </script>
</head>
<body>
<?php switch ($test->getCurrentStep()): ?>
<?php case 1: ?>
    <div id="step1">
        <h1>Choose test</h1>

        <div class="content">
            <label for="course">Course :</label>

            <form action="">
                <select name="course" id="course">
                    <?php foreach ($test->getAvailableCourses() as $index => $name) : ?>
                        <option value="<?php echo $index; ?>"><?php echo $name["name"] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="mode" value="test">Test</button>
                <button type="submit" name="mode" value="review">Review</button>
            </form>
        </div>
    </div>
    <?php break;
case 2: ?>
    <div id="step2">

        <h1 class="title"><?php echo $test->getCourseData("name") ?></h1>

        <div class="content quizcontainer">
            <div class="question-head">
                <h3 class="label"><span>Question <?php echo $test->getCurrIndexQuestion() + 1; ?> /
                    <?php echo count($test->getCourseData("questions")); ?></span></h3>
            </div>


            <form action="" method="post">
                <input name="course" type="hidden" value="<?php echo $test->getActiveCourse() ?>"/>
                <input name="q" type="hidden" value="<?php echo $test->getCurrIndexQuestion() ?>"/>

                <p class="mlw_qmn_question"><?php echo $question["text"]; ?></p>
                <?php $i = 0; ?>
                <?php foreach ($question["answers"] as $index => $name) : ?>
                    <div class="row <?php if($test->hasAnswer()) { echo "disabled"; }?>">
                        <?php if($test->isRadioBtn()) : ?>
                            <input type="radio" <?php if ($test->hasAnswer()) { echo "disabled=\"disabled\""; }?>
                                   name="a" id="a<?php echo $i; ?>" value="<?php echo htmlentities($index); ?>"
                                <?php echo $test->isChecked($index);?> />
                        <?php else : ?>
                            <input type="checkbox" <?php if($test->hasAnswer()) { echo "disabled=\"disabled\""; }?>
                                <?php echo $test->isChecked($index);?>
                                   name="a[]" id="a<?php echo $i; ?>" value="<?php echo htmlentities($index); ?>"/>
                        <?php endif; ?>
                        <label for="a<?php echo $i; ?>"
                               class="<?php echo $test->getClass($index)?>"><?php echo htmlentities($index); ?></label>

                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
                <?php if(!$test->hasAnswer()) : ?>
                    <div class="actions clearfix">
                        <a class="back" href="javascript:void(0)" onclick="document.Test.reset()">Reset</a>
                        <button type="submit" id="check-answer">Proceed</button>

                        <div class="footer">
                            <span class="result">Current progress: </span>
                            <span class="correct"><?php echo $test->getCorrectPercents(); ?>%</span>
                            <span class="wrong"><?php echo $test->getWrongPercents(); ?>%</span>
                        </div>
                    </div>
                <?php endif; ?>
            </form>

            <?php if($test->hasAnswer()) : ?>
                <div class="actions clearfix">
                    <form action="" method="post">
                        <input name="course" type="hidden" value="<?php echo $test->getActiveCourse() ?>"/>
                        <input name="q" type="hidden" value="<?php echo $test->getCurrIndexQuestion() + 1 ?>"/>
                        <?php if ($test->isReviewMode()) : ?>
                            <a class="back" href="javascript:void(0)" onclick="document.Test.reset()">Reset</a>
                        <?php endif; ?>
                        <button type="submit" id="next-question">Next</button>
                    </form>
                    <?php if (!$test->isReviewMode()) : ?>
                    <div class="footer">
                        <span class="result">Current progress: </span>
                        <span class="correct"><?php echo $test->getCorrectPercents(); ?>%</span>
                        <span class="wrong"><?php echo $test->getWrongPercents(); ?>%</span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
    <?php break;
case 3: ?>
<div id="step3">

    <h1>Results</h1>
    <div class="content quizcontainer">
        <div class="details">
            <p class="correct"><span class="label">Correct answers:</span> <?php echo $test->getCorrectCount(); ?>
                (<?php echo $test->getCorrectPercents(); ?>%)</p>
            <p class="wrong"><span class="label">Wrong answers:</span> <?php echo $test->getWrongCount(); ?>
                (<?php echo $test->getWrongPercents(); ?>%)</p>
        </div>
        <div class="status <?php echo ($test->isPassed() ? "correct" : "wrong"); ?>">
            <h1><?php echo $test->getStatus(); ?></h1>
            <?php if(!$test->isPassed()) : ?>
            <span class="info">required = <?php echo $test->getSuccessLimit() ?>%</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer">
        <a href="javascript:void(0)" onclick="window.location.replace(window.location.origin + window.location.pathname)">Restart</a>
    </div>
</div>

    <?php break;
endswitch; ?>
</body>
</html>