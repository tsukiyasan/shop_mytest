<?

include( '../../config.php' ); 

$idStr = global_get_param( $_REQUEST, 'idStr', null ,0,0  );
$v = global_get_param( $_REQUEST, 'v', null ,0,1  );

if(!empty($idStr) && ( $v == '4' || $v == '5'))
{
	$arr = explode("||",$idStr);
	$id_arr = array();
	foreach($arr as $row)
	{
		if(!empty($row))
		{
			$id_arr[] = intval($row);
		}
	}
	$cnt = count($id_arr);
	
	
	$sql = " SELECT * FROM siteinfo";
	$db->setQuery( $sql );
	$siteinfo =$db->loadRow();
	
	
	$sql = " SELECT A.id, A.orderNum, A.dlvrName, A.dlvrCity, A.dlvrCanton, A.dlvrZip, A.dlvrAddr, A.dlvrMobile, A.dlvrFee, A.totalAmt, B.*,C.name as proName, C.proCode FROM orders A, orderdtl B , products C WHERE A.id = B.oid AND B.pid = C.id AND A.id IN ('".implode("','",$id_arr)."') ORDER BY C.odring2 , C.name";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$order_arr = array();
	foreach($r as $row)
	{
		$info = array();
		
		$info['orderNum'] = $row['orderNum'];
		$info['dlvrName'] = $row['dlvrName'];
		$info['dlvrCity'] = getFieldValue("select name from addrcode where id='{$row['dlvrCity']}'","name");
		$info['dlvrCanton'] = getFieldValue("select name from addrcode where id='{$row['dlvrCanton']}'","name");
		$info['dlvrZip'] = $row['dlvrZip'];
		$info['dlvrAddr'] = $row['dlvrAddr'];
		$info['dlvrMobile'] = $row['dlvrMobile'];
		$info['dlvrFee'] = $row['dlvrFee'];
		$info['totalAmt'] = $row['totalAmt'];
		
		$info['oid'] = $row['oid'];
		$info['proCode'] = $row['proCode'];
		$info['proName'] = $row['proName'];
		$info['quantity'] = $row['quantity'];
		$info['unitAmt'] = $row['unitAmt'];
		$order_arr[$row['oid']][] = $info;
	}
	
	
	
	
	$html_str = "
		<div class=\"\">
			<table align=\"center\" width=\"750\" class=\"\">
				<tr>
					<td align=\"center\">總共{$cnt}筆數，此頁顯示{$cnt}筆
						<input type=\"button\" onClick=\"print();\" value=\"列印\"></br>
						<font color=\"#FF0000\">※第一頁不需列印※</font>
						<hr color=\"#000000\">
					</td>
				</tr>
			</table>
		</div>
		<p style=\"page-break-after:always\"></p>";
		
	
	if($v == '4')
	{
		$html_top = "
			<div align=\"center\"> 
				<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"> 
					<tr>  
						<td align=\"left\" valign=\"top\">
							<center>".$siteinfo['name']."</center>
						</td>
					</tr>
					<tr>  
						<td align=\"left\" valign=\"top\">
							<center>揀貨單</center>
						</td>
					</tr>
					<tr>  
						<td align=\"left\" valign=\"top\"> </td>
					</tr>
					<tr>  
						<td align=\"left\" valign=\"top\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">
								<tr>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">序號</th>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">廠商編號</th>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">商品編號</th>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">商品名稱</th>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">規格</th>
									<th align=\"left\" style=\"border-bottom: 2px solid #000; \">配貨數量</th>
								</tr>";
								
		$html_end = "
							</table>
						</td>
					</tr>
				</table>
			</div>
			<p style=\"page-break-after:always\"></p>";
			
		foreach($order_arr as $row)
		{
			$html_str .= $html_top;
			$sum = 0;
			foreach($row as $k=>$r)
			{
				$html_str .= "	<tr>
									<td style=\"border-bottom: 2px dashed #000; \">".((int)$k+1)."</td>
									<td style=\"border-bottom: 2px dashed #000; \"></td>
									<td style=\"border-bottom: 2px dashed #000; \">".$r['proCode']."</td>
									<td style=\"border-bottom: 2px dashed #000; \">".$r['proName']."</td>
									<td style=\"border-bottom: 2px dashed #000; \"></td>
									<td style=\"border-bottom: 2px dashed #000; \">".$r['quantity']."</td>
								</tr>";
				$sum += (int)$r['quantity'];
			}
			
			$html_str .= "
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>合計: {$sum}</td>
								</tr>";
			$html_str .= $html_end;	
		}
	}
	else
	{

		foreach($order_arr as $row)
		{
			$html_str .="
			<div align=\"center\"> 
				<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> 
					<tr>  
						<td>
							<div style=\"border: 2px solid #000;\"> 
								<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"> 
									<tr>
										<td align=\"left\" valign=\"top\" colspan=\"2\">
											".$siteinfo['addr']."<br />
											".$siteinfo['name']."<br />
											".$siteinfo['tel']."
										</td>
										<td width=\"34%\"></td>
									</tr>
									<tr>
										<td width=\"33%\"></td>
										<td align=\"left\" valign=\"top\" colspan=\"2\">
											<span style=\"font-size:1.5em;\">
											{$row[0]['dlvrZip']}{$row[0]['dlvrCity']}{$row[0]['dlvrCanton']}{$row[0]['dlvrAddr']}<br />
											{$row[0]['dlvrName']} 收<br />
											{$row[0]['dlvrMobile']}
											</span>
										</td>
									</tr>
									<tr>
										<td width=\"66%\"  colspan=\"2\"></td>
										<td align=\"left\" valign=\"top\" colspan=\"2\">
											訂單編號：{$row[0]['orderNum']}
										</td>
									</tr>
									<tr>
										<td colspan=\"3\">&nbsp;</td>
									</tr>
								</table>
							</div> 
						</td>
					</tr>
					<tr>
						<td>
							<div style=\"border: 2px solid #000;\" align=\"center\"> 
								<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"> 
									<tr>
										<th><span style=\"font-size:1.5em;\">".$siteinfo['name']."</span></th>
									</tr>
									<tr>
										<th style=\"border-bottom: 3px solid #000; \"><span style=\"font-size:1.5em;\">出貨單</span></th>
									</tr>
									<tr>
										<td style=\"border-bottom: 3px solid #000; \">訂單編號：{$row[0]['orderNum']}</td>
									</tr>
									<tr>
										<td>
											<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">
												<tr>
													<th align=\"left\">商品編號</th>
													<th align=\"left\">產品名稱</th>
													<th align=\"left\">規格</th>
													<th align=\"left\">數量</th>
													<th align=\"left\">單筆金額</th>
												</tr>";
											
			$sum = 0;
			foreach($row as $r)
			{
				$html_str .="
												<tr>
													<td align=\"left\"></td>
													<td align=\"left\">{$r['proName']}</td>
													<td align=\"left\"></td>
													<td align=\"left\">{$r['quantity']}</td>
													<td align=\"left\">{$r['unitAmt']}</td>
												</tr>
				";
				
				$sum += (int)$r['quantity'];
			}
			
			$html_str .="
												<tr>
													<td align=\"left\"></td>
													<td align=\"left\">運費</td>
													<td align=\"left\"></td>
													<td align=\"left\"></td>
													<td align=\"left\">{$row[0]['dlvrFee']}</td>
												</tr>
												<tr>
													<td align=\"left\" style=\"border-bottom: 3px solid #000; \">&nbsp;</td>
													<td align=\"left\" style=\"border-bottom: 3px solid #000; \">&nbsp;</td>
													<td align=\"left\" style=\"border-bottom: 3px solid #000; \">&nbsp;</td>
													<td align=\"left\" style=\"border-bottom: 3px solid #000; \">&nbsp;</td>
													<td align=\"left\" style=\"border-bottom: 3px solid #000; \">&nbsp;</td>
												</tr>
												<tr>
													<td align=\"left\">備註</td>
													<td align=\"left\"></td>
													<td align=\"right\">數量:</td>
													<td align=\"left\">{$sum}</td>
													<td align=\"left\">總計:{$row[0]['totalAmt']}</td>
												</tr>
												<tr>
													<td align=\"left\">&nbsp;</td>
													<td align=\"left\">&nbsp;</td>
													<td align=\"left\">&nbsp;</td>
													<td align=\"left\">&nbsp;</td>
													<td align=\"left\">&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
						
					</tr>
				</table>
			</div>
			<p style=\"page-break-after:always\"></p>
			";
			
		}
	}
	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="robots" content="none" />
		<title>後台管理系統</title>
		<!-- Favicon -->
		<link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />
	</head>
	
	<body id="site_bg">
		<?=$html_str?>
	</body>

	<script src="lib/jquery-2.2.2/jquery-2.2.2.min.js"></script>
	
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->

</html>
<?

include( $conf_php.'common_end.php' ); 
?>