<?php

class Form{
	protected $balise;
	protected $id;
	protected $name;
	protected $class;
	protected $mandatory;
	protected $fieldLabel;
	
	protected $toValidate;
	
	
	//LABEL
	protected $title;


	//Field
	protected $type;
	protected $accept; 
	protected $accesskey;
	protected $align;
	protected $alt;
	protected $border;
	protected $checked;
	protected $dir;
	protected $disabled;
	protected $height;
	protected $hspace;
	protected $lang;
	protected $maxlength;
	protected $onblur;
	protected $onchange;
	protected $onclick;
	protected $ondblclick;
	protected $onfocus;
	protected $onkeydown;
	protected $onkeypress;
	protected $onkeyup;
	protected $onmousedown;
	protected $onmousemove;
	protected $onmouseout;
	protected $onmouseover;
	protected $onmouseup;
	protected $onselect;
	protected $readonly;
	protected $size;
	protected $src;
	protected $style;
	protected $tabindex;
	protected $usemap;
	protected $width;
	//protected $title;
	
	
	//TEXTAREA
	protected $cols;
	protected $rows;
	protected $wrap;
	protected $value;
	//protected $accesskey;
	//protected $dir;
	//protected $disabled;
	//protected $lang;
	//protected $onblur;
	//protected $onchange;
	//protected $onclick;
	//protected $ondblclick;
	//protected $onfocus;
	//protected $onkeydown;
	//protected $onkeypress;
	//protected $onkeyup;
	//protected $onmousedown;
	//protected $onmousemove;
	//protected $onmouseout;
	//protected $onmouseover;
	//protected $onmouseup;
	//protected $onselect;
	//protected $readonly;
	//protected $style;
	//protected $tabindex;
	//protected $title;
	
	
	//SELECT
	//Attribut for a select
	protected $optionToASelect;
	//protected $accesskey;
	//protected $dir;
	//protected $disabled;
	//protected $lang;
	protected $multiple;
	//protected $onblur;
	//protected $onchange;
	//protected $onfocus;
	//protected $size;
	//protected $style;
	//protected $tabindex;
	//protected $title; 
	
	//Attribut for the Option
	#protected $option;
	protected $attributToAnOption;
	//protected $class;
	//protected $dir;
	//protected $disabled;
	//protected $id;
	protected $label; 		#is not in select
	//protected $lang; 		#is not in select
	//protected $onblur;
	//protected $onclick; 	#is not in select
	//protected $ondblclick; 	#is not in select
	//protected $onfocus;
	//protected $onkeydown; 	#is not in select
	//protected $onkeypress; 	#is not in select 
	//protected $onkeyup;		#is not in select
	//protected $onmousedown;	#is not in select
	//protected $onmousemove;	#is not in select
	//protected $onmouseout;	#is not in select
	//protected $onmouseover; #is not in select
	//protected $onmouseup;	#is not in select
	protected $selected;	#is not in select
	//protected $style;
	//protected $title;
	//protected $value;		#is not in select
	
	
	function __construct($balise,$id,$fieldLabel,$name,$class,$string_condition){
		$this->balise = $balise;
		$this->id = $id.'_'.$name;
		$this->name = $name;
		$this->class = $class;
		$this->fieldLabel = $fieldLabel;

		if(is_int($string_condition)){
			$this->mandatory = $string_condition;
		}elseif(empty($string_condition)){
			$_SESSION[$name]['fieldLabel'] = $this->fieldLabel;
		}else{
		
		// Liste des conditions possibles
			$validCondition = array(
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
				'captcha'
			);
		
			
			//Place les conditions sous forme d'array
			$array_condition = explode(',',$string_condition);
	
			// Parcours le tableau et liste les condition

			//$this->toValidates[$name]=array();
			foreach ($array_condition as $key => $condition){
				
				//Créée un nouvelle array afin de départager les conditions qui ont une valeur. Expl: minDigit:8
				$_condition = explode(":",$condition);
				
				if(!in_array($_condition[0],$validCondition)){
					echo $name.': ['.$_condition[0].'] is not valid. ';
				}else{	
					$_condition0 = $_condition[0];
					if(isset($_condition[1])) $_condition1 = ':'.$_condition[1];
						else $_condition1= "";
	
					$this->toValidate['conditions'][]=$_condition0.$_condition1;
				}
				
			}
			$_SESSION[$this->name] = $this->toValidate;
			$_SESSION[$this->name]['fieldLabel'] = $this->fieldLabel;
			
			#print_r($_SESSION[$this->name]);
			#echo '<hr>';
		}
	}
	
	
	public function __set($name, $value){
		if(property_exists(__CLASS__, $name)) $this->$name = $value;		
        	else echo '<p>Code error : <b><em>"'.$name.'"</em></b> is not an existing property in the class : '.__CLASS__.'</p>';
	}
	
	
	public function __get($name){
		return $this->$name;
	}
	
