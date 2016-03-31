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
        <div class="row<?php echo $test->hasAnswer() ? " disabled" : "" ?>">
            <?php if ($test->isRadioBtn()) : ?>
                <input type="radio" <?php if ($test->hasAnswer()) {
                    echo "disabled=\"disabled\"";
                } ?>
                       name="a" id="a<?php echo $i; ?>" value="<?php echo htmlentities($index); ?>"
                    <?php echo $test->isChecked($index); ?> />
            <?php else : ?>
                <input type="checkbox" <?php if ($test->hasAnswer()) {
                    echo "disabled=\"disabled\"";
                } ?>
                    <?php echo $test->isChecked($index); ?>
                       name="a[]" id="a<?php echo $i; ?>" value="<?php echo htmlentities($index); ?>"/>
            <?php endif; ?>
            <label for="a<?php echo $i; ?>"
                   class="<?php echo $test->getClass($index) ?>"><?php echo htmlentities($index); ?></label>

        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
    <?php if (!$test->hasAnswer()) : ?>
        <div class="actions clearfix">
            <a class="back" href="javascript:void(0)" onclick="Test.reset(this)">Reset</a>
            <button type="button" id="check-answer" onclick="Test.proceed(this)">Proceed</button>

            <?php echo includeOutput("view/step2/footer_status.php") ?>
        </div>
    <?php endif; ?>
</form>

<?php if ($test->hasAnswer()) : ?>
    <div class="actions clearfix">
        <form action="" method="post">
            <input name="course" type="hidden" value="<?php echo $test->getActiveCourse() ?>"/>
            <input name="q" type="hidden" value="<?php echo $test->getCurrIndexQuestion() + 1 ?>"/>
            <?php if ($test->isReviewMode()) : ?>
                <a class="back" href="javascript:void(0)" onclick="Test.reset(this)">Reset</a>
            <?php endif; ?>
            <button type="button" id="next-question" onclick="Test.next(this)">Next</button>
            <?php if ($test->isReviewMode() && $test->getCurrIndexQuestion() > 0) : ?>
                <button type="button" id="prev-question" onclick="Test.prev(this)">Prev</button>
            <?php endif; ?>
        </form>
        <?php echo includeOutput("view/step2/footer_status.php") ?>
    </div>
<?php endif; ?>
