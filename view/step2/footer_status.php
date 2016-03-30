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
<?php if (!$test->isReviewMode()) : ?>
    <div class="footer">
        <span class="result">Current progress: </span>
        <span class="correct"><?php echo $test->getCorrectPercents(); ?>%</span>
        <span class="wrong"><?php echo $test->getWrongPercents(); ?>%</span>
    </div>
<?php endif; ?>