	function Post($post){
		if(isset($post[$this->name])) return $post[$this->name];
			else return "";
	}
	
	function Label(){
		echo '<label class="'.$this->class.'" id="label_'.$this->id.'" for="'.$this->name.'">'.$this->title.' </label>';
		
		//Si champs obligatoire
		if($this->mandatory) echo ' <span class="mandatory" style="color:#ff0000">*</span>';
	}
	
	function Input(){
		// Liste des types de champs autorisés
		$typeList = array('text','radio','submit','captcha','password','checkbox','hidden');
		$attributList =array(
			'accept','accesskey','align','alt','border','checked','dir',
			'disabled','height','hspace','lang',
			'maxlength','onblur','onchange','onclick',
			'ondblclick','onfocus','onkeydown','onkeypress',
			'onkeyup','onmousedown','onmousemove','onmouseout',
			'onmouseover','onmouseup','onselect','readonly',
			'size','src','style','tabindex','usemap', 'value', 'width','title'
		);
		
		//Controle si le type est autorisé
		if(!in_array($this->type, $typeList)){
			echo 'ERROR : That field type is not allow.'; //See lign 77	
		}else{
			
			if($this->type == "radio") $this->id .= '_'.$this->value;
			
			if($this->type == "captcha"){
				echo '<img style="margin-top:2px;" src="./math_captcha/image.php" alt="'.$this->name.'" title="'.$this->name.'" id="captcha_im" /><br />';
				$this->type = "texte";
			}
			
			echo '<input type="'.$this->type.'" id="'.$this->id.'" name="'.$this->name.'" class="'.$this->class.'"';
			
			//Affiche les attributs choisis et s'ils sont listés dans $attributList, et ne sont pas vides
			foreach($this as $attribut => $val){
				if(in_array($attribut,$attributList) AND !empty($val)){
					echo $attribut = $attribut.'="'.$val.'" ';
				}
			}			

			echo ' />';
		
		}	
	}
	
	function TextArea(){
		$attributList =array(
 			'accesskey',
			 'cols',
			 'dir',
			 'disabled',
			 'lang',
			 'onblur',
			 'onchange',
			 'onclick',
			 'ondblclick',
			 'onfocus',
			 'onkeydown',
			 'onkeypress',
			 'onkeyup',
			 'onmousedown',
			 'onmousemove',
			 'onmouseout',
			 'onmouseover',
			 'onmouseup',
			 'onselect',
			 'readonly',
			 'rows',
			 'style',
			 'tabindex',
			 'title',
			 'wrap'
		);
		
		echo '<textarea id="'.$this->id.'" name="'.$this->name.'" class="'.$this->class.'"';
			
			//Affiche les attributs choisis et s'ils sont listés dans $attributList, et ne sont pas vides
			foreach($this as $attribut => $val){
				if(in_array($attribut,$attributList) AND !empty($val)){
					echo $attribut = $attribut.'="'.$val.'" ';
				}
			}			

			echo '>'.$this->value.'</textarea>';
			
		
		
	}
	
	
	function Select(){
		$selectAttributList = array(
			'accesskey',
			'dir',
			'disabled',
			'lang',
			'multiple',
			'onblur',
			'onchange',
			'onfocus',
			'size',
			'style',
			'tabindex',
			'title'
		);
		
		echo '<select id="'.$this->id.'" name="'.$this->name.'" class="'.$this->class.'"';
			
			foreach($this as $attribut => $val){
				if(in_array($attribut,$selectAttributList) AND !empty($val)){
					echo $attribut = $attribut.'="'.$val.'" ';
				}
			}
		echo '>';
		
		
		
		foreach($this->optionToASelect as $value => $name){
				echo '<option ';
				
				//Si des attributs pour option ont été définis, si non ne fais pas
				if(is_array($this->attributToAnOption)){
					foreach($this->attributToAnOption as $key_att => $value_att){
						if($key_att==$value){
							#echo $attribut[0].'="'.$attribut[1].'"';
							foreach($value_att as $attribut_name => $attribut_value){
								echo $attribut_name.'="'.$attribut_value.'" ';
							}
						}
					}
				}
				
				echo ' value="'.$value.'">'.$name.'</option>';

		}
		
		
		echo '</select>';
	}

	
	function SetOptionToASelect($value,$name){
		$this->optionToASelect[$value]=$name;
	}

	
	function SetAttributToAnOption($option,$attribut,$value){
		
		$optionAttributList = array(
			'class',
			'dir',
			'disabled',
			'id',
			'label',		
			'lang',
			'onblur',
			'onclick',
			'ondblclick',
			'onfocus',
			'onkeydown',
			'onkeypress', 
			'onkeyup',
			'onmousedown',
			'onmousemove',
			'onmouseout',
			'onmouseover',
			'onmouseup',
			'selected',
			'style',
			'title'
		);
		
		if(in_array($attribut,$optionAttributList)){
			$this->attributToAnOption[$option][$attribut]=$value;
			#print_r($this->attributToAnOption);
		}
	}
	
