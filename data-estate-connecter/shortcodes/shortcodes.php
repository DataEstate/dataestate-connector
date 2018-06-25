<?php

/***Shortcode For Name ****/
function dec_name($atts, $content = null){
	extract(shortcode_atts(array('name'=>'name'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		return $api_arry->$name;
	}
}
/***Shortcode For Name ****/
function atdw_beacon($atts, $content=null) {
	global $api_arry;
	$error=api_error_function();
	if ($error) {
		return $error;
	}
	else {
		$beacon_url='http://atlas.atdw-online.com.au/pixel?distributorId=56b1eb9444feca3df2e32101&language=en&syndicationMethod=API';
		$beacon_string='<img src="'.$beacon_url.'&productId='.$api_arry->id.'" alt="atdw web beacon" style="width:0px;height:0px">';
		return $beacon_string;
	}
}
/***Shortcode For Description ****/
function dec_description($atts, $content = null){
	extract(shortcode_atts(array('description'=>'description'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		return nl2br($api_arry->$description);
	}
}

/***Shortcode For Address ****/
function dec_address($atts, $content = null){
	extract(shortcode_atts(array('address'=>'addresses','type'=>'','get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		$result_string = "";
		if($type=='' && $get=='' ){
			return $api_arry->$address->{'PHYSICAL'}->{'street_address'};
		}
		else if($type!='' && $get==''){
			return $api_arry->$address->$type->{'street_address'};
		}
		else {
			$multi_get=explode(",",$get);
			//TODO
			$result_string = "";
			foreach($multi_get as $val){
				// this is not working as $type is not assigned
				$result_string .= $api_arry->$address->$type->$val.' ';
			}
			return $result_string;
		}
		// one more condition is missing which is either $type=='' && $get!='' OR $type!='' && $get!=''
		echo $result_string;;
	}
}
/***Shortcode For Address ****/

/***Shortcode For Attributes ****/
function dec_attributes($atts, $content = null){
	extract(shortcode_atts(array('attributes'=>'attributes','index'=>'', 'type'=>'','get'=>'', 'as'=>'text'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		$attrs = [];
		//Check Types
		if ($type != '') {
			if (isset($api_arry->{"attributes"})) {
				foreach ($api_arry->{'attributes'} as $attr) {
					if ($attr->{"type_id"} == $type) {
						$attrs[]=$attr;
					}
				}
			}
		}
		else {
			$attrs=$api_arry->$attributes;
		}
		//Check Get
		$get_array=["description"];
		if ($get != '') {
			$get_array=explode(",", $get);
		}
		//Check Index
		if ($index == '') {
			$result_string="<ul class='dec-attributes'>";
			$string_format='<li>%s</li>';
			if ($as=='class') {
				$string_format='<li><div class="de-badge de-tourismorgs key-%s"><span class="badge-text">%s</span></div></li>';
			}
			foreach ($attrs as $attr) {
				$get_string = "";
				foreach ($get_array as $val) {
					$get_string.=$attr->$val.' ';
				}
				if ($as=='class') {
					$result_string.=sprintf($string_format, $attr->id, $attr->description);
				}
				else {
					$result_string.=sprintf($string_format, $get_string);
				}
			}
			$result_string.="</ul>";
			return $result_string;
		}
		else {
			$get_string = "";
			foreach ($get_array as $val) {
				$get_string .=$attrs[$index]->$val.' ';
			}
			return $get_string;
		}
	}
}

/***Shortcode For rate ****/
function dec_rate($atts, $content=null) {
	extract(shortcode_atts(array('rate'=>'rate','type'=>'from', 'currency'=>''), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		if (isset($api_arry->$rate)) {
			if ($type=="to") {
				return $currency.$api_arry->$rate->to;
			}
			else if ($type=="from") {
				return $currency.$api_arry->$rate->from;
			} else if ($type == "between") {
				$price_to = $api_arry->$rate->to;
				$price_from = $api_arry->$rate->from;
				if ($price_to > 0 && $price_from > 0) {
					return $currency.$api_arry->$rate->from. " - " .$currency.$api_arry->$rate->to;
				} else {
					return $currency.$api_arry->$rate->from;
				}
			}
			else {
				return "";
			}
		}
		else {
			return "";
		}
	}
}

/***Shortcode For Category ****/
function dec_category($atts, $content = null){
	extract(shortcode_atts(array('category'=>'category'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		return $api_arry->$category;
	}
}

// 
function dec_subtypes($atts, $content = null) {
	extract(shortcode_atts(array('subtypes'=>'subtypes','index'=>'', 'get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		$attrs = $api_arry->$subtypes;;
		//Check Get
		$get_array=["description"];
		if ($get != '') {
			$get_array=explode(",", $get);
		}
		//Check Index
		if ($index == '') {
			$result_string="<ul class='dec-attributes'>";
			foreach ($attrs as $attr) {
				$get_string = "";
				foreach ($get_array as $val) {
					$get_string.=$attr->$val.' ';
				}
				$result_string.="<li>".$get_string."</li>";
			}
			$result_string.="</ul>";
			return $result_string;
		}
		else {
			$get_string = "";
			foreach ($get_array as $val) {
				$get_string .=$attrs[$index]->$val.' ';
			}
			return $get_string;
		}
	}
}

/***Shortcode For Email ****/
function dec_email($atts, $content = null){
	extract(shortcode_atts(array('emails'=>'emails','index'=>'','get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		if($index=='' && $get=='' ){
			return $api_arry->emails[0]->address;
		}
		else if($index!='' && $get==''){
			return $api_arry->emails[$index];
		}
		else if($index=='' && $get!=''){
			$multi_get=explode(",",$get);
			$result_string = "";
			foreach($multi_get as $val){
				$result_string .= $api_arry->emails[0]->$val.' ';
			}
			return $result_string;

		}
		else {
			$multi_get=explode(",",$get);
			$result_string = "";
			foreach($multi_get as $val){
				$result_string .= $api_arry->emails[$index]->$val.' ';
			}
			return $result_string;
		}
	}
}

/***Shortcode For Rooms ****/
function dec_rooms($atts, $content=null) {
	extract(shortcode_atts(array('get' => 'full', 'index' => ''), $atts));
	global $api_arry;
	if ($index == '') {
		$n = 0;
	}
	
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		if (isset($api_arry->rooms)) {
			if ($get=='full') {
				$room_string='<div class="dec-rooms">';
				foreach ($api_arry->rooms as $room) {
					$room_string.='<div class="dec-room">';
					if (isset($room->hero_image)) {
						$room_string.='<div class="dec-room-image">';
						$room_string.='<img src="'.$room->hero_image->sizes->medium->path;
						if (isset($room->hero_image->alt)) {
							$room_string.=' alt="'.$room->hero_image->alt.'"></img>';
						}
						$room_string.='</div>';
					}
					$room_string.='<div class="dec-room-content">';
					if (isset($room->name)) {
						$room_string.='<h4 class="dec-room-name">'.$room->name.'</h4>';
					}
					if (isset($room->description)) {
						$room_string.='<p class="dec-room-description">'.$room->description.'</p>';
					}
					$room_string.='</div></div>';
				}
				$room_string.='</div>';
				return $room_string;
			} else if ($get == "name") {
				return $api_arry->rooms[$n]->name;
			} else if ($get == "description") {
				return $api_arry->rooms[$n]->description;
			}
		}
		else {
			return "nothing";
		}
	}
}

/***Shortcode For Image ****/
function dec_images($atts, $content = null){
	extract(shortcode_atts(array('images'=>'images','index'=>"",'get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();

	if($error){
		return $error;
	}
	else {
		$img_objects = $api_arry->images;
		if (!is_null($img_objects)) {
			if ($index == "") {
				$index=count($img_objects);
			}

			if ($get=="") {
				$result="<div class='dec-img-container'>";
				for ($i=0; $i <= $index; $i++) {
					$img_obj = $img_objects[$i];
					$img_string = "<img src='".$img_obj->path."' alt='".$img_obj->alt."'>";
					$result.=$img_string;
				}
				$result.="</div>";
				return $result;
			}
			else {
				$multi_get=explode(",",$get);
				$result_string = "";

				// this is not working as $index not assigned
				foreach($multi_get as $val){
					$result_string .= $img_objects[$index]->$val.' ';
				}
				return $result_string;
			}
		}
	}
}
/***Shortcode For Image ****/


/***Shortcode For gallery ****/
function dec_gallery($atts, $content = null) {
	extract(shortcode_atts(array('images'=>'images', 'lightbox'=>'true'), $atts));
	global $api_arry;
	$error= api_error_function();

	if($error){
		return $error;
	}
	else {
		if ($lightbox==true && $lightbox=="true") {
			wp_enqueue_script("dec-lightbox-2", "https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js", ["jquery"], null, true);
			wp_enqueue_style("dec-lightbox-2-style", "https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css");
		}
		$img_objects = $api_arry->images;
		if (!is_null($img_objects)) {
			$result="<div class='dec-gallery'>";
			foreach ($img_objects as $img_obj) {
				$img_string = "<img src='".$img_obj->path."?h=150' alt='".$img_obj->alt."'>";
				if ($lightbox==true && $lightbox=="true") {
					$result.='<a href="'.$img_obj->path.'?w=600" data-title="'.$img_obj->alt.'" data-lightbox="dec-gallery">'.$img_string."</a>";
				}
				else {
					$result.="<div>".$img_string."</div>";
				}
			}
			$result.="</div>";
			return $result;
		}
	}
}

/***Shortcode For Phone  ****/
function dec_phone($atts, $content = null){
	extract(shortcode_atts(array('phones'=>'phones','index'=>'','get'=>'', format=>'TEL'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		//return $error;
		return "";
	}
	else {
		if (isset($api_arry->{"phones"})) {
			if($index=='' && $get=='' ){
				foreach ($api_arry->phones as $phone_key => $phone_val) {
					if ($api_arry->phones[$phone_key]->format == $format) {
						return '('.$api_arry->phones[$phone_key]->calling_code.') '. $api_arry->phones[$phone_key]->area_code . ' ' . $api_arry->phones[$phone_key]->number;
					}
				}
			}
			else if($index!='' && $get==''){
				return '('.$api_arry->phones[$index]->calling_code.') '. $api_arry->phones[$index]->area_code . ' ' . $api_arry->phones[$index]->number;
			}
			else if($index=='' && $get!=''){
				$multi_get=explode(",",$get);
				$result_string = "";
				foreach($multi_get as $val){
					$result_string .= $api_arry->phones[0]->$val.' ';
				}
				return $result_string;
			}
			else {
				$multi_get=explode(",",$get);
				$result_string = "";
				foreach($multi_get as $val){
					$result_string.= $api_arry->phones[$index]->$val.' ';
				}
				return $result_string;
			}
		}
	}
}
/***Shortcode For Phone  ****/

/***Shortcode For Url  ****/
function dec_url($atts, $content = null){
	extract(shortcode_atts(array('urls'=>'urls','index'=>'','get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();

	if($error){
		return $error;
	}
	else {
		if($index=='' && $get=='' ){
			$result_string='http://'.str_replace("http://", "", $api_arry->urls[0]->address);
			return $result_string;
		}
		else if($index!='' && $get==''){
			return $api_arry->urls[$index];
		}
		else if($index=='' && $get!=''){
			$multi_get=explode(",",$get);
			$result_string = "";
			foreach($multi_get as $val){
				$result_string .= 'http://'.str_replace("http://", "", $api_arry->urls[0]->$val);
			}
			return $result_string;
		}
		else {
			$multi_get=explode(",",$get);
			$result_string = "";
			foreach($multi_get as $val){
				$result_string .= $api_arry->urls[$index]->$val.' ';
			}
			$result_string='http://'.str_replace("http://", "", $result_string);

			return $result_string;
		}
	}
}

/***Shortcode For Location  ****/
function dec_location($atts, $content = null) {
	extract(shortcode_atts(array('width'=>'100%', 'height'=>'100%', 'zoom'=>8, 'scrollwheel'=>false), $atts));
	global $api_arry;
	$error=api_error_function();
	if ($error) {
		return $error;
	}
	else {
		// dec-google-map.js is missing
		wp_enqueue_script('dec-google-map', false, [], false, true);
		$sw= ($scrollWheel || $scrollWheel =="true") ? "true" : "false";
		$address = $api_arry->{'addresses'}->{'PHYSICAL'};
		$name = $api_arry->{'name'};
		if (isset($address->{'geocode'})) {
			$map_script = "var map;
				var eLatLng = {lat:".$address->{'geocode'}->{'lat'}.", lng: ".$address->{'geocode'}->{'lng'}."};
				function initMap() {
					map = new google.maps.Map(document.getElementById('map'), {
						center: eLatLng, zoom: ".$zoom.",
						scrollwheel: ".$sw."
					});
					var marker = new google.maps.Marker({
						position: eLatLng,
						map: map,
						title: '".addslashes($name)."'
					});
				}";
			$map_div="<div id='map' style='width:".$width.";height:".$height."'></div>";
			return $map_div."<script>".$map_script."</script>";
		}
		else {
			return "<script>function initMap() { console.log('No geocodes found')</script>";
		}
	}
}

/***Shortcode For Rating  ****/
function dec_star_rating($atts, $content=null) {
	extract(shortcode_atts(array('as_icon'=>'true', 'default_text'=>'NA'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		$star_rating="";
		if (isset($api_arry->{'attributes'})) {
			foreach ($api_arry->{'attributes'} as $attribute) {
				if ($attribute->{'type_id'}=='RATING AAA') {
					$star_rating=$attribute->{'id'};
				}
			}
		}
		if ($as_icon=="true") {
			wp_enqueue_style("google-material-font");
			if ($star_rating=="" || $star_rating == "NA") {
				return $default_text;
			}
			else {
				$star_rating=floatval($star_rating);
				$rating_string="";
				$rating_string="<div class='dec-star-rating'>";

				$i=0;
				while ($i < floor($star_rating)) {
					$rating_string.="<i class='material-icons'>star</i>";
					$i++;
				}
				if ($star_rating % 1 != 0) {
					$rating_string.="<i class='material-icons'>star_half</i>";
					$i++;
				}
				while ($i<5) {
					$rating_string.="<i class='material-icons'>star_border</i>";
					$i++;
				}

				$rating_string.="</div>";
				return $rating_string;
			}
		}
	}
}

/***Shortcode For TXA Button  ****/
function dec_txa_button($atts,$content=null) {
	extract(shortcode_atts(array('enquire'=>""), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		if (isset($api_arry->{'linked_services'})) {
			foreach ($api_arry->{'linked_services'} as $linked_service) {
				if ($linked_service->id=="TXA_DEFAULT") {
					$txa_url="http://www.au.v3travel.com/CABS2/DiscoveryServices/ProviderAvailability.aspx";
					//TODO: Make it dynamically call user!
					$shortname="Kangaroo_Island_Escapes_web";
					$property_shortname=$linked_service->value;
					$button='<a class="dec-txa-book" target="_blank" href="'.$txa_url.'?exl_dn='.$shortname.'&exl_psn='.$property_shortname.'">Book Now</a>';
					return $button;
				}
			}
			if ($enquire != "") {
				return '<a class="dec-txa-enquire" href="'.$enquire.'?id='.$api_arry->id.'">Enquire</a>';
			}
			else {
				return "";
			}
		}
		else {
			return "";
		}
	}
	//SOAP Request
	//$wsdl="https://www.au.v3travel.com/CABS.WebServices/SearchService.asmx?WSDL";

}

/***Shortcode For Event Date  ****/
function dec_event_date($atts, $content=null) {
	extract(shortcode_atts(array('format'=>'l, jS F Y', 'asEndDate'=>'false'), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
		if ($asEndDate!='false') {
			if (isset($api_arry->{'latest_end_date'})) {
				$date = new DateTime($api_arry->{'latest_end_date'});
				return $date->format($format);
			}
			else {
				return '';
			}
		}
		if (isset($api_arry->{'latest_date'})) {
			$date = new DateTime($api_arry->{'latest_date'});
			return $date->format($format);
		}
		else {
			return '';
		}
	}
}

function dec_awards($atts, $content = null){
	extract(shortcode_atts(array('attributes'=>'tourism_awards','index'=>'','get'=>''), $atts));
	global $api_arry;
	$error= api_error_function();
	if($error){
		return $error;
	}
	else {
        $attrs = [];
        $attrs=$api_arry->$attributes;

		if (count($attrs) > 0) {
			//Check Index
			if ($index == '') {
				$result_string="<ul class='dec-awards'>";

				foreach ($attrs as $attr) {
					$result_string='<li>'.$attr->name.'</li>';
				}
				$result_string.="</ul>";
				return $result_string;
			}
			else {
				$get_string = "";
				foreach ($get_array as $val) {
					$get_string .=$attrs[$index]->$val.' ';
				}
				return $get_string;
			}
		} else {
			return 'NOTHING';
		}
	}
}

