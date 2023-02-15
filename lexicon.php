<?php

define("IN_MYBB", 1);
define('THIS_SCRIPT', 'lexicon.php');

require_once "./global.php";
$lang->load('lexicon');

add_breadcrumb($lang->lexicon, "lexicon.php");

$mybb->input['action'] = $mybb->get_input('action');

// Breadcrump Navigation
switch($mybb->input['action'])
{
    case "list":
        add_breadcrumb($lang->lexicon_list);
        break;
    case "view":
        add_breadcrumb($lang->lexicon_view_entry);
        break;
}

// Generate navigation
$alphabet = range('A', 'Z');
array_push($alphabet, "Ä", "Ö", "Ü");
foreach($alphabet as $letter) {
    eval("\$menu_bit .= \"".$templates->get("lexicon_nav_bit")."\";"); 
}

eval("\$menu = \"".$templates->get("lexicon_nav")."\";");

// Search for categories
$select_category = "";
$query_cats = $db->simple_select("lexicon_categories", "*", "",
    ["order_by" => 'name', "order_dir" => 'ASC']);
if($db->num_rows($query_cats) > 0) {
    while($categories = $db->fetch_array($query_cats)) {
        $categories_bit .= "<option value=\"{$categories['lcid']}\">{$categories['name']}</option>";
    }
	$select_category = "<select name=\"category\"><option value=\"\">{$lang->lexicon_select_cat}</option>{$categories_bit}</select>";
}

// Landing Page
if(!$mybb->input['action'])
{

    eval("\$page = \"".$templates->get("lexicon")."\";");
    output_page($page);
}

// Entry List
if($mybb->input['action'] == "list") {

    $letter = $db->escape_string($mybb->get_input('letter'));
    $cat = (int)$mybb->get_input('category');
    $keyword = $db->escape_string($mybb->get_input('keyword'));

    if(!$letter) {
        $letter = '%';
    }
    if(!$cat) {
        $cat = '%';
    }
	if(!$keyword || $keyword == $lang->lexicon_search_word) {
        $keyword = '%';
    }

    // Format Entries
    require_once MYBB_ROOT."inc/class_parser.php";
    $parser = new postParser;
    $parser_options = array(
        "allow_html" => 1,
        "allow_mycode" => 1,
        "allow_smilies" => 1,
        "allow_imgcode" => 1
    );

    $entry_bit = "";
    $query = $db->simple_select("lexicon_entries", "*", "name LIKE '{$letter}%' AND name LIKE '%{$keyword}%' AND lcid LIKE '%{$cat}%'",
    ["order_by" => 'name', "order_dir" => 'ASC']);
    if($db->num_rows($query) > 0) {
        while($entry = $db->fetch_array($query)) {
			$entry['category'] = $db->fetch_field($db->simple_select("lexicon_categories", "name", "lcid = '{$entry['lcid']}'"), "name");
            $entry['text'] = $parser->parse_message($entry['text'], $parser_options);
            eval("\$entry_bit .= \"".$templates->get("lexicon_list_bit")."\";");  
        }
    } else { eval("\$entry_bit = \"".$templates->get("lexicon_list_none")."\";");  }

    eval("\$page = \"".$templates->get("lexicon_list")."\";");
    output_page($page);   
}

?>
