<?php

	ini_set('display_errors', 1);
	$fin =  fopen("sum.in", "r");
	$fou = fopen("sum.out", "w");
	
	fscanf($fin, "%d %d", $a, $b);
	if ($a > 1000) {
		fputs($fou, $a + $b + 100);
	} else {
		fputs($fou, $a + $b . "\n");
	}
	
	fclose($fin);
	fclose($fou);
	
	
