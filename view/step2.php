<?php
/**
 * Class
 *
 * @category     Speroteck
 * @package      Speroteck_
 * @copyright    Copyright (c) 2016 Speroteck Inc. (www.speroteck.com)
 * @author       Speroteck team (dev@speroteck.com)
 * @license      http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
global $test;
$question = $test->getQuestion();
?>

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
                    <button type="button" id="check-answer" onclick="document.Test.proceed()">Proceed</button>

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
                    <button type="button" id="next-question" onclick="document.Test.next()">Next</button>
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