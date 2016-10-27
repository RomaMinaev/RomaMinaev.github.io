<?php

 include_once '../models/CategoriesModel.php';
 include_once '../models/ProductsModel.php';
 
 function addtocartAction(){
     
     $itemId= isset($_GET['id'])? intval($_GET['id']):null;
     if (!$itemId)         return false;
     $PriseItem=isset($_GET['prise'])? intval($_GET['prise']):null;
     $resData = array();
     
     array_push($_SESSION['cart'], $itemId);
     $_SESSION['priseCart']=$_SESSION['priseCart']+$PriseItem;
     echo "<script>history.go(-1)</script>";
  
       }
       
       
 function removefromcartAction(){
     $itemId= isset($_GET['id'])? intval($_GET['id']):null;
     if (!$itemId)         return false;
     $key=  array_search($itemId, $_SESSION['cart']);
     unset($_SESSION['cart'][$key]);
    
      $PriseItem=isset($_GET['prise'])? intval($_GET['prise']):null;
       $_SESSION['priseCart']=$_SESSION['priseCart']-$PriseItem;
     echo "<script>history.go(-1)</script>";
 }
 
 function indexAction($smarty){
     $itemsIds=$_SESSION['cart'];
     $rsCategories = getAllMainCatsWithChilder();
     $rsProducts = getProductsFromArray($itemsIds);
     $smarty->assign('pageTitle','Корзина'); 
     $smarty->assign('rsCategories',$rsCategories);
     $smarty->assign('rsProducts',$rsProducts);
     
     loadTemplate($smarty, 'header');
     loadTemplate($smarty, 'cart');
     loadTemplate($smarty, 'footer');
 }
 

 function mailAction (){
    $to = "fi3al0ka@list.ru";
    $subject = "Robot - Робот";
   
   
     $strIds= implode($_SESSION['cart'],', ');
    $message = "Message, сообщение!".$strIds." ";
  mail ($to, $subject, $message);
     
 }
 
  function inputAction($smarty){
     
      
      
     $rsCategories = getAllMainCatsWithChilder();
   
     $smarty->assign('pageTitle','Корзина'); 
     $smarty->assign('rsCategories',$rsCategories);
   
     loadTemplate($smarty, 'header');
     
     
     $x=1;
$Check =  $_POST['Check']; 
$Products = " ";
$Total=0;
while ($x<=$Check){
  $Name =  $_POST['Name_'.$x]; 
  $Prise =  "Цена: ".$_POST['Prise_'.$x];
  $Color ="Цвет: ".$_POST['Color_'.$x];
  $Count = "Количество: ".$_POST['Count_'.$x];
  $Products = $Products.$Name."    ".$Prise."    ".$Color."   ".$Count." "."<br>"; 
    
    $Total=$Total+(int)$_POST['Prise_'.$x]*(int)$_POST['Count_'.$x];
    $x=$x+1;
    
};

$fio=$_POST['fio'];
$email=$_POST['email'];
$metro=$_POST['metro'];
$phone=$_POST['phone'];
$adress=$_POST['adress'];
$other=$_POST['other'];
mysql_query ("INSERT INTO orders (Products,Total,fio,email,metro,adress,other,phone) "
        .     "VALUES ('$Products','$Total','$fio','$email','$metro','$adress','$other','$phone')");
 $sql="SELECT * FROM orders ORDER BY id DESC LIMIT 1";
          $rs= mysql_query($sql);
     $row = mysql_fetch_array($rs);
echo "<div class='grid_9 omega contentHead' >

			<div class='cartPage' id='OrderPage'>

				<h1>Заказ Добавлен</h1><br>
                                <p>Ожидайте подтверждения заказа от сотрудника магазина.<br>
                                Номер заказа: 311".$row['id']."
</div></div>
";
     loadTemplate($smarty, 'footer');
   
     
     
	   
     
	  
     
     
     mail("fi3al0ka@yandex.ru", "Новый заказ N 331".$row['id'], "Поступил новый заказ проверте http://shopwhitecat.ru/?controller=orders");
     mail($email, "Заказ N".$row['id'], "shopwhitecat.ru Заказ N 331"+$row['id']." успешно оформлен <br/> Ожидайте звонка оператора для подтверждения заказа");
      session_destroy();
 }