<?php

// Authors : Robert Walport, Rashmi Raman
// Code to provide GUI to user to choose the location to pass on as a search parameter to the Yelp API


echo "<form method='post' action='getYelpDatav2.php'>";
echo '<div>';
echo '<SELECT id="Location"> <option value="hellskitchen">Hells Kitchen</option><option value="chinatown">Chinatown</option><option value="flushing">Flushing</option><option value="chelsea">Chelsea</option><option value="uws">Upper West Side</option>';
echo "<input type='submit' name='submitBtn' value='Get Yelp Data' />";
echo '</div>';
echo '</form>';
?>