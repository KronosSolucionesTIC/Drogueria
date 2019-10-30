<? include("lib/database.php");?>
<?
setcookie("nombre",$global[3]);
setcookie("global[2]", $global[2], time() + 86400); 
setcookie("global[3]", $global[3], time() + 86400); 
setcookie("global[4]", $global[4], time() + 86400); 
?>

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>::::: SISTEMA POS ::::</title>
<script type="text/javascript">var tWorkPath="menus/data.files/";</script>
<script type="text/javascript" src="menus/data.files/dtree.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">

<style type="text/css">
/* Inset 3D Curved */
.inset {background: transparent; width:98%; height:95%;  }
.inset h1, .inset p {margin:0 10px;}
.inset h1 {font-size:2em; color:#fff; letter-spacing:1px;}
.inset p {padding-bottom:0.5em;}

.inset .top, .inset .bottom {display:block; background:transparent; font-size:1px;}
.inset .b1, .inset .b2, .inset .b3, .inset .b4, .inset .b1b, .inset .b2b, .inset .b3b, .inset .b4b {display:block; overflow:hidden;}
.inset .b1, .inset .b2, .inset .b3, .inset .b1b, .inset .b2b, .inset .b3b {height:1px;}
.inset .b2 {background:#ccc; border-left:1px solid #999; border-right:1px solid #aaa;}
.inset .b3 {background:#ccc; border-left:1px solid #999; border-right:1px solid #ddd;}
.inset .b4 {background:#ccc; border-left:1px solid #999; border-right:1px solid #eee;}


.inset .b4b {background:#ccc; border-left:1px solid #aaa; border-right:1px solid #fff;}
.inset .b3b {background:#ccc; border-left:1px solid #ddd; border-right:1px solid #fff;}
.inset .b2b {background:#ccc; border-left:1px solid #eee; border-right:1px solid #fff;}


.inset .b1 {margin:0 5px; background:#999;}
.inset .b2, .inset .b2b {margin:0 1px; border-width:0 1px;}
.inset .b3, .inset .b3b {margin:0 2px;}
.inset .b4, .inset .b4b {height:1px; margin:0 1px;}
.inset .b1b {margin:0 5px; background:#fff;}

.inset .boxcontent {display:block; background:#ccc; border-left:1px solid #999; border-right:1px solid #fff;}


.Estilo1 {color: #0000CC}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script language="javascript">
function cerrar() {
top.window.close()
}
</script>
<table width="194"   height="210" border="0" >
      <tr valign="top">
        <td>
		<table width="189" height="39" border="0">

  
  <tr>

  <td align="center">&nbsp;</td>
  </tr>
</table>		</td>
      </tr>
      <tr valign="top">
        <td  align="left"><? include("menus/data.php") ?>
		
	    </td>
      </tr>
	   <tr>
        <td><a href="index.html" ></a></td>
      </tr>
</table>
