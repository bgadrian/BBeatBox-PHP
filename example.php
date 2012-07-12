<?php

require_once('bbeatbox.class.php');

//USAGE 1
#BBeatBox::executionTime(false); //at the end, show the execution time
//USAGE 2
//AUTOMATICALLY call the function at the end of the run (only works in PHP 5.1, 5.2 and 5.3)
register_shutdown_function('BBeatBox::executionTime',false,true,4,true);
 
BBeatBox::executionTime(); //start session

echo '<h2>Mask text </h2><br />'.BBeatBox::maskText('btools@gmail.com');//bto..@..il.com
echo '<br />'.BBeatBox::maskText('btools@gmail.com',4,false,'@');//btoo..@gmail.com
echo '<br />'.BBeatBox::maskText('btools@gmail.com',false,4,'@',true,'??');//btools@??.com
//next ex : the middle character will not be found and not displayed
echo '<br />'.BBeatBox::maskText('btools@gmail.com',4,4,'X');//btoo.....com
//if we want all the start or end, and the middle is not found, is also a fallback
echo '<br />'.BBeatBox::maskText('btools@gmail.com',false,false,'X');//bto....com */


 
echo '<h2>Time ago </h2> ';
$translate_periods_RO = array(
					'decade' => 'decada',
			        'year' => 'an',
			        'month' => 'luna',
			        'week' => 'saptamana', 
			        'day' => 'zi',
			        'hour' => 'ora',
			        'minute' => 'minut',
			        'second' => 'secunda');
$translate_periods_plural_RO =  array(
					'decade' => 'decade',
			        'year' => 'ani',
			        'month' => 'luni',
			        'week' => 'saptamani', 
			        'day' => 'zile',
			        'hour' => 'ore',
			        'minute' => 'minute',
			        'second' => 'secunde');	
			/** Remember works with timestamps too ! */		        
echo '<br />Simple usage : posted '.BBeatBox::timeAgo('2011-1-1 14:34:21').' ago';
echo '<br />Complex usage : Difference is '.BBeatBox::timeAgo('2011-1-1 14:34:21',4,'2012-2-1 10:33:22').' ';
echo '<br />[RO] Complex usage : Diferenta este de '.BBeatBox::timeAgo('2011-1-1 14:34:21',4,'2012-2-1 10:33:22',$translate_periods_RO,$translate_periods_plural_RO).' ';
echo '<br />Simple usage, with special rule, dates with a difference bigger then 45 days, display the date itself, not nice form : '.
			BBeatBox::timeAgo('2011-1-1 14:34:21',2,false,false,false,45);
 
 
echo '<h2>Transform array </h2>';
	$db_regular_result = array(
		array('id'=>2,'name'=>'The two'),
		array('id'=>5,'name'=>'The wolf five'),
		array('id'=>9,'name'=>'The matrix 9'),
	);
	var_dump($db_regular_result);
	//now call the magic
	echo '<br />Transform the array to a key indexed one.';
	var_dump(BBeatBox::transform_array($db_regular_result,array('id'=>'name')));
	echo '<Br />Now we only need a list of IDS, example to use in a query ("WHERE `id` IN (....)").';
	var_dump(implode(',',BBeatBox::transform_array($db_regular_result,'id')));
	
	
echo '<h2>Max upload size and transform bytes</h2>';
echo 'Your max PHP upload size is '.BBeatBox::transformBytes(BBeatBox::getPhpMaxUploadSize());
echo '<h2>Loading time </h2>';
//the text is displayed by register_shutdown_function see line #2