<?php
	defined('SYSPATH') or die('No direct script access.');
	foreach ($messages as $_msg) {
		$_res = '';
		foreach($_msg->message as $_sub_msg)
			$_res .= "<li>{$_sub_msg}</li>";
		echo "<ul class=\"{$_msg->type}\">{$_res}</ul>";
	}
?>
