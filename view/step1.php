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
?>
<div id="step<?php echo $test->getCurrentStep() ?>">
    <h1 class="title">Choose test</h1>

    <div class="content quizcontainer">
        <div class="question-head">
            <h3 class="label"><span>Course :</span></h3>
        </div>
        <form action="">
            <p class="mlw_qmn_question">
                <select name="course" id="course">
                    <?php foreach ($test->getAvailableCourses() as $index => $name) : ?>
                        <option value="<?php echo $index; ?>"><?php echo $name["name"] ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <div class="row">
                <label for="">Time limit:</label>
                <input type="text" id="timeLimit" name="timeLimit" placeholder="minutes"/>
            </div>
            <div class="actions clearfix">
                <button type="submit" name="mode" value="test">Test</button>
                <button type="submit" name="mode" value="review">Review</button>
                <button type="submit" name="mode" value="learn">Learn</button>
            </div>
        </form>
    </div>
</div>
