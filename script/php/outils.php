<?php


 function aff_tableau($array,$niveau = NULL){
	
	if($niveau == NULL)
	{
		$niveau = 0;
	}
	$debut = 0;
	while($debut != $niveau)
	{
		$indentation .= "\t";
		$debut ++;
	}
	
	$calcule_indentation = 1 * $niveau;
	foreach($array as $k => $v)
	{
		if(is_array($v))
		{
			$niveau ++;
			aff_tableau($v,$niveau);		
		}
		else
		{
			
			$sorti .= $indentation.$k." = $v \n";		
		}
		
	}
	
	return $sorti;
 }




?>
