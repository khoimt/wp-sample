<?php

	ini_set('display_errors', 1);
	$fin =  fopen("sum.in", "r");
	$fou = fopen("sum.out", "w");
	
	fscanf($fin, "%d %d", $a, $b);
	fputs($fou, $a + $b . "\n");
	
	fclose($fin);
	fclose($fou);
	
	
