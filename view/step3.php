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
    <h1 class="title">Results</h1>

    <div class="content quizcontainer">
        <div class="details">
            <p class="correct"><span class="label">Correct answers:</span> <?php echo $test->getCorrectCount(); ?>
                (<?php echo $test->getCorrectPercents(); ?>%)</p>
            <p class="wrong"><span class="label">Wrong answers:</span> <?php echo $test->getWrongCount(); ?>
                (<?php echo $test->getWrongPercents(); ?>%)</p>
        </div>
        <div class="status <?php echo($test->isPassed() ? "correct" : "wrong"); ?>">
            <h1><?php echo $test->getStatus(); ?></h1>
            <?php if (!$test->isPassed()) : ?>
                <span class="info">required = <?php echo $test->getSuccessLimit() ?>%</span>
            <?php endif; ?>
        </div>
        <div class="footer">
            <a href="javascript:void(0)" class="back"
               onclick="window.location.replace(window.location.origin + window.location.pathname)">Restart</a>
        </div>
    </div>
</div>
