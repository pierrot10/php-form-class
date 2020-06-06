<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Formulaire OO</title>
<script type="text/javascript" src="jQuery/jquery-1.4.4.min.js"></script>
<script language="javascript">
	
	
		function deroulant_open(what){
	//$(document).ready(function(){
		//quant on clique sur le div blocaction
		//$("#id-tarif .bt_option").click(function(){
					
			//si le div deroulant est caché on le montre
			if($("#"+what).css("display")=="none")
			{
				$("#"+what).slideToggle();	
			}
			
		//});
		
		//quand on resort de déroulant
		/*
		$(".block_deroule").hover(function(){
			//rien
		},function(){
	
			//si le div déroulant est visible, on le cache
			
			if($(".block_deroule").css("display")=="block")
			{
				$(".block_deroule").slideToggle();
			}
		});
		*/
	//}); 
	}
	function deroulant_close(what){

		if($("#"+what).css("display")=="block")
		{
			$("#"+what).slideToggle();	
		}
	}

</script>
</head>

<body>
<h1>Formulaire OO</h1>
<?php
include('formulaire.class.php');


/* 

CONDITION POSSIBLE
	'isNotEmpty',
	'isEmail',
	'isString',
	'isStringOnly',
	'isDate',
	'isNumeric',
	'minDigit', 	// (minDigir:5) Doit avoir au moins 5 caracteres
	'maxDigit', 	// (maxDigit:5) Doit avoir max 5 caractères
	'nbDigit', 		// (digit:5) doit avoir 5 caractere
	'removeAccent',
	'strip_tags',	// strip_tags:[br],[hr],[p]
	'captcha

FORMATAGE
 $name = new Form('input','id',$labelName->title,'name','form','isNotEmpty,isEmail,minDigit:4,strip_tags:');		=> *Format, voir plus bas
 $name->type = 'text'; 																								=> (text,radio,submit,captcha,password,checkbox,hidden)
 $name->value = $name->Post($_POST);																				=> Vlaue	
 $name->Display();


* Format :
'inout'						: Type du champs (input, select)
'id' 							: Id (laisser id, car le nom du champs viendra se merger expl: id_name
'name'							: nom du champs
'form'							: class
'isNotEmpty,isEmail'			: validation (separé par une virgule)

*/
?>
<div id="error" style="display:none">
<fieldset><legend>Message d'erreur</legend>

<?php
if(count($_POST) > 0){
		print_r($_POST);
		$check_field = new Validation();
		
		foreach($_POST as $name => $value){
		/*------------------------------------------------------*/						
		/*						VALIDATION						*/
		/*------------------------------------------------------*/						
			//Affiche les erreurs de validation. (S'il y en a)
			echo '<ul>';
			$check_field->validate($name,$value);
			echo '</ul>';
		}
		
		/*------------------------------------------------------*/
		/*		Controle s'il n'y a pas de message d'erreur 	*/
		/*------------------------------------------------------*/
		if(in_array(0,$check_field->validated)){
			?>
            <script language="javascript">
            <!--
            //
          	 deroulant_open('error');
            //
            -->
			</script>
            <?php

		}elseif($_POST['pwd'] != $_POST['conf_pwd']){
			
			echo '<p>'.$langue->pwd_not_ident.'</p>';
			
			?>
            <script language="javascript">
            <!--
            //
            deroulant_open('error');
            //
            -->
			</script>
            <?php
		}else{
			echo 'ok';
		}
}
?>
<div id="fermer"><p><a href="#" onclick="javascript:deroulant_close('error');">Fermer</a></p></div>
</fieldset>

</div>
<form id="form1" name="form1" method="post" action="">
          <p>
            <?php
                //Nom
                $labelName = new Form('label','id','','name','class',1);
                $labelName->title = 'Name';
                $labelName->Display();
                
             ?>
          
            <?php
            
                $name = new Form('input','id',$labelName->title,'name','class','isNotEmpty,minDigit:4,strip_tags:');
                $name->type = 'text';
                $name->value = $name->Post($_POST);
                $name->Display();
            ?>
          </p>
          <p>
            <?php
                //FIRSTNAME
                $labelFirstname = new Form('label','id','','firstname','class',1);
                $labelFirstname->title = 'Firstname';
                $labelFirstname->Display();
                
             ?>
          
            <?php
            
                $firstname = new Form('input','id',$labelFirstname->title,'firstname','form','minDigit:4,strip_tags:');
                $firstname->type = 'text';
                $firstname->value = $firstname->Post($_POST);
                $firstname->Display();
            ?>
          </p>
          <p>
            <?php
                //EMAIL
                $labelEmail = new Form('label','id','','email','class',1);
                $labelEmail->title = 'email';
                $labelEmail->Display();
                
             ?>
          
            <?php
            
                $email = new Form('input','id',$labelEmail->title,'email','form','isNotEmpty,isEmail,minDigit:4,strip_tags:');
                $email->type = 'text';
                $email->value = $email->Post($_POST);
                $email->Display();
            ?>
          </p>
          <p>
          <?php
                $labelCaptcha = new Form('label','id','','captcha','class',1);
                $labelCaptcha->title = 'Captcha'; 
                $labelCaptcha->Display();
           ?>
            <?php
                $captcha = new Form('input','id',$labelCaptcha->title,'captcha','','captcha');
                $captcha->type = 'captcha';
                $captcha->style = "width:150px";
                $captcha->Display();
            ?>
           </p>
           <p>
				Vous devez encore confirmer avoir pris connaissance des <a href="">conditions générales</a> en cochant la case ci-dessous.
           </p>
           <p>
            <?php
				$cg0 = new Form('input','id','J\'ai lu les conditions générales','cg','','isNotEmpty');
                $cg0->type = 'hidden';
				$cg0->value = 0;
                $cg0->Display();

				$cg = new Form('input','id','J\'ai lu les conditions générales','cg','','isNotEmpty');
                $cg->type = 'checkbox';
				$cg->value = 1;
                $cg->Display();
				
				echo "J'ai lu les conditions générales";
            ?>
           </p>
           <p>
            <?php
            // BOUTON SEND
            /***************/
                $submit = new Form('input','id','submit','submit','form','');
                $submit->type = 'submit';
                $submit->value= 'Envoyer';
                $submit->Display();
            ?>
                </p>
 </form>

</body>
</html>
