<?php

require_once('./CrtHelper.php');

function displayCSRtable($csr, $sans, $showall = true) {

	$out = "<div class='table-responsive'>";
	$out = "<table class='table table-hover table-bordered'>";
	
	foreach($csr as $key => $value) {
		$row_valid = true;
		if (empty($value)) {
			$row_valid = false;
		}
		if ($key == "start" && $showall) {
			$title = "Startzeitpunkt";
		}
		elseif ($key == "end" && $showall) {
			$title = "Endzeitpunkt";
		}
		elseif ($key == "country") {
			$title = "L&auml;ndercode";
		}
		elseif ($key == "state") {
			$title = "Staat";
		}
		elseif ($key == "city") {
			$title = "Stadt";
		}
		elseif ($key == "organisation_name") {
			$title = "Organisation";
		}
		elseif ($key == "organisatio_unit_name") {
			$title = "Organisationseinheit";
		}
		elseif ($key == "common_name") {
			$title = "Common Name";
		}
		elseif ($key == "responsible_email") {
			$title = "Zust&auml;ndige E-Mail Adresse";
		}
		elseif ($key == "challenge_password") {
			$title = "Challenge Password";
		}
		elseif ($key == "optional_company_name") {
			$title = "Optionaler Firmenname";
		}
		elseif ($key == "intermediate") {
			$title = "Intermediate Zertifikat";
			if($value == "1") {
				$value = "Ja";
			} else {
				$value = "Nein";
			}
		}
		elseif ($key == "status" && $showall) {
			$title = "Status";
		}
		elseif ($key == "log" && $showall) {
			$title = "Administratorenkommentar";
		}
		else {
			$row_valid = false;
		}
		
		if($row_valid) {
			$out .= "<tr>";
			$out .= "<th>".$title."</th>";
			if($key == "status") {
				$out .= CrtHelper::getStatusColorfulTD($value);
			} else {
				$out .= "<td>".$value."</td>";
			}
			$out .= "</tr>";
			if($key == "common_name") {
				foreach($sans as $key => $value) {
					$out .= "<tr><th>SAN ".($key+1)."</th><td>".$value->name."</td></tr>";
				}
			}
		}
	}
	
	$out .= "</table>";
	$out .= "</div>";
	
	return $out;
}

?>