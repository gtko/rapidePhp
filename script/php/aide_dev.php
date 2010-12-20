<?php

function aide_var($var_recup_rapide_php){
	
	if(AIDE_VAR == 'true')
	{
		$tree = new OutilsDev;
		$tree->var_all_variable = $var_recup_rapide_php;
		$tree->aff_variable();
		return $tree->var_sortie;
	}	
}

function aide_var_objet($modules){

	//on affiche les variable de l'objet
	if(AIDE_VAR_OBJET == 'true')
	{
		$tree_ob = new OutilsDev;
		$tree_ob ->var_all_variable = $modules;
		$tree_ob ->aff_variable_objet();
		return $tree_ob->var_sortie;
	}
}
	function getmtime($temps)
	{
    
    $temps = explode(' ', $temps);
    return $temps[1] + $temps[0];
 
	}
	
	function aide_time_charge($depart){
	//on affiche les variable de l'objet
	
		$fin = microtime();
		$fin = getmtime($fin);
		$depart = getmtime($depart);
		
	   //je prend la variable de depart
	   $microtime_final = $fin - $depart;
	   
		return "<div   style=\"position:fixed;z-index:99;left:10px; bottom:20px;color:white;font-weight:bold;text-decoration:underline;background-image:url('".REP_IMG_PHP."fond-transparent.png');\" > rapide php  a mit ".round($microtime_final,6)." s<br/> pour calculer la page</div>";

}

?>