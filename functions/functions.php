<?php
	// copyright function
	function copyright($startYear) {
		$currentYear = date('Y');
			if ($startYear < $currentYear) {
				$currentYear = date('y');
				return "<div class='footer-copyright text-center py-3'>&copy; $startYear&ndash;$currentYear The Network</div>";
			} else {
				return "<div class='footer-copyright text-center py-3'>&copy; $startYear The Network</div>";
			}
	}
?>