	function Display(){
		
		switch(strtolower($this->balise)){
			case 'label':
				$this->Label();
				break;
				
			case 'input':
				$this->Input();
				break;
				
			case 'textarea':
				$this->TextArea();
				break;
			
			case 'select':
				$this->Select();
				break;

			default:
				echo "Ce champs n'existe pas";
		}
	}
	
}


class Validation{
	protected $validated = array();
	
	public function __get($name){
		return $this->$name;
	}
	
	public function validate($name,$value){
	
		echo '<pre>';
		print_r($_SESSION[$name]);
		echo '</pre><hr>';
		if (array_key_exists('conditions',$_SESSION[$name])){
			foreach($_SESSION[$name]['conditions'] as $key => $condition_value){
				#echo '('.$value.')';
				$_condition = explode(":",$condition_value);
				$condition0 = $_condition[0];
				if(isset($_condition[1])) $condition1 = $_condition[1];
					else $condition1= "";

				switch($condition0){
					
					case 'isNotEmpty':
						if(!Algorithms::isNotEmpty($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> est obligatoire.</li>"; 
							$this->validated[]=0;
						}else{ 
							$this->validated[]=1;
						}
						break;
					
					case 'isEmail':
						if(!Algorithms::isEmail($value)){ 
							echo "<li>Votre e-mail n'est pas valide.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;

					case 'isDomain':
						if(!Algorithms::isDomain($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> contient un domaine invalide.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
					
					case 'isNumeric':
						if(!Algorithms::isNumeric($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> doit &ecirc;tre de type Num&eacute;rique.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
					
					
					case 'isString':
						if(!Algorithms::isString($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> doit contenir des lettres.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;	
						
					case 'isDate':
						if(!Algorithms::isDate($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> doit &ecirc;tre une date.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
					
						
					case 'isStringOnly':
						if(!Algorithms::isStringOnly($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> ne doit pas contenir de chiffre(s).</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;	
						
					case 'minDigit':
						if(!Algorithms::minDigit($value,$condition1)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> doit avoir au minimum ".$condition1." catact&egrave;re(s).</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
					
					case 'maxDigit':
						if(!Algorithms::maxDigit($value,$condition1)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> ne doit pas avoir plus de ".$condition1." catact&egrave;re(s).</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
						
					case 'nbDigit':
						if(!Algorithms::nbDigit($value,$condition1)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> doit avoir exactement ".$condition1." caract&egrave;re(s).</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
						
					case 'removeAccent':
						$_POST[$name] = Algorithms::RemoveAccent($value);
						
						break;
						
					case 'strip_tags':
						$_POST[$name] = Algorithms::Strip_tags($value,$condition1);
						break;
					
					case 'captcha':
						if(!Algorithms::Captcha($value)){
							echo "<li>Le champs <em>".$_SESSION[$name]['fieldLabel']."</em> n'a pas le bon r&eacute;sultat.</li>";
							$this->validated[]=0;
						}else{
							$this->validated[]=1;
						}
						break;
					default:
						echo "<li>La condition [<em>".$condition0."</em>] n'existe pas dans la class Validation.</li>";
				}
			}
		}
	}
}

class Algorithms{
	
//CHECK IF FIELD IS EMPTY
	static function isNotEmpty($value){
		 return !empty($value);
	}
	
	static function isEmail($value){
			
		//(http://atranchant.developpez.com/code/validation/)
		// Le code suivant est la version du 2 mai 2005 qui respecte les RFC 2822 et 1035
		// http://www.faqs.org/rfcs/rfc2822.html
		// http://www.faqs.org/rfcs/rfc1035.html

		if(!empty($value)){
			$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
			$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
										   
			$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
			'(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
											// séparés par des caractères autorisés avant l'arobase
			'@' .                           // Suivis d'un arobase
			'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
											// séparés par des points
			$domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine
			
			
			if (preg_match($regex, $value)) return true;
				else return false;
		}else{
			return true;
		}
	}
	
	static function isDate($value){
		if(!empty($value)){
			$date = date_parse_from_format("d.m.Y", $value);
		
			if($date['error_count'] == 1 AND in_array('Data missing',$date['errors'])) return false;
				else return true;
		}else{
			return true;
		}
	}
	
	static function isDomain($value){
		
		if($fieldValue == "http://" OR empty($value)){ 
			return false;
		}else{
			if(ereg("@",$value)){
				$ls_domaine = str_replace("@","",strstr($value,"@")); 
			}else{ 
				$ls_domaine = str_replace("www.","",$value);
				if(ereg("/",$ls_domaine)){
					$nb_str = strpos($ls_domaine, "/");
					$ls_domaine = substr($ls_domaine, 0, $nb_str);
				}
			}
		
			//Inscrivez dans ce tableau les serveurs de noms de votre FAI.
			$la_serveur_de_nom=array(
				'ns10.csa-network.com',   
				'ns11.csa-network.com'
				#'212.27.32.177'     //Adresse IP du serveur de noms tertiaire de mon FAI (Free)
			);
			
			//Appel de la bibliothèque PEAR : Net DNS
			require_once 'include/Net/DNS.php';
			
			//Les fonctions ne peuvent pas s'appeler de façon statitique cette fois
			//On crée donc une instance de classe Net_DNS_Resolver
			$lo_resolver = new Net_DNS_Resolver();
			
			//Décommentez cette ligne pour afficher le debuggage
			//$lo_resolver->debug=1;
			
			//On précise nos noms de serveurs
			$lo_resolver->nameservers=$la_serveur_de_nom;
			
			//On lance une requête, on précise MX pour identifier un éventuel serveur de mail
			$lo_response = $lo_resolver->query($ls_domaine,'MX');
		
			//on teste la réponse
			if ($lo_response) {
			  foreach ($lo_response->answer as $lo_rr) {
				//On affiche le résultat pour l'exemple, mais c'est inutile dans l'aboslu
				$lo_rr->display();
				//echo "Nom de serveur de mail $ls_domaine valide";
				return false;
		  		}
			}else {
				return 'isNotADomain*';
#				echo $this->error = '<p class="f_error">Le nom de domaine <b>'.$ls_domaine.'</b> que vous avez rempli dans le champs <b>'.$name.'</b>, est inconnu</p>';
			}
		}
		
		
	}
	
	static function isNumeric($value){
		if(!empty($value)){
			return is_numeric($value);
		}else{
			return true;
		}
	}
	
	static function isString($value){
		return !is_numeric($value);
	}
	
	static function isStringOnly($value){
		if( preg_match( '/\d/' , $value) ) return false;
        	else return true;
	}
	
	static function minDigit($value,$condition_value){
		if(!empty($value)){
			$nb_caracter = strlen($value);
			if($nb_caracter < $condition_value) return false;
				else return true;
		}else{
			return true;
		}
	}
	
	static function maxDigit($value,$condition_value){
		if(!empty($value)){
			$nb_caracter = strlen($value);
			if($nb_caracter > $condition_value) return false;
				else return true;
		}else{
			return true;	
		}
	}
	
	static function nbDigit($value,$condition_value){
		if(!empty($value)){
			$nb_caracter = strlen($value);
			if($nb_caracter == $condition_value) return true;
				else return false;
		}else{
			return true;
		}
	}
	
	//REMOVE ACCENT
	static function RemoveAccent($string,$charset='utf-8'){
		$string = htmlentities($string, ENT_NOQUOTES, $charset);
	    $string = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml)\;#', '\1', $string);
    	$string = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $string); // pour les ligatures e.g. '&oelig;'
    	$string = preg_replace('#\&[^;]+\;#', '', $string); // supprime les autres caractères
		$string = preg_replace("#'#","_",$string); //supprime apostrophe. Ajouter par Pierrot
 		
    	return $string;
	}
	
	static function Strip_tags($string,$tags){
		$string = trim(strip_tags($string,str_replace("]",">",str_replace("[","<",$tags))));
		return $string;
	}
	
	static function Captcha($value){
		if($value != $_SESSION['security_number']) return false;
			else return true;	
	}
}
?>