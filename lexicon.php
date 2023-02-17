<?php

define("IN_MYBB", 1);
define('THIS_SCRIPT', 'lexicon.php');

$templatelist = "lexicon_nav_bit, lexicon_nav, lexicon_list_bit, lexicon_list, lexicon";

require_once "./global.php";
$lang->load('lexicon');

add_breadcrumb($lang->lexicon, "lexicon.php");

$mybb->input['action'] = $mybb->get_input('action');

// Generate navigation
$alphabet = range('A', 'Z');
array_push($alphabet, "Ä", "Ö", "Ü");

$menu_bit = "";
foreach ($alphabet as $letter)
{
    $lang_lexicon_as = $lang->sprintf($lang->lexicon_as, $letter);
    eval("\$menu_bit .= \"" . $templates->get("lexicon_nav_bit") . "\";");
}

eval("\$menu = \"" . $templates->get("lexicon_nav") . "\";");

// Generate Cat Cache
$catcache = array();
$query = $db->simple_select("lexicon_categories", "*", "", array("order_by" => 'name', "order_dir" => 'ASC'));
while ($cats = $db->fetch_array($query))
{
    $catcache[(int)$cats['lcid']] = htmlspecialchars_uni($cats['name']);
}

// Search for categories
$select_category = "";
if (!empty($catcache))
{
    $select_category = "<select name=\"category\">";
    $select_category .= "<option value=\"\">{$lang->lexicon_select_cat}</option>";

    foreach ($catcache as $catid => $catname)
    {
        $select_category .= "<option value=\"{$catid}\">{$catname}</option>";
    }

    $select_category .= "</select>";
}

// Landing Page
if (!$mybb->input['action'])
{
    eval("\$page = \"" . $templates->get("lexicon") . "\";");
    output_page($page);
}

// Entry List
if ($mybb->input['action'] == "list")
{

    $letter = $db->escape_string($mybb->get_input('letter'));
    $cat = $mybb->get_input('category', MyBB::INPUT_INT);
    $keyword = $db->escape_string($mybb->get_input('keyword'));

    $where = "";

    if ((!empty($letter) && $letter != "%") || !empty($cat) || (!empty($keyword) && $keyword != $lang->lexicon_search_word))
    {
        $where = "1=1";
    }

    if (empty($letter) || $letter == "%")
    {
        add_breadcrumb($lang->sprintf($lang->lexicon_all));
        $lang->lexicon_like = "";
    }
    else
    {
        add_breadcrumb($lang->sprintf($lang->lexicon_as, $letter));
        $lang->lexicon_like = $lang->sprintf($lang->lexicon_like, $letter);
        $where .= " AND name LIKE '{$letter}%'";
    }

    if (!empty($cat))
    {
        $where .= " AND lcid = '{$cat}'";
    }

    if (!empty($keyword) && $keyword != $lang->lexicon_search_word)
    {
        $where .= " AND name LIKE '%{$keyword}%'";
    }

    // Format Entries
    require_once MYBB_ROOT . "inc/class_parser.php";
    $parser = new postParser;
    $parser_options = array(
        "allow_html" => 1,
        "allow_mycode" => 1,
        "allow_smilies" => 1,
        "allow_imgcode" => 1,
        "filter_badwords" => 1
    );

    $entry_bit = "";
    $query = $db->simple_select("lexicon_entries", "*", $where, array("order_by" => 'name', "order_dir" => 'ASC'));
    if ($db->num_rows($query) > 0)
    {
        while ($entry = $db->fetch_array($query))
        {
            if (!empty($catcache) && array_key_exists($entry['lcid'], $catcache))
            {
                $entry['category'] = $catcache[$entry['lcid']];
            }
            else
            {
                $entry['category'] = $db->fetch_field($db->simple_select("lexicon_categories", "name", "lcid = '{$entry['lcid']}'"), "name");
            }

            $entry['name'] = htmlspecialchars_uni($entry['name']);

            if (!empty($entry['category']))
            {
                $entry['name'] .= " &raquo; " . htmlspecialchars_uni($entry['category']);
            }

            $entry['text'] = $parser->parse_message($entry['text'], $parser_options);
            eval("\$entry_bit .= \"" . $templates->get("lexicon_list_bit") . "\";");
        }
    }
    else
    {
        eval("\$entry_bit = \"" . $templates->get("lexicon_list_none") . "\";");
    }

    eval("\$page = \"" . $templates->get("lexicon_list") . "\";");
    output_page($page);
}
