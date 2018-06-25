<?php 
/**
*
* @author Data Estate
*
*/
/** Shortcode for Data Estate Search Widget **/
function dec_widget($atts, $content = null){
	$attributes = shortcode_atts(array(
		'autoload'=>true,
		'use_get'=>true,
		'result_url'=>'self',
		'bg'=>null, 
		'states'=>null, 
		'categories'=>null, 
		'areas'=>null, 
		'localities'=>null,
		'detail_url'=>null, 
		'page_size'=>20,
		'date_format'=>'dth mmm yy',
		"txa_widget"=>false, 
		"class_name"=>"",
		"sort"=>"name",
		"badges"=>"",
		"enquire_url"=>null), $atts);
	$attr_map = [
		'autoload'=>autoload,
		'use_get'=>'use_get', 
		'result_url'=>'search_action', 
		'bg'=>'widget_bg', 
		'states'=>'limit_states', 
		'categories'=>'limit_categories', 
		'areas'=>'limit_areas', 
		'localities'=>'limit_localities',
		'detail_url'=>'detail_link',
		'page_size'=>'page_size',
		'date_format'=>'date_format',
		"txa_widget"=>"txa_widget",
		"class_name"=>"class_name",
		"sort"=>"default_sort",
		"badges"=>"badges",
		"enquire_url"=>"enquire_link"
	];
	$error= api_error_function();
	if($error){
		//return $error;
		return "";
	}
	else {
		$class_inject="";
		//Hijack class name
		if (isset($atts["class_name"])) {
			$class_inject="class=\"".$atts["class_name"]."\"";
			unset($atts["class_name"]);
		}
		$create_container=$atts["create_container"];
		if ($atts["txa_widget"]) {
			wp_enqueue_script('dec-search-widget-txa');
		}
		else {
			wp_enqueue_script('dec-search-widget');
		}
		
		$widget_conf = [];
		foreach ($attributes as $att_key=>$att) {
			if (!is_null($att)) {
				$widget_conf[$attr_map[$att_key]]=$att;
			}
		} 
		$widget_conf = json_encode($widget_conf);
		$widget_func = "
		function init() { 
			var opt=".$widget_conf."; 
			var searchwid= new SearchWidget('#dewid', opt);
		}";
		$widget_script = "<script>".$widget_func."</script>";
		if ($create_container!=false && $create_container != "false") {
			$widget_div = '<div id="dewid" '.$class_inject.'></div>';
		}
		else {
			$widget_div="";
		}
		return $widget_script.$widget_div;
	}
}
function dec_widget_element($atts, $content=null) {
	
}
function dec_map_widget($atts, $content=null) {
	extract(shortcode_atts(array('width'=>'100%', 'height'=>'100%', 'zoom'=>14, 'scrollwheel'=>false, 'detail_url'=>null, 'localities'=>null, 'states'=>null,'categories'=>null,'areas'=>null,'regions'=>null, 'size'=>200,"lat"=>"-25.274411", "lng"=>"133.775132", "radius"=>20), $atts));
	wp_enqueue_script('dec-google-map', false, [], false, true);
	wp_enqueue_script('dec-map-clusterer', false, [], false, true);
	wp_enqueue_script('jquery');
	//GET DATAESTATE API//
	global $wpdb;
	$api_info = $wpdb->get_results("SELECT api_base_url, api_end_point, api_key from `".DEC_TABLE_DETAILS."` WHERE id=1", ARRAY_A);
	if (count($api_info) > 0) {
		$sw= ($scrollWheel || $scrollWheel =="true") ? "true" : "false";
		$api_url = $api_info[0]["api_base_url"].'/'.$api_info[0]["api_end_point"];
		$api_key = $api_info[0]["api_key"];
		$map_script="
		function initMap() {
				var api_url=\"".$api_url."\";
				console.log(api_url);
				$ = jQuery.noConflict();
				var center= {lat:".$lat.", lng:".$lng."};
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: ".$zoom.", 
					center: center, 
					scrollwheel: ".$sw."
				});
				var q = {
					\"api_key\":\"".$api_key."\",";
		if (!is_null($categories)) {
			$map_script.="\"categories\":\"".$categories."\",";
		}
		if (!is_null($localities)) {
			$map_script.="\"localities\":\"".$localities."\",";
		}
		if (!is_null($states)) {
			$map_script.="\"states\":\"".$states."\",";
		}
		if (!is_null($areas)) {
			$map_script.="\"areas\":\"".$areas."\",";
		}
		if (!is_null($regions)) {
			$map_script.="\"regions\":\"".$regions."\",";
		}
			$map_script.="\"fields\":\"id,name,category_code,category,geo_location,description,addresses.PHYSICAL,images,latest_date\", 
					\"size\": ".$size.", 
					\"near\": \"".$lat.",".$lng."\",
					\"max_km\": ".$radius."
				};
				var markers=[];
				var image_path = \"http://warehouse.dataestate.com.au/DE/images/map/\";
				var info_window = new google.maps.InfoWindow();
				$.get(api_url, q)
					.done(function(response) {
						jQuery.each(response, function(i, item) {
							if (item.geo_location.coordinates !== undefined) {
								var coordinates = item.geo_location.coordinates
								var icon = {
									url: image_path+item.category_code+\".png\", 
									size: new google.maps.Size(30,30), 
									scaledSize: new google.maps.Size(30,30)
								};
								var marker = new google.maps.Marker({
									\"position\": {\"lng\": coordinates[0], \"lat\": coordinates[1]}, 
									\"title\": item.name, 
									\"icon\": icon
								});
								var categoryColor = {
									\"COMPANY\": \"#B8E986\",
									\"PORTFOLIO\": \"#9B23B4\",
									\"ACCOMM\": \"#4A90E2\",
									\"ATTRACTION\": \"#F5A623\",
									\"DESTINFO\": \"#D03802\",
									\"EVENT\": \"#EF3F3D\",
									\"RESTAURANT\": \"#FF8291\",
									\"GENSERVICE\": \"#417505\",
									\"HIRE\": \"#F8CE1C\",
									\"INFO\": \"#275891\",
									\"JOURNEY\": \"#AC621F\",
									\"TOUR\": \"#4AB485\",
									\"TRANSPORT\": \"#50E3C2\"
								};
								var imgContainerStyle= 'width:60px;height:60px;display:inline-block;overflow:hidden;vertical-align:middle;margin-right:10px';
								var titleContainerStyle='display:inline-block;vertical-align:top;width:height:60px';
								var titleStyle='margin:0px;font-size:18px;padding:0px';
								var addressStyle='margin-top:5px;font-size:12px';
								var paragraphStyle='margin-top:5px;font-size:12px';

								google.maps.event.addListener(marker, 'click', function() {
									var addressString = item.addresses.PHYSICAL.street_address+\", \"+item.addresses.PHYSICAL.locality+\" \"+item.addresses.PHYSICAL.state_code+\" \"+item.addresses.PHYSICAL.post_code;
									var dateString =\"\";
									if (item.latest_date !== undefined){
										var d = new Date(item.latest_date);
										dateString = d.getDate()+\"/\"+(d.getMonth()+1)+\"/\"+d.getFullYear()+\", \";
									}
									var categoryStyle='color:'+categoryColor[item.category_code]+';font-weight:bold;font-size:14px;margin-top:5px;display:inline-block';
									var contentString = \"<div class='marker-content'>\";
									contentString+=\"<div style='\"+imgContainerStyle+\"'>\";
									contentString+=\"<img src='\"+item.images[0].path+\"?w=100' alt='\"+item.images[0].alt+\"' /></div>\";
									contentString+=\"<div style='\"+titleContainerStyle+\"'>\";
									contentString+=\"<h2 style='\"+titleStyle+\"'>\"+item.name+\"</h2>\";
									contentString+=\"<span style='font-weight:bold'>\"+dateString+\"</span>\";
									contentString+=\"<span style='\"+categoryStyle+\"'>\"+item.category+\"</span>\";
									contentString+=\"<p style='\"+addressStyle+\"'>\"+addressString+\"</p></div></div>\";\n";
			if (!is_null($detail_url)) {
				$map_script.="
									contentString+=\"<div style='width:100%;text-align:right'>\";
									contentString+=\"<div style='width:100%;text-align:right;margin-bottom:10px'>\";
									contentString+=\"<a href='".$detail_url."?id=\"+item.id+\"' style='text-decoration:none;display:inline-block;width:100px;border:1px solid #aaa;padding:5px;color:black;text-align:center'>View Details</a>\";
									contentString+=\"</div>\";";
			}
			$map_script.="info_window.setContent(contentString);
									info_window.open(map,marker);
								});
								markers.push(marker);
							}
						});
						var clusterOption={
							\"height\": 40, 
							\"width\": 40, 
							\"iconAnchor\":[40,0], 
							\"textSize\":16, 
							\"textColor\":'white'
						};
						var markerClusters=new MarkerClusterer(map, markers, 
							{
								\"styles\":[
									{
										\"url\": image_path+\"CLUSTER/M1.png\", 
										\"height\": clusterOption.height, 
										\"width\": clusterOption.width, 
										\"iconAnchor\": clusterOption.iconAnchor, 
										\"textSize\": clusterOption.textSize, 
										\"anchor\": clusterOption.anchor, 
										\"textColor\": clusterOption.textColor
									}, 
									{
										\"url\": image_path+\"CLUSTER/M2.png\", 
										\"height\": clusterOption.height, 
										\"width\": clusterOption.width, 
										\"iconAnchor\": clusterOption.iconAnchor, 
										\"textSize\": clusterOption.textSize, 
										\"anchor\": clusterOption.anchor, 
										\"textColor\": clusterOption.textColor
									}, 
									{
										\"url\": image_path+\"CLUSTER/M3.png\", 
										\"height\": clusterOption.height, 
										\"width\": clusterOption.width, 
										\"iconAnchor\": clusterOption.iconAnchor, 
										\"textSize\": clusterOption.textSize, 
										\"anchor\": clusterOption.anchor, 
										\"textColor\": clusterOption.textColor
									}, 
									{
										\"url\": image_path+\"CLUSTER/M4.png\", 
										\"height\": clusterOption.height, 
										\"width\": clusterOption.width, 
										\"iconAnchor\": clusterOption.iconAnchor, 
										\"textSize\": clusterOption.textSize, 
										\"anchor\": clusterOption.anchor, 
										\"textColor\": clusterOption.textColor
									}, 
									{
										\"url\": image_path+\"CLUSTER/M5.png\", 
										\"height\": clusterOption.height, 
										\"width\": clusterOption.width, 
										\"iconAnchor\": clusterOption.iconAnchor, 
										\"textSize\": clusterOption.textSize, 
										\"anchor\": clusterOption.anchor, 
										\"textColor\": clusterOption.textColor
									}
									],
								\"maxZoom\":18
							}
						);
				});
			}";
		$map_div="<div id='map' style='width:".$width.";height:".$height."'></div>";
		return $map_div."<script>".$map_script."</script>";
	}
}
//Enclosing shortcode
function dec_assets($atts, $content=null) {
	extract(shortcode_atts(['fields'=>'', 'size'=>20,'sort'=>'name','types'=>'', 'template_keys'=>'name'], $atts));
	$deApi = De_api::get_instance();
	$queryParams=[];
	foreach ($atts as $aKey => $aVal) {
		if ($aVal !='' && $aKey !='template_keys') {
			$queryParams[$aKey]=$aVal;
		}
	}
	$deAssets = $deApi->assets($queryParams);
	$templateKeys=explode(",", $template_keys);
	$resultString='<div class="de-assets">';
	//Build content template
	foreach ($deAssets as $asset) {
		$varArray=[];
		foreach ($templateKeys as $key) {
			$key = str_replace("$", "", $key);
			if (isset($asset->$key)) {
				$varArray[]=$asset->$key;
			}
			else {
				$varArray[]="";
			}
		}
		$resultString .= str_replace($templateKeys, $varArray, $content);
		//$assetString.='<li>'.$asset->{'name'}..'</li>';
	}
	$resultString.='</div>';
	return $resultString;
}


