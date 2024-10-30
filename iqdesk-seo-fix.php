<?php
/**
 * Plugin Name: iQDesk SEO Fix for qTranslate
 * Description: Helps to manage meta tags/seo definitions for enabled langugaes on the website
 * Version: 1.0
 * Author: iQDesk
 * Author URI: http://iqdesk.co.uk
 */
add_action('wpseo_tab_content','iqdesk_seo_init',1000);
add_filter('sanitize_text_field','iqdesk_sanitize_text_field',1000,2);
add_filter('get_post_metadata','iqdesk_seo_fix_metadata',10,4);
function iqdesk_sanitize_text_field($filtered,$str){
	$return=$filtered;
	$allowed=array("yoast_wpseo_focuskw","yoast_wpseo_title","yoast_wpseo_metadesc","yoast_wpseo_metakeywords");
	if (isset($_POST)) {
		foreach($_POST as $key=>$value) {
			if (is_string($value)) {
				if ($value==$str && in_array(strtolower($key),$allowed)) {
					$return=$str;
					break;
				}
			}
		}
	}
	return $return;
}
function iqdesk_seo_fix_metadata($metadata, $object_id, $meta_key, $single){
	GLOBAL $skip_search,$q_config;
	if (!is_admin()) {
		if ($meta_key=="" && !$skip_search) {
			$skip_search=true;
			$return=get_post_custom($object_id);
			foreach($return as $key=>$value) {
				if (substr_count($key,"_yoast_wpseo_")>0) {
					$return[$key]=qtrans_use($q_config['language'],$value,false);
				}
			}
			$skip_search=false;
		} else {
			$return=$metadata;
		}
	}
	return $return;
}
function iqdesk_seo_init(){
	GLOBAL $q_config;
	if (!empty($q_config)) {
		echo '
		<script>
		var cache = {}, lastXhr, _fields;
	
		_fields=jQuery(".wpseotab.general").find("#wpseosnippet");
		_fields.each(function(){
			var _wrp=jQuery(this).closest("td");
			var _content=_wrp.html();
			_wrp.wrapInner("<div style=\'display:none;\'></div>");
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			_wrp.append("\
				<div class=\'iqdesk-seo-fix-row\'>\
					<div style=\'font-weight:bold;padding-bottom:5px;padding-top:5px;\'>'.$q_config['language_name'][$q_config['enabled_languages'][$i]].'</div>\
					"+_content+"\
				</div>\
			");
			_wrp.find(".iqdesk-seo-fix-row:last").find("#wpseosnippet").attr("id",_wrp.find(".iqdesk-seo-fix-row:last").find("#wpseosnippet").attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			';
		}	
		echo '
		});	
	
		_fields=jQuery(".wpseotab.general").find("textarea");
		_fields.each(function(){
			var _wrp=jQuery(this).closest("td");
			var _content=_wrp.html();
			var _parent_name=jQuery(this).attr("id");
			_wrp.wrapInner("<div style=\'display:none;\'></div>");
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			_wrp.append("\
				<div class=\'iqdesk-seo-fix-row\'>\
					<div style=\'font-weight:bold;padding-bottom:5px;padding-top:5px;\'>'.$q_config['language_name'][$q_config['enabled_languages'][$i]].'</div>\
					"+_content+"\
				</div>\
			");
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").attr("id",_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").removeAttr("name");
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").attr("_iqdesk_seo_fix","true");
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").attr("_iqdesk_seo_lang","'.$q_config['enabled_languages'][$i].'");
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").attr("_iqdesk_seo_parent",_parent_name);
			_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").val(iqdesk_seo_fix_prepare_text(_wrp.find(".iqdesk-seo-fix-row:last").find("textarea").val(),"'.$q_config['enabled_languages'][$i].'"));
			if (_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_metadesc-length").length>0) {
				_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_metadesc-length").attr("id",_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_metadesc-length").attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			}			
			';
		}	
		echo '
		});		
	
		_fields=jQuery(".wpseotab.general").find("input[type=\'text\']");
		_fields.each(function(){
			var _wrp=jQuery(this).closest("td");
			var _content=_wrp.html();
			var _parent_name=jQuery(this).attr("id");
			var _current_id=_wrp.find("input[type=\'text\']").attr("id");
			_wrp.wrapInner("<div style=\'display:none;\'></div>");
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			_wrp.append("\
				<div class=\'iqdesk-seo-fix-row\'>\
					<div style=\'font-weight:bold;padding-bottom:5px;padding-top:5px;\'>'.$q_config['language_name'][$q_config['enabled_languages'][$i]].'</div>\
					"+_content+"\
				</div>\
			");
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").attr("id",_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").removeAttr("name");
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").attr("_iqdesk_seo_fix","true");
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").attr("_iqdesk_seo_lang","'.$q_config['enabled_languages'][$i].'");
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").attr("_iqdesk_seo_parent",_parent_name);
			_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").val(iqdesk_seo_fix_prepare_text(_wrp.find(\'.iqdesk-seo-fix-row:last\').find("input[type=\'text\']").val(),"'.$q_config['enabled_languages'][$i].'"));
			if (_wrp.find(\'.iqdesk-seo-fix-row:last\').find(\'#focuskwresults\').length>0) {
				_wrp.find(\'.iqdesk-seo-fix-row:last\').find(\'#focuskwresults\').attr("id",_wrp.find(\'.iqdesk-seo-fix-row:last\').find(\'#focuskwresults\').attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			}
			if (_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_title-length").length>0) {
				_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_title-length").attr("id",_wrp.find(".iqdesk-seo-fix-row:last").find("#yoast_wpseo_title-length").attr("id")+"_'.$q_config['enabled_languages'][$i].'");
			}				
			';
		}
		echo '			
		});
		jQuery(document).ready(function(){
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			jQuery(document).on("blur","#qtrans_title_'.$q_config['enabled_languages'][$i].'",function(){
				setTimeout(function(){
					jQuery("#titlewrap #title").trigger("change");
				},10);
			});		
			jQuery("#yoast_wpseo_focuskw_'.$q_config['enabled_languages'][$i].'").autocomplete({
				minLength   : 3,
				formatResult: function (row) {
					return jQuery("<div/>").html(row).html();
				},
				source      : function (request, response) {
					var term = request.term;
					if (term in cache) {
						response(cache[ term ]);
						return;
					}
					request._ajax_nonce = wpseo_keyword_suggest_nonce;
					request.action = "wpseo_get_suggest";

					lastXhr = jQuery.getJSON(ajaxurl, request, function (data, status, xhr) {
						cache[ term ] = data;
						if (xhr === lastXhr) {
							response(data);
						}
					});
				}
			});		
			jQuery("#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'").keyup(function () {
				iqdesk_seo_fix_updateTitle();
			});
			jQuery("#yoast_wpseo_metadesc_'.$q_config['enabled_languages'][$i].'").keyup(function () {
				iqdesk_seo_fix_updateDesc();
			});
			jQuery(document).on("change", "#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'", function () {
				iqdesk_seo_fix_updateTitle();
			});
			jQuery(document).on("change", "#yoast_wpseo_metadesc_'.$q_config['enabled_languages'][$i].'", function () {
				iqdesk_seo_fix_updateDesc();
			});
			jQuery(document).on("change", "#yoast_wpseo_focuskw_'.$q_config['enabled_languages'][$i].'", function () {
				iqdesk_seo_fix_updateSnippet();
			});			
			';	
		}
		echo '
			jQuery("#excerpt").keyup(function () {
				iqdesk_seo_fix_updateDesc();
			});	
			jQuery(document).on("change", "#excerpt", function () {
				iqdesk_seo_fix_updateDesc();
			});
			jQuery(document).on("change", "#content", function () {
				iqdesk_seo_fix_updateDesc();
			});
			jQuery(document).on("change", "#qtrans_textarea_content", function () {
				iqdesk_seo_fix_updateDesc();
			});			
			jQuery(document).on("change", "#tinymce", function () {
				iqdesk_seo_fix_updateDesc();
			});
			jQuery(document).on("change", "#titlewrap #title", function () {
				iqdesk_seo_fix_updateTitle();
			});
			jQuery("#wpseo_regen_title").click(function () {
				iqdesk_seo_fix_updateTitle(1);
				return false;
			});				
			jQuery(document).on("submit","form#post",function(e){
				var _cur_lng="";
				var _cur_id="";
				var _values={};
				jQuery("*[_iqdesk_seo_fix=\'true\']").each(function(){
					_cur_lng=jQuery(this).attr("_iqdesk_seo_lang");
					_cur_id=jQuery(this).attr("_iqdesk_seo_parent");
					if (typeof _values[_cur_id]=="undefined") _values[_cur_id]={};
					_values[_cur_id][_cur_lng]=jQuery(this).val();
				});
				for(_parent_id in _values){
					var _value_to_store="";
					for(_lang in _values[_parent_id]) {
						_value_to_store+="<!--:"+_lang+"-->"+_values[_parent_id][_lang]+"<!--:-->";
					}
					if (jQuery("#"+_parent_id).length>0) {
						jQuery("#"+_parent_id).val(_value_to_store);
					}
				}
			});
			iqdesk_seo_fix_updateSnippet();
		});
		function iqdesk_seo_fix_updateSnippet(){
			iqdesk_seo_fix_updateURL();
			iqdesk_seo_fix_updateTitle();
			iqdesk_seo_fix_updateDesc();		
		}
		function iqdesk_seo_fix_updateURL() {
			var name = jQuery("#editable-post-name-full").text();
			var url = wpseo_permalink_template.replace("%postname%", name).replace("http://","");
			url = boldKeywords(url, true);
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .url").html(url);
			';
		}
		echo '
			iqdesk_seo_fix_testFocusKw();
		}	
		function iqdesk_seo_fix_testFocusKw() {
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '
			var focuskw = jQuery.trim(jQuery("#yoast_wpseo_focuskw_'.$q_config['enabled_languages'][$i].'").val());
			focuskw = focuskw.toLowerCase();
			var postname = jQuery("#editable-post-name-full").text();
			var url = wpseo_permalink_template.replace("%postname%", postname).replace("http://","");
			p = new RegExp("(^|[ \s\n\r\t\.,\'\(\"\+;!?:\-])" + focuskw + "($|[ \s\n\r\t.,\'\)\"\+!?:;\-])","gim");
			var focuskwNoDiacritics = removeLowerCaseDiacritics(focuskw);
			p2 = new RegExp(focuskwNoDiacritics.replace(/\s+/g, "[-_\\\//]"),"gim");
			var metadesc = jQuery("#yoast_wpseo_metadesc_'.$q_config['enabled_languages'][$i].'").val();
			if (metadesc == "")
				metadesc = jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .desc").text();
			if (focuskw != "") {
				var html = "<p>" + wpseoMetaboxL10n.keyword_header + "<br />";
				html += wpseoMetaboxL10n.article_header_text + ptest(iqdesk_seo_fix_prepare_text(jQuery("#title").val(),"'.$q_config['enabled_languages'][$i].'"), p) + "<br/>";
				html += wpseoMetaboxL10n.page_title_text + ptest(jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .title").text(), p) + "<br/>";
				html += wpseoMetaboxL10n.page_url_text + ptest(url, p2) + "<br/>";
				html += wpseoMetaboxL10n.content_text + ptest(iqdesk_seo_fix_prepare_text(jQuery("#content").val(),"'.$q_config['enabled_languages'][$i].'"), p) + "<br/>";
				html += wpseoMetaboxL10n.meta_description_text + ptest(metadesc, p);
				html += "</p>";
				jQuery("#focuskwresults_'.$q_config['enabled_languages'][$i].'").html(html);
			} else {
				jQuery("#focuskwresults_'.$q_config['enabled_languages'][$i].'").html("");
			}
			';
		}
		echo '
		}		
		function iqdesk_seo_fix_updateTitle(force) {
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '		
			if (jQuery("#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'").val()) {
				var title = jQuery("#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'").val();
			} else {
				var title = wpseo_title_template.replace("%%title%%", iqdesk_seo_fix_prepare_text(jQuery("#title").val(),"'.$q_config['enabled_languages'][$i].'"));
				title = jQuery("<div />").html(title).text();
			}
			if (title == "") {
				jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .title").html("");
				jQuery("#yoast_wpseo_title-length_'.$q_config['enabled_languages'][$i].'").html("");
				return;
			}
			title = yst_clean(title);
			title = jQuery.trim(title);
			var original_title = title;
			title = jQuery("<div />").text(title).html();
			if (force) {
				jQuery("#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'").val(title);
			} else {
				original_title = jQuery("<div />").html(original_title).text();
				jQuery("#yoast_wpseo_title_'.$q_config['enabled_languages'][$i].'").attr("placeholder", original_title);
			}
			var len = 70 - title.length;
			if (title.length > 70) {
				var space = title.lastIndexOf(" ", 67);
				title = title.substring(0, space).concat(" <strong>...</strong>");
			}
			if (len < 0)
				len = "<span class=\'wrong\'>" + len + "</span>";
			else
				len = "<span class=\'good\'>" + len + "</span>";
			title = boldKeywords(title, false);
			jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .title").html(title);
			jQuery("#yoast_wpseo_title-length_'.$q_config['enabled_languages'][$i].'").html(len);
			iqdesk_seo_fix_testFocusKw();
			';
		}
		echo '
		}	
		function iqdesk_seo_fix_updateDesc(desc) {
		';
		for($i=0;$i<count($q_config['enabled_languages']);$i++){
			echo '				
			var autogen = false;
			var desc = jQuery.trim(yst_clean(jQuery("#yoast_wpseo_metadesc_'.$q_config['enabled_languages'][$i].'").val()));
			var color = "#000";
			if (desc == "") {
				if (wpseo_metadesc_template != "") {
					var excerpt = yst_clean(iqdesk_seo_fix_prepare_text(jQuery("#excerpt").val(),"'.$q_config['enabled_languages'][$i].'"));
					desc = wpseo_metadesc_template.replace("%%excerpt_only%%", excerpt);
					desc = desc.replace("%%excerpt%%", excerpt);
					desc = jQuery("<div />").html(desc).text();
				}
				desc = jQuery.trim(desc);
				if (desc == "") {
					desc = iqdesk_seo_fix_prepare_text(jQuery("#content").val(),"'.$q_config['enabled_languages'][$i].'");
					desc = yst_clean(desc);
					var focuskw = jQuery.trim(jQuery("#yoast_wpseo_focuskw_'.$q_config['enabled_languages'][$i].'").val());
					if (focuskw != "") {
						var descsearch = new RegExp(focuskw,"gim");
						if (desc.search(descsearch) != -1 && desc.length > wpseo_meta_desc_length) {
							desc = desc.substr(desc.search(descsearch), wpseo_meta_desc_length);
						} else {
							desc = desc.substr(0, wpseo_meta_desc_length);
						}
					} else {
						desc = desc.substr(0, wpseo_meta_desc_length);
					}
					var color = "#888";
					autogen = true;
				}
			}
			desc = jQuery("<div />").text(desc).html();
			desc = yst_clean(desc);
			if (!autogen)
				var len = wpseo_meta_desc_length - desc.length;
			else
				var len = wpseo_meta_desc_length;
			if (len < 0)
				len = "<span class=\'wrong\'>" + len + "</span>";
			else
				len = "<span class=\'good\'>" + len + "</span>";
			if (autogen || desc.length > wpseo_meta_desc_length) {
				if (desc.length > wpseo_meta_desc_length)
					var space = desc.lastIndexOf(" ", ( wpseo_meta_desc_length - 3 ));
				else
					var space = wpseo_meta_desc_length;
				desc = desc.substring(0, space).concat(" <strong>...</strong>");
			}
			desc = boldKeywords(desc, false);
			jQuery("#yoast_wpseo_metadesc-length_'.$q_config['enabled_languages'][$i].'").html(len);
			jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .desc span.content").css("color", color);
			jQuery("#wpseosnippet_'.$q_config['enabled_languages'][$i].' .desc span.content").html(desc);
			iqdesk_seo_fix_testFocusKw();
			';
		}
		echo '			
		}		
		function iqdesk_seo_fix_prepare_text(text,lang){
			if (text.indexOf("<!--:-->")!=-1) {
				var regex = new RegExp("<!--:"+lang+"-->((.|[\r\n])*?)<!--:-->","gim");
				match = regex.exec(text);	
				if (!!match) {
					return match[1];
				} else {
					return "";
				}
			} else {
				return text;
			}
		}
		</script>
		<style>
		[id*="wpseosnippet"] {
			margin: 0 0 10px 0;
			padding: 0 5px;
			font-family: arial, sans-serif;
			line-height: 15px !important;
			font-size: 13px !important;
			font-style: normal;
			width: auto;
			max-width: 520px;
		}

		[id*="wpseosnippet"] td {
			padding: 0;
			margin: 0;
		}

		[id*="wpseosnippet"] cite.url {
			font-weight: normal;
			font-style: normal;
		}

		[id*="wpseosnippet"] a {
			text-decoration: none;
		}

		[id*="wpseosnippet"] .title {
			color: #11c;
			font-size: 16px !important;
			line-height: 19px;
			text-decoration: underline;
		}

		[id*="wpseosnippet"] .desc {
			color: #000;
		}

		[id*="wpseosnippet"] .url {
			color: #093;
			font-size: 13px;
		}

		[id*="wpseosnippet"] .meta {
			color: #767676;
		}

		[id*="wpseosnippet"] .util {
			color: #4272DB;
		}

		[id*="wpseosnippet"] p {
			margin: 0 !important;
		}

		[id*="wpseosnippet"] a:hover {
			text-decoration: underline;
		}

		[id*="wpseosnippet"] {
			margin-bottom: 10px;
		}
		</style>
		';
	}
}
?>