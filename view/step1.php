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