//Enclosing shortcode
function dec_estates($atts, $content=null) {
	extract(shortcode_atts(['fields'=>'', 'size'=>20,'sort'=>'name','category_code'=>'',
	'template_keys'=>'$name', 'award_description'=>'', 'states'=>'', 'areas'=>'', 'regions'=>'',
	 'categories'=>'', 'localities'=>'', 'atap'=>'', 'subtypes'=>'', 'att_types'=>'', 
	'attributes'=>'', 'sort'=>'', 'pg'=>'1', 'url_params'=>True, 'desc_wordslength' => '', 'shortcode_content'=>'true'], $atts));


	// get params from url
	parse_str($_SERVER['QUERY_STRING'], $output);
	$deApi = De_api::get_instance();
	$queryParams=[];
	foreach ($atts as $aKey => $aVal) {
		if ($aVal !='' && $aKey !='template_keys') {
			// if url params has same atts, then use url params value
			if (array_key_exists($aKey, $output)) {
				$queryParams[$aKey]=$output[$aKey];
				unset($output[$aKey]);
			}else {
				$queryParams[$aKey]=$aVal;
			}
		}
	}
	$queryParams = array_merge($queryParams,$output);	//to be reviewed
	//print_r($queryParams);

	$deEstates = $deApi->estates($queryParams);
	$templateKeys=explode(",", $template_keys);

	$resultString.='<div class="de-estates">';
	//Build content template
	foreach ($deEstates as $asset) {

		$varArray=[];
		foreach ($templateKeys as $key) {
			$key_arr=explode(".", $key);
			$key = str_replace("$", "", $key);
			
			if (isset($asset->$key)) {
				//limit the description length
				if ($desc_wordslength > 0 && $key == 'description') {
					$varArray[]= mb_strimwidth(strip_tags($asset->$key), 0, $desc_wordslength, "...");
				} else {
					$varArray[]=strip_tags($asset->$key);
				}
			}else {
				//Allow user to call nested shortcode field, e.g. addresses.PHYSICAL.state
				//Remove '$' sign on the first element of array 
				$key_arr[0] = str_replace("$", "", $key_arr[0]);
				if (count($key_arr) > 1) {
					// if api result has this result, then get the result with the given shortcode fields, else leave it blank
					if(property_exists($asset, $key_arr[0])) {
						$i = 0;
						$current = $asset;
// 						check whether the array exists, if not return empty
						foreach ($key_arr as $item) {
							if(property_exists($current, $item)) {
								$current = $current->$item;
							} else {
								$current = "";
								break;
							}
						}
						
						if ($key_arr[0] == "rate") {
							$current = "$ ".number_format($current, 2);
						} else {
							$current=strip_tags($current);
						}
						$varArray[]=$current;
						
					} else {
						$varArray[]="";
					}
				} else {
					$varArray[]="";
				}
			}
		}

		//print_r($templateKeys);
		//print_r($varArray);
		// echo htmlspecialchars($content);
		$resultString .= str_replace($templateKeys, $varArray, $content);
	}
	$resultString.='</div>';


	//if nested shortcodes
	if ($shortcode_content=='false' || $shortcode_content=='') {
		return $resultString;
	}else {
		//echo htmlspecialchars($resultString);
		return do_shortcode($resultString);
	}
}


