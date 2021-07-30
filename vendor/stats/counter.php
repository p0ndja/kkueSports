<?php
	/*
	Text Counter by http://www.free-php-counter.com
	You are allowed to remove advertising after you purchased a licence
	*/

	// settings

	// ip-protection in seconds
	$counter_expire   = 600;
	$counter_filename = "../vendor/stats/counter.txt";

	// ignore agent list
	$counter_ignore_agents = array(
		'bot',
		'bot1',
		'bot3'
	);

	// ignore ip list
	$counter_ignore_ips = array();


	// get basic information
	$counter_agent = $_SERVER['HTTP_USER_AGENT'];
	$counter_ip   = getClientIP();
	$counter_time = time();


	if (file_exists($counter_filename)) {
		// check ignore lists
		$ignore = false;
		
		$length = sizeof($counter_ignore_agents);
		for ($i = 0; $i < $length; $i++) {
			if (substr_count($counter_agent, strtolower($counter_ignore_agents[$i]))) {
				$ignore = true;
				break;
			}
		}
		
		$length = sizeof($counter_ignore_ips);
		for ($i = 0; $i < $length; $i++) {
			if ($counter_ip == $counter_ignore_ips[$i]) {
				$ignore = true;
				break;
			}
		}
		
		
		
		// get current counter state
		$c_file = array();
		$fp     = fopen($counter_filename, "r");
		
		if ($fp) {
			//flock($fp, LOCK_EX);
			$canWrite = false;
			while (!$canWrite)
				$canWrite = flock($fp, LOCK_EX);
			
			while (!feof($fp)) {
				$line = trim(fgets($fp, 1024));
				if ($line != "")
					$c_file[] = $line;
			}
			flock($fp, LOCK_UN);
			fclose($fp);
		} else {
			$ignore = true;
		}
		
		
		// check for ip lock
		if ($ignore == false) {
			$continue_block = array();
			for ($i = 1; $i < sizeof($c_file); $i++) {
				$tmp = explode("||", $c_file[$i]);
				
				if (sizeof($tmp) == 2) {
					list($counter_ip_file, $counter_time_file) = $tmp;
					$counter_time_file = trim($counter_time_file);
					
					if ($counter_ip == $counter_ip_file && $counter_time - $counter_expire < $counter_time_file) {
						// do not count this user but keep ip
						$ignore = true;
						
						$continue_block[] = $counter_ip . "||" . $counter_time;
					} else if ($counter_time - $counter_expire < $counter_time_file) {
						$continue_block[] = $counter_ip_file . "||" . $counter_time_file;
					}
				}
			}
		}
		
		// count now
		if ($ignore == false) {
			// increase counter
			if (isset($c_file[0]))
				$tmp = explode("||", $c_file[0]);
			else
				$tmp = array();
			
			if (sizeof($tmp) == 8) {
				// prevent errors
				list($day_arr, $yesterday_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = $tmp;
				
				$day_data       = explode(":", $day_arr);
				$yesterday_data = explode(":", $yesterday_arr);
				
				// yesterday
				$yesterday = $yesterday_data[1];
				if ($day_data[0] == (date("z") - 1)) {
					$yesterday = $day_data[1];
				} else {
					if ($yesterday_data[0] != (date("z") - 1)) {
						$yesterday = 0;
					}
				}
				
				// day
				$day = $day_data[1];
				if ($day_data[0] == date("z"))
					$day++;
				else
					$day = 1;
				
				// week
				$week_data = explode(":", $week_arr);
				$week      = $week_data[1];
				if ($week_data[0] == date("W"))
					$week++;
				else
					$week = 1;
				
				// month
				$month_data = explode(":", $month_arr);
				$month      = $month_data[1];
				if ($month_data[0] == date("n"))
					$month++;
				else
					$month = 1;
				
				// year
				$year_data = explode(":", $year_arr);
				$year      = $year_data[1];
				if ($year_data[0] == date("Y"))
					$year++;
				else
					$year = 1;
				
				// all
				$all++;
				
				// neuer record?
				$record_time = trim($record_time);
				if ($day > $record) {
					$record      = $day;
					$record_time = $counter_time;
				}
				
				// speichern und aufräumen und anzahl der online leute bestimmten
				$online = 1;
				
				// write counter data (avoid resetting)
				if ($all > 1) {
					$fp = fopen($counter_filename, "w+");
					if ($fp) {
						//flock($fp, LOCK_EX);
						$canWrite = false;
						while (!$canWrite)
							$canWrite = flock($fp, LOCK_EX);
						
						$add_line1 = date("z") . ":" . $day . "||" . (date("z") - 1) . ":" . $yesterday . "||" . date("W") . ":" . $week . "||" . date("n") . ":" . $month . "||" . date("Y") . ":" . $year . "||" . $all . "||" . $record . "||" . $record_time . "\n";
						fwrite($fp, $add_line1);
						
						$length = sizeof($continue_block);
						for ($i = 0; $i < $length; $i++) {
							fwrite($fp, $continue_block[$i] . "\n");
							$online++;
						}
						
						fwrite($fp, $counter_ip . "||" . $counter_time . "\n");
						flock($fp, LOCK_UN);
						fclose($fp);
					}
				} else {
					$online = 1;
				}
			} else {
				// show data when error  (of course these values are wrong, but it prevents error messages and prevent a counter reset)
				
				// get counter values
				$yesterday   = 0;
				$day         = $week = $month = $year = $all = $record = 1;
				$record_time = $counter_time;
				$online      = 1;
			}
		} else {
			// get data for reading only
			if (sizeof($c_file) > 0)
				list($day_arr, $yesterday_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode("||", $c_file[0]);
			else
				list($day_arr, $yesterday_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode("||", date("z") . ":1||" . (date("z") - 1) . ":0||" . date("W") . ":1||" . date("n") . ":1||" . date("Y") . ":1||1||1||" . $counter_time);
			
			// day
			$day_data = explode(":", $day_arr);
			$day      = $day_data[1];
			
			// yesterday
			$yesterday_data = explode(":", $yesterday_arr);
			$yesterday      = $yesterday_data[1];
			
			// week
			$week_data = explode(":", $week_arr);
			$week      = $week_data[1];
			
			// month
			$month_data = explode(":", $month_arr);
			$month      = $month_data[1];
			
			// year
			$year_data = explode(":", $year_arr);
			$year      = $year_data[1];
			
			$record_time = trim($record_time);
			
			$online = sizeof($c_file) - 1;
			if ($online <= 0)
				$online = 1;
		}
	} else {
		// create counter file
		$add_line = date("z") . ":1||" . (date("z") - 1) . ":0||" . date("W") . ":1||" . date("n") . ":1||" . date("Y") . ":1||1||1||" . $counter_time . "\n" . $counter_ip . "||" . $counter_time . "\n";
		// write counter data
		$fp       = fopen($counter_filename, "w+");
		if ($fp) {
			//flock($fp, LOCK_EX);
			$canWrite = false;
			while (!$canWrite)
				$canWrite = flock($fp, LOCK_EX);
			fwrite($fp, $add_line);
			flock($fp, LOCK_UN);
			fclose($fp);
		}
		
		// get counter values
		$yesterday   = 0;
		$day         = $week = $month = $year = $all = $record = 1;
		$record_time = $counter_time;
		$online      = 1;
	}

	$webstatscounter = array(
		"online"=>$online,
		"today"=>$day,
		"yesterday"=>$yesterday,
		"week"=>$week,
		"month"=>$month,
		"year"=>$year,
		"total"=>$all,
		"record"=>$record
	);
?>
<div class="card mb-3">
	<div class="card-body">
		<div class="card-title">
			<div class="font-weight-bold count-up h1 text-center" data-from="0" data-to="<?php echo $webstatscounter['total']; ?>" data-time="1000">0
			</div>
		</div>
		<div class="card-text">
			<p>
				<hr>
				<div class="text-success"><i class="fas fa-user"></i> Today: <?php echo $webstatscounter['today']; ?></div>
				<div class="text-info"><i class="fas fa-user"></i> This week: <?php echo $webstatscounter['week']; ?></div>
				<div class="text-warning"><i class="fas fa-user"></i> This Month: <?php echo $webstatscounter['month']; ?></div>
				<div class="text-danger"><i class="fas fa-user"></i> This Year: <?php echo $webstatscounter['year']; ?></div>
				<div class="text-secondary"><i class="fas fa-users"></i> Total: <?php echo $webstatscounter['total']; ?></div>
			</p>
		</div>
	</div>
	<div class="card-footer text-center text-muted">
		IP ของคุณ: <?php echo $counter_ip; ?><br>
		<sup><small><a href="http://www.free-php-counter.com" target="_blank" class="md">Free PHP Counter</a></small></sup>
	</div>
</div>
<script>
	(function ($) {
		$.fn.counter = function () {
			const $this = $(this),
				numberFrom = parseInt($this.attr('data-from')),
				numberTo = parseInt($this.attr('data-to')),
				delta = numberTo - numberFrom,
				deltaPositive = delta > 0 ? 1 : 0,
				time = parseInt($this.attr('data-time')),
				changeTime = 10;

			let currentNumber = numberFrom,
				value = delta * changeTime / time;
			var interval1;
			const changeNumber = () => {
				currentNumber += value;
				//checks if currentNumber reached numberTo
				(deltaPositive && currentNumber >= numberTo) || (!deltaPositive && currentNumber <= numberTo) ?
				currentNumber = numberTo: currentNumber;
				this.text(parseInt(currentNumber));
				currentNumber == numberTo ? clearInterval(interval1) : currentNumber;
			}

			interval1 = setInterval(changeNumber, changeTime);
		}
	}(jQuery));

	$(document).ready(function () {

		$('.count-up').counter();
		$('.count1').counter();
		$('.count2').counter();
		$('.count3').counter();
		$('.count4').counter();

		new WOW().init();

		setTimeout(function () {
			$('.count5').counter();
		}, 3000);
	});
</script>