<?php

	$scQ="select have_intro_no,use_e_cash from comp_data";
	$scR=$db->query($scQ);
	if($scR->size()>0){
		$scRow=$scR->fetch();
		//$have_intro預設Yes1 ，yesPlacement ； 0YesNoPlacement
		$have_intro=$scRow['have_intro_no'];
	}

    $arTabs=array();
	$arTabs['Network Info']="javascript:show_form('BLOCK_orgseq_info')";
	//$arTabs['Retraction Network']="javascript:show_form('BLOCK_orgseq')";
	$arTabs['Retraction Network']="javascript:show_form('BLOCK_orgseq5')";
	if($have_intro==1){
	$arTabs['Upright placement chart']="javascript:show_form('BLOCK_orgseq2')";
	}
	$arTabs['Upright sponsor chart']="javascript:show_form('BLOCK_orgseq3')";
	parse_tabs($arTabs);
	$main_block='BLOCK_orgseq3';
?>