//if output is empty
function dec_ifnot_empty($atts, $content=null) {
	extract(shortcode_atts([
		'field'=>''
	], $atts));
	if (strlen($field) > 0) {
		return $content;
	} else {
		return '';
	}
}

function dec_awarded_estates($atts, $content=null) {
	extract(shortcode_atts([
		'fields'=>'', 'award'=>'Australian Tourism Awards',
		'year'=>'', 'description'=>'', 'category'=>'', 'states'=>'',
		'categories'=>'', 'template_keys'=>'', 'shortcode_content'=>'false', 'size'=>'', 'pg'=>'1'
	], $atts));
	$deApi = De_api::get_instance();
	$queryParams=[];
	foreach ($atts as $aKey => $aVal) {
		if ($aVal !='' && !in_array($aKey, ['template_keys', 'shortcode_content'])) {
			$queryParams[$aKey]=$aVal;
		}
	}
	$deAwardGroups = $deApi->estates($queryParams, null, 'winners');
	$templateKeys=explode(",", $template_keys);
	$resultString.='<div class="de-award-group">';
	//Award Group
	foreach ($deAwardGroups as $awardGroup) {
		if (isset($awardGroup->name)) {
				$resultString.='<h3 class="de-award-name">'.$awardGroup->name.'</h3>';
		}
		$resultString.='<div class="de-description-group">';
		if (isset($awardGroup->description) || isset($awardGroup->year)) {
			$resultString.='<div class="de-award-details">';
			if (isset($awardGroup->year)) {
				$resultString.='<span class="de-award-year">'.$awardGroup->year.'</span>';
			}
			if (isset($awardGroup->description)) {
				$resultString.='<span class="de-award-category">'.$awardGroup->description.'</span>';
			}
			$resultString.='</div>';
		}
		if (isset($awardGroup->estates)) {
			foreach ($awardGroup->estates as $estate) {
				$varArray=[];
				foreach ($templateKeys as $key) {
					$key = str_replace("$", "", $key);
					//Split into sections
					$keyParts = explode(".", $key);
					$val = (array)$estate;
					foreach ($keyParts as $keyPart) {
						if (is_array($val)) {
							if (isset($val[$keyPart])) {
								$val = $val[$keyPart];
								if (is_object($val)) {
									$val=(array)$val;
								}
							}
							else {
								$val="";
							}
						}
					}
					$varArray[] = $val;
				}
				$resultString .= str_replace($templateKeys, $varArray, $content);
			}
		}
		$resultString.='</div>';
	}
	$resultString.='</div>';
	if ($shortcode_content=='false' || $shortcode_content=='') {
		return $resultString;
	}
	else {
		return do_shortcode($resultString);
	}
}

function dec_condition($atts, $content=null) {
	extract(
		shortcode_atts([
			'if'=>'', 'target'=>''
		], $atts)
	);
	switch ($target) {
		case '':
			if (eval_condition($if)) {
				return $content;
			}
			else {
				return "";
			}
			break;
		case 'estate':
			global $api_arry;
			if (get_field($api_arry, $if)=="") {
				return "";
			}
			else {
				return $content;
			}
			break;
			//$if is an estate field in this. 
	}
}

function eval_condition($conditionString) {
	if ($conditionString== "") {
		return false;
	}
	$functionString = "return (".$conditionString.");";
	return eval($functionString);
}

//Get PATH, TODO: Helper
function get_field($object=[], $pathString="", $default="") {
	$keyParts = explode(".", $pathString);
	$val = (array)$object; //Use Array if object
	foreach ($keyParts as $keyPart) {
		if (is_array($val)) {
			if (isset($val[$keyPart])) {
				$val = $val[$keyPart];
				if (is_object($val)) {
					$val=(array)$val;
				}
			}
			else {
				$val=$default; //If not found, return default
			}
		}
	}
}











