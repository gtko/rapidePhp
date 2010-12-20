<?php

function date_conver()
	
	{

	/*ereg('^([0-9]{4})-([0-9]{2})-([0-9]{2})$', $date, $regs);
	print_r	($date."=".$regs[1]."=".$regs[2]."=".$regs[3]);
	
	$date = date_create(date("$regs[1]-$regs[2]-$regs[3] 00:00:00",0));
	#$date=date_format($date,'d F Y ' ) ;*/
	
	switch (date('w'))
		{
		case 0:
			$jour="Dimanche";
			break;
		case 1:
			$jour="Lundi";
			break;
		case 2:
			$jour="Mardi";
			break;
		case 3:
			$jour="Mercredi";
			break;
		case 4:
			$jour="Jeudi";
			break;
		case 5:
			$jour="Vendredi";
			break;
		case 6:
			$jour="Samedi";
			break;
		default:
							print_r(" passe default");	
		}
	switch (date('n'))
		{
		case 1:
			$mois="Janvier";
			break;
		case 2:
			$mois="Février";
			break;
		case 3:
			$mois="Mars";
			break;
		case 4:
			$mois="Avril";
			break;
		case 5:
			$mois="Mai";
			break;
		case 6:
			$mois="Juin";
			break;
		case 7:
			$mois="Juillet";
			break;
		case 8:
			$mois="Août";
			break;
		case 9:
			$mois="Septembre";
			break;
		case 10:
			$mois="Octobre";
			break;
		case 11:
			$mois="Novembre";
			break;
		case 12:
			$mois="Décembre";
			break;	
				
		default:
							print_r(" passe default");	
		}
	
	
	$date=$jour." " .date('d ' ). $mois." ".date('Y ' ) ;
	return($date);	
	}
?>