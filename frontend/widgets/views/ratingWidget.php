<?php
/**
 * @var $rating integer
 */

for ($i = 0; $i < 5; $i++) {
    if ($i < floor($rating)) {
        echo '<span></span>';
    } else {
        echo '<span class="star-disabled"></span>';
    }
}
echo "<b>{$rating}<b>";

