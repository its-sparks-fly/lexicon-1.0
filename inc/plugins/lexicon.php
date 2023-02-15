<?php

// Disallow direct access to this file for security reasons
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}


function lexicon_info()
{
    global $mybb, $lang;
    $lang->load('lexicon');

    // Plugin Info
    $lexicon = [
        "name" => $lang->lexicon_name,
        "description" => $lang->lexicon_short_desc,
        "website" => "https://github.com/its-sparks-fly",
        "author" => "sparks fly",
        "authorsite" => "https://github.com/its-sparks-fly",
        "version" => "1.0",
        "compatibility" => "18*"
    ];

    return $lexicon;
}

function lexicon_install()
{
    global $db;

    // Catch potential errors [duplicates]
    $tables = [
        "lexicon_categories", "lexicon_entries"
    ];

    foreach($tables as $table) {
        if ($db->table_exists($table)) {
            $db->drop_table($table);
        }
    }


    $collation = $db->build_create_table_collation();

    $db->write_query("
        CREATE TABLE ".TABLE_PREFIX."lexicon_categories (
            `lcid` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (lcid)
        ) ENGINE=MyISAM{$collation};
    ");

    $db->write_query("
        CREATE TABLE ".TABLE_PREFIX."lexicon_entries (
            `leid` int(10) unsigned NOT NULL auto_increment,
            `lcid` int(10) NOT NULL,
            `name` varchar(255) NOT NULL DEFAULT '',
            `text` text NOT NULL,
            PRIMARY KEY (leid)
        ) ENGINE=MyISAM{$collation};
    ");

}

function lexicon_is_installed()
{
    global $db;

    if ($db->table_exists("lexicon_categories") && $db->table_exists("lexicon_entries")) {
        return true;
    } else {
        return false;
    }
}

function lexicon_uninstall() 
{
    global $db;

    $tables = [
        "lexicon_categories", "lexicon_entries"
    ];
    
    foreach($tables as $table) {
        if($db->table_exists($table)) {
            $db->drop_table($table);
        }
    }

}

function lexicon_activate() 
{
	global $db;
	
    $lexicon = [
        'title'        => 'lexicon',
        'template'    => $db->escape_string('<html>
		<head>
		<title>{$mybb->settings[\'bbname\']} - {$lang->lexicon}</title>
		{$headerinclude}</head>
		<body>
		{$header}
			<table width="100%" cellspacing="5" cellpadding="5">
				<tr>
					{$menu}
					<td valign="top" class="trow1">
						<div class="thead" align="right">
							<form method="get" id="lexicon_get">
								<input type="hidden" name="action" value="list" />
									Suche: {$select_category} <input type="text" name="keyword" value="{$lang->lexicon_search_word}" onfocus="this.value=\'\'" class="textarea" /> <input type="submit"  value="{$lang->lexicon_search}"  class="button" /> 
								</form>
						</div>
						<div style="text-align: justify; width: 70%; margin: 20px auto;">
							{$lang->lexicon_start}
						</div>
					</td>
				</tr>
			</table>
			</div>
		{$footer}
		</body>
		</html>'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon);   
    
    $lexicon_list = [
        'title'        => 'lexicon_list',
        'template'    => $db->escape_string('<html>
		<head>
		<title>{$mybb->settings[\'bbname\']} - {$lang->lexicon}</title>
		{$headerinclude}
		</head>
		<body>
		{$header}
		<div>
			<table width="100%" cellspacing="5" cellpadding="5">
				<tr>
					{$menu}
					<td valign="top" class="trow1">
						<div class="thead" align="right">
							<form method="get" id="randomnames">
								<input type="hidden" name="action" value="list" />
								<input type="hidden" name="letter" value="{$letter}" />
									Suche: {$select_category} <input type="text" name="keyword" value="{$lang->lexicon_search_word}" onfocus="this.value=\'\'" class="textarea" /> <input type="submit"  value="{$lang->lexicon_search}"  class="button" /> 
								</form>
						</div>
						<br />
						{$entry_bit}
					</td>
				</tr>
			</table>
			</div>
		{$footer}
		</body>
		</html>'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon_list);    

    $lexicon_list_bit = [
        'title'        => 'lexicon_list_bit',
        'template'    => $db->escape_string('<table class="tborder" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" style="width: 90%;">
        <tr>
            <td class="tcat">{$entry[\'name\']} &raquo; {$entry[\'category\']}</td>
        </tr>
        <tr>
            <td class="trow2" align="justify">
                {$entry[\'text\']}
            </td>
        </tr>
    </table><br />'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon_list_bit);   
    
    $lexicon_list_none = [
        'title'        => 'lexicon_list_none',
        'template'    => $db->escape_string('<center>
        <strong>
            {$lang->lexicon_none}
        </strong>
    </center>'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon_list_none);   

    $lexicon_nav = [
        'title'        => 'lexicon_nav',
        'template'    => $db->escape_string('<td width="20%">
        <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
        <tbody>
            <tr>
                <td class="thead"><strong><a href="lexicon.php">{$lang->lexicon}</a></strong></td>
            </tr>
            {$menu_bit}
        </tbody>
        </table>
    </td>'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon_nav);  

    $lexicon_nav_bit = [
        'title'        => 'lexicon_nav_bit',
        'template'    => $db->escape_string('<tr>
		<td class="trow1 smalltext"><a href="lexicon.php?action=list&letter={$letter}"> {$letter} {$lang->lexicon_as}</a></td>
</tr>'),
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    ];
    $db->insert_query("templates", $lexicon_nav_bit);  

}

function lexicon_deactivate()
{
	global $db;
	$db->delete_query("templates", "title LIKE '%lexicon%'");
}

// ACP Action Handler
$plugins->add_hook("admin_config_action_handler", "lexicon_admin_config_action_handler");
function lexicon_admin_config_action_handler(&$actions)
{
    $actions['lexicon'] = array('active' => 'lexicon', 'file' => 'lexicon');
}

// ACP Permissions
$plugins->add_hook("admin_config_permissions", "lexicon_admin_config_permissions");
function lexicon_admin_config_permissions(&$admin_permissions)
{
    global $lang;
    $lang->load('lexicon');

    $admin_permissions['lexicon'] = $lang->lexicon_permission;
}

// ACP menu 
$plugins->add_hook("admin_config_menu", "lexicon_admin_config_menu");
function lexicon_admin_config_menu(&$sub_menu)
{
    global $mybb, $lang;
    $lang->load('lexicon');
    
    $sub_menu[] = [
        "id" => "lexicon",
        "title" => $lang->lexicon_manage,
        "link" => "index.php?module=config-lexicon"
    ];
}

$plugins->add_hook("admin_load", "lexicon_manage_lexicon");
function lexicon_manage_lexicon() 
{
    global $mybb, $db, $lang, $page, $run_module, $action_file;
    $lang->load('lexicon');

    if ($page->active_action != 'lexicon') {
        return false;
    }

    if ($run_module == 'config' && $action_file == 'lexicon') {

        $folderprefix = "";
        if($mybb->version_code <= 1820) {
            $folderprefix = "editor_";
        }

        // Lexicon Overview
        if ($mybb->input['action'] == "" || !isset($mybb->input['action'])) {

            // Add to page navigation
            $page->add_breadcrumb_item($lang->lexicon_manage);

            // Build options header
            $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_entries);
            $sub_tabs['lexicon'] = [
                "title" => $lang->lexicon_manage_overview_entries,
                "link" => "index.php?module=config-lexicon",
                "description" => $lang->lexicon_manage_overview_entries_desc
                
            ];
            $sub_tabs['lexicon_cats'] = [
                "title" => $lang->lexicon_manage_overview_cats,
                "link" => "index.php?module=config-lexicon&amp;action=cats",
                "description" => $lang->lexicon_manage_overview_cats_desc
                
            ];
            $sub_tabs['lexicon_entry_add'] = [
                "title" => $lang->lexicon_manage_add_entry,
                "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                "description" => $lang->lexicon_manage_add_entry_desc
            ];
            $sub_tabs['lexicon_cat_add'] = [
                "title" => $lang->lexicon_manage_add_cat,
                "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                "description" => $lang->lexicon_manage_add_cat_desc
            ];

            $page->output_nav_tabs($sub_tabs, 'lexicon');

            // Show errors
            if (isset($errors)) {
                $page->output_inline_error($errors);
            }

            // Build the overview
            $form = new Form("index.php?module=config-lexicon", "post");

            $form_container = new FormContainer($lang->lexicon_manage_edit_entry);
            $form_container->output_row_header($lang->lexicon_manage_title);
            $form_container->output_row_header($lang->lexicon_manage_cat);
            $form_container->output_row_header("<div style=\"text-align: center;\">".$lang->lexicon_manage_options."</div>");

            // Get all entries
            $query = $db->simple_select("lexicon_entries", "*", "",
                ["order_by" => 'name', 'order_dir' => 'ASC']);
 
            while($lexicon_entries = $db->fetch_array($query)) {

                // Get category
                $cat = $db->simple_select("lexicon_categories", "name", "lcid='{$lexicon_entries['lcid']}'");
                $cat_name = $db->fetch_field($cat, "name");

                $form_container->output_cell('<strong>'.htmlspecialchars_uni($lexicon_entries['name']).'</strong>');
                $form_container->output_cell(htmlspecialchars_uni($cat_name));
                $popup = new PopupMenu("lexicon_{$lexicon_entries['leid']}", $lang->lexicon_manage_edit);
                $popup->add_item(
                    $lang->lexicon_manage_edit,
                    "index.php?module=config-lexicon&amp;action=edit_entry&amp;leid={$lexicon_entries['leid']}"
                );
                $popup->add_item(
                    $lang->lexicon_manage_delete,
                    "index.php?module=config-lexicon&amp;action=delete_entry&amp;leid={$lexicon_entries['leid']}"
                    ."&amp;my_post_key={$mybb->post_code}"
                );
                $form_container->output_cell($popup->fetch(), array("class" => "align_center"));
                $form_container->construct_row();
            }

            $form_container->end();
            $form->end();
            $page->output_footer();

            exit;
        }

        if ($mybb->input['action'] == "cats") {

            // Add to page navigation
            $page->add_breadcrumb_item($lang->lexicon_manage);

            // Build options header
            $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_cats);
            $sub_tabs['lexicon'] = [
                "title" => $lang->lexicon_manage_overview_entries,
                "link" => "index.php?module=config-lexicon",
                "description" => $lang->lexicon_manage_overview_entries_desc
                
            ];
            $sub_tabs['lexicon_cats'] = [
                "title" => $lang->lexicon_manage_overview_cats,
                "link" => "index.php?module=config-lexicon&amp;action=cats",
                "description" => $lang->lexicon_manage_overview_cats_desc
                
            ];
            $sub_tabs['lexicon_entry_add'] = [
                "title" => $lang->lexicon_manage_add_entry,
                "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                "description" => $lang->lexicon_manage_add_entry_desc
            ];
            $sub_tabs['lexicon_cat_add'] = [
                "title" => $lang->lexicon_manage_add_cat,
                "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                "description" => $lang->lexicon_manage_add_cat_desc
            ];

            $page->output_nav_tabs($sub_tabs, 'lexicon_cats');

            // Show errors
            if (isset($errors)) {
                $page->output_inline_error($errors);
            }

            // Build the overview
            $form = new Form("index.php?module=config-lexicon&amp;action=cats", "post");

            $form_container = new FormContainer($lang->lexicon_manage_edit_cat);
            $form_container->output_row_header($lang->lexicon_manage_title);
            $form_container->output_row_header("<div style=\"text-align: center;\">".$lang->lexicon_manage_options."</div>");

            // Get all entries
            $query = $db->simple_select("lexicon_categories", "*", "",
                ["order_by" => 'name', 'order_dir' => 'DESC']);
 
            while($lexicon_categories = $db->fetch_array($query)) {

                $form_container->output_cell('<strong>'.htmlspecialchars_uni($lexicon_categories['name']).'</strong>');
                $popup = new PopupMenu("lexicon_{$lexicon_categories['lcid']}", $lang->lexicon_manage_edit);
                $popup->add_item(
                    $lang->lexicon_manage_edit,
                    "index.php?module=config-lexicon&amp;action=edit_cat&amp;lcid={$lexicon_categories['lcid']}"
                );
                $popup->add_item(
                    $lang->lexicon_manage_delete,
                    "index.php?module=config-lexicon&amp;action=delete_cat&amp;lcid={$lexicon_categories['lcid']}"
                    ."&amp;my_post_key={$mybb->post_code}"
                );
                $form_container->output_cell($popup->fetch(), array("class" => "align_center"));
                $form_container->construct_row();
            }

            $form_container->end();
            $form->end();
            $page->output_footer();

            exit;
        }

        if ($mybb->input['action'] == "add_cat") {
            if ($mybb->request_method == "post") {
                // Check if required fields are not empty
                if (empty($mybb->input['name'])) {
                    $errors[] = $lang->lexicon_manage_error_no_title;
                }

                // No errors - insert the lexicon
                if (empty($errors)) {

                    $new_cat = [
                        "name" => $db->escape_string($mybb->input['name'])
                    ];

                    $db->insert_query("lexicon_categories", $new_cat);

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_cat_added;
                    log_admin_action(htmlspecialchars_uni($mybb->input['name']));

                    flash_message($lang->lexicon_manage_cat_added, 'success');
                    admin_redirect("index.php?module=config-lexicon&amp;action=cats");
                }

            }

            $page->add_breadcrumb_item($lang->lexicon_manage_add_cat);

            // Build options header
            $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_cats);
            $sub_tabs['lexicon'] = [
                "title" => $lang->lexicon_manage_overview_entries,
                "link" => "index.php?module=config-lexicon",
                "description" => $lang->lexicon_manage_overview_entries_desc
                
            ];
            $sub_tabs['lexicon_cats'] = [
                "title" => $lang->lexicon_manage_overview_cats,
                "link" => "index.php?module=config-lexicon&amp;action=cats",
                "description" => $lang->lexicon_manage_overview_cats_desc
                
            ];
            $sub_tabs['lexicon_entry_add'] = [
                "title" => $lang->lexicon_manage_add_entry,
                "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                "description" => $lang->lexicon_manage_add_entry_desc
            ];
            $sub_tabs['lexicon_cat_add'] = [
                "title" => $lang->lexicon_manage_add_cat,
                "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                "description" => $lang->lexicon_manage_add_cat_desc
            ];

            $page->output_nav_tabs($sub_tabs, 'lexicon_cat_add');     
            
            // Show errors
            if (isset($errors)) {
                $page->output_inline_error($errors);
            }

            // Build the form
            $form = new Form("index.php?module=config-lexicon&amp;action=add_cat", "post", "", 1);

            $form_container = new FormContainer($lang->lexicon_manage_add_cat);
            $form_container->output_row(
                $lang->lexicon_manage_title.'<em>*</em>',
                $lang->lexicon_manage_cat_name_desc,
                $form->generate_text_box('name', $mybb->input['name'])
            );

            $form_container->end();
            $buttons[] = $form->generate_submit_button($lang->lexicon_manage_submit);
            $form->output_submit_wrapper($buttons);
            $form->end();
            $page->output_footer();

            exit;
        }

        if ($mybb->input['action'] == "edit_cat") { 
            if ($mybb->request_method == "post") {
                // Check if required fields are not empty
                if (empty($mybb->input['name'])) {
                    $errors[] = $lang->lexicon_manage_error_no_title;
                }

                // No errors - insert the terms of use
                if (empty($errors)) {
                    $lcid = $mybb->get_input('lcid', MyBB::INPUT_INT);

                    $edited_cat = array(
                        "name" => $db->escape_string($mybb->input['name'])
                    );

                    $db->update_query("lexicon_categories", $edited_cat, "lcid='{$lcid}'");

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_cat_edited;
                    log_admin_action(htmlspecialchars_uni($mybb->input['name']));

                    flash_message($lang->lexicon_manage_cat_edited, 'success');
                    admin_redirect("index.php?module=config-lexicon&amp;action=cats");
                }
            }

            $page->add_breadcrumb_item($lang->lexicon_manage_edit_cat);

            // Build options header
            $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_cats);
            $sub_tabs['lexicon'] = [
                "title" => $lang->lexicon_manage_overview_entries,
                "link" => "index.php?module=config-lexicon",
                "description" => $lang->lexicon_manage_overview_entries_desc
                
            ];
            $sub_tabs['lexicon_cats'] = [
                "title" => $lang->lexicon_manage_overview_cats,
                "link" => "index.php?module=config-lexicon&amp;action=cats",
                "description" => $lang->lexicon_manage_overview_cats_desc
                
            ];
            $sub_tabs['lexicon_entry_add'] = [
                "title" => $lang->lexicon_manage_add_entry,
                "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                "description" => $lang->lexicon_manage_add_entry_desc
            ];
            $sub_tabs['lexicon_cat_add'] = [
                "title" => $lang->lexicon_manage_add_cat,
                "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                "description" => $lang->lexicon_manage_add_cat_desc
            ];

            $page->output_nav_tabs($sub_tabs, 'lexicon_cat_add');     
            
            // Show errors
            if (isset($errors)) {
                $page->output_inline_error($errors);
            }

            // Get the data
            $lcid = $mybb->get_input('lcid', MyBB::INPUT_INT);
            $query = $db->simple_select("lexicon_categories", "*", "lcid={$lcid}");
            $edit_cat = $db->fetch_array($query);

            // Build the form
            $form = new Form("index.php?module=config-lexicon&amp;action=edit_cat", "post", "", 1);
            echo $form->generate_hidden_field('lcid', $lcid);

            $form_container = new FormContainer($lang->lexicon_manage_edit_cat);
            $form_container->output_row(
                $lang->lexicon_manage_title.'<em>*</em>',
                $lang->lexicon_manage_cat_name_desc,
                $form->generate_text_box('name', htmlspecialchars_uni($edit_cat['name']))
            );
 
            $form_container->end();
            $buttons[] = $form->generate_submit_button($lang->lexicon_manage_submit);
            $form->output_submit_wrapper($buttons);
            $form->end();
            $page->output_footer();

            exit;         
        }  

       // Delete category
        if ($mybb->input['action'] == "delete_cat") {

            // Get data
            $lcid = $mybb->get_input('lcid', MyBB::INPUT_INT);
            $query = $db->simple_select("lexicon_categories", "*", "lcid={$lcid}");
            $del_cat = $db->fetch_array($query);

            // Error Handling
            if (empty($lcid)) {
                flash_message($lang->lexicon_manage_error_invalid, 'error');
                admin_redirect("index.php?module=config-lexicon&amp;action=cats");
            }

            // Cancel button pressed?
            if (isset($mybb->input['no']) && $mybb->input['no']) {
                admin_redirect("index.php?module=config-lexicon&amp;action=cats");
            }

            if (!verify_post_check($mybb->input['my_post_key'])) {
                flash_message($lang->invalid_post_verify_key2, 'error');
                admin_redirect("index.php?module=config-lexicon&amp;action=cats");
            }  // all fine
            else {
                if ($mybb->request_method == "post") {
                    
                    $db->delete_query("lexicon_categories", "lcid='{$lcid}'");

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_cat_deleted;
                    log_admin_action(htmlspecialchars_uni($del_cat['name']));

                    flash_message($lang->lexicon_manage_cat_deleted, 'success');
                    admin_redirect("index.php?module=config-lexicon&amp;action=cats");
                } else {
                    $page->output_confirm_action(
                        "index.php?module=config-lexicon&amp;action=delete_cat&amp;lcid={$lcid}",
                        $lang->lexicon_manage_delete
                    );
                }
            }
            exit;
        }

        if ($mybb->input['action'] == "add_entry") {
            if ($mybb->request_method == "post") {
                // Check if required fields are not empty
                if (empty($mybb->input['name'])) {
                    $errors[] = $lang->lexicon_manage_error_no_title;
                }
                if (empty($mybb->input['text'])) {
                    $errors[] = $lang->lexicon_manage_error_no_text;
                }

                // No errors - insert
                if (empty($errors)) {

                    $new_entry = array(
                        "lcid" => (int)$mybb->input['lcid'],
                        "name" => $db->escape_string($mybb->input['name']),
                        "text" => $db->escape_string($mybb->input['text'])
                    );

                    $db->insert_query("lexicon_entries", $new_entry);

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_entry_added;
                    log_admin_action(htmlspecialchars_uni($mybb->input['name']));

                    flash_message($lang->lexicon_manage_entry_added, 'success');
                    admin_redirect("index.php?module=config-lexicon");
                }
            }

                $page->add_breadcrumb_item($lang->lexicon_manage_add_entry);

                // Editor scripts
                $page->extra_header .= <<<EOF
                
<link rel="stylesheet" href="../jscripts/sceditor/{$folderprefix}themes/mybb.css" type="text/css" media="all" />
<script type="text/javascript" src="../jscripts/sceditor/jquery.sceditor.bbcode.min.js?ver=1805"></script>
<script type="text/javascript" src="../jscripts/bbcodes_sceditor.js?ver=1808"></script>
<script type="text/javascript" src="../jscripts/sceditor/{$folderprefix}plugins/undo.js?ver=1805"></script>
EOF;

                // Build options header
                $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_cats);
                $sub_tabs['lexicon'] = [
                    "title" => $lang->lexicon_manage_overview_entries,
                    "link" => "index.php?module=config-lexicon",
                    "description" => $lang->lexicon_manage_overview_entries_desc
                    
                ];
                $sub_tabs['lexicon_cats'] = [
                    "title" => $lang->lexicon_manage_overview_cats,
                    "link" => "index.php?module=config-lexicon&amp;action=cats",
                    "description" => $lang->lexicon_manage_overview_cats_desc
                    
                ];
                $sub_tabs['lexicon_entry_add'] = [
                    "title" => $lang->lexicon_manage_add_entry,
                    "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                    "description" => $lang->lexicon_manage_add_entry_desc
                ];
                $sub_tabs['lexicon_cat_add'] = [
                    "title" => $lang->lexicon_manage_add_cat,
                    "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                    "description" => $lang->lexicon_manage_add_cat_desc
                ];

                $page->output_nav_tabs($sub_tabs, 'lexicon_entry_add'); 

                // Show errors
                if (isset($errors)) {
                    $page->output_inline_error($errors);
                }

                // Build the form
                $form = new Form("index.php?module=config-lexicon&amp;action=add_entry", "post", "", 1);
                $form_container = new FormContainer($lang->lexicon_manage_add_entry);
                $form_container->output_row(
                    $lang->lexicon_manage_title."<em>*</em>",
                    $lang->lexicon_manage_entry_name_desc,
                    $form->generate_text_box('name', $mybb->input['name'])
                );

                $query = $db->simple_select("lexicon_categories", "lcid, name", "", 
                ["order_by" => "name", "order_dir" => "ASC"]);
                $categories = [];
                while($category = $db->fetch_array($query)) {
                    $categories[$category['lcid']] = $category['name'];
                }

                $form_container->output_row($lang->lexicon_manage_cat." <em>*</em>", "", $form->generate_select_box("lcid", $categories, $mybb->input['lcid'],["id" => "lcid"]), 'lcid');

                $text_editor = $form->generate_text_area('text', $mybb->input['text'], array(
                    'id' => 'text',
                    'rows' => '25',
                    'cols' => '70',
                    'style' => 'height: 450px; width: 75%'
                    )
                );

                $text_editor .= build_mycode_inserter('text');
                $form_container->output_row(
                    $lang->lexicon_manage_content. "<em>*</em>",
                    $lang->lexicon_manage_entry_title_desc,
                    $text_editor,
                    'text'
                );

                $form_container->end();
                $buttons[] = $form->generate_submit_button($lang->lexicon_manage_submit);
                $form->output_submit_wrapper($buttons);
                $form->end();
                $page->output_footer();
    
                exit;         
        }
        if ($mybb->input['action'] == "edit_entry") {
            if ($mybb->request_method == "post") {
                // Check if required fields are not empty
                if (empty($mybb->input['name'])) {
                    $errors[] = $lang->lexicon_manage_error_no_title;
                }
                if (empty($mybb->input['text'])) {
                    $errors[] = $lang->lexicon_manage_error_no_text;
                }

                // No errors - insert the terms of use
                if (empty($errors)) {
                    $leid = $mybb->get_input('leid', MyBB::INPUT_INT);

                    $edited_entry = [
                        "lcid" => (int)$mybb->input['lcid'],
                        "name" => $db->escape_string($mybb->input['name']),
                        "text" => $db->escape_string($mybb->input['text'])
                    ];

                    $db->update_query("lexicon_entries", $edited_entry, "leid='{$leid}'");

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_entry_edited;
                    log_admin_action(htmlspecialchars_uni($mybb->input['name']));

                    flash_message($lang->lexicon_manage_entry_edited, 'success');
                    admin_redirect("index.php?module=config-lexicon");
                }

            }
            
            $page->add_breadcrumb_item($lang->lexicon_manage_edit_entry);

            // Editor scripts
            $page->extra_header .= <<<EOF

<link rel="stylesheet" href="../jscripts/sceditor/{$folderprefix}themes/mybb.css" type="text/css" media="all" />
<script type="text/javascript" src="../jscripts/sceditor/jquery.sceditor.bbcode.min.js?ver=1805"></script>
<script type="text/javascript" src="../jscripts/bbcodes_sceditor.js?ver=1808"></script>
<script type="text/javascript" src="../jscripts/sceditor/{$folderprefix}plugins/undo.js?ver=1805"></script>
EOF;

            // Build options header
            $page->output_header($lang->lexicon_manage." - ".$lang->lexicon_manage_overview_cats);
            $sub_tabs['lexicon'] = [
                "title" => $lang->lexicon_manage_overview_entries,
                 "link" => "index.php?module=config-lexicon",
                "description" => $lang->lexicon_manage_overview_entries_desc
                 
            ];
            $sub_tabs['lexicon_cats'] = [
                 "title" => $lang->lexicon_manage_overview_cats,
                 "link" => "index.php?module=config-lexicon&amp;action=cats",
                 "description" => $lang->lexicon_manage_overview_cats_desc
                
            ];
            $sub_tabs['lexicon_entry_add'] = [
                "title" => $lang->lexicon_manage_add_entry,
                "link" => "index.php?module=config-lexicon&amp;action=add_entry",
                 "description" => $lang->lexicon_manage_add_entry_desc
             ];
            $sub_tabs['lexicon_cat_add'] = [
                 "title" => $lang->lexicon_manage_add_cat,
                "link" => "index.php?module=config-lexicon&amp;action=add_cat",
                "description" => $lang->lexicon_manage_add_cat_desc
            ];

            $page->output_nav_tabs($sub_tabs, 'lexicon'); 

            // Show errors
            if (isset($errors)) {
                $page->output_inline_error($errors);
            }

            // Get the data
            $leid = $mybb->get_input('leid', MyBB::INPUT_INT);
            $query = $db->simple_select("lexicon_entries", "*", "leid={$leid}");
            $edit_entry = $db->fetch_array($query);

            // Build the form
            $form = new Form("index.php?module=config-lexicon&amp;action=edit_entry", "post", "", 1);
            echo $form->generate_hidden_field('leid', $leid);

            $form_container = new FormContainer($lang->lexicon_manage_edit_entry);
            $form_container->output_row(
                $lang->lexicon_manage_title,
                $lang->lexicon_manage_entry_name_desc,
                $form->generate_text_box('name', htmlspecialchars_uni($edit_entry['name']))
            );

            $categories = [];
            $query_cats = $db->simple_select("lexicon_categories", "lcid, name", "",
            ["order_by" => "name", "order_dir" => "ASC"]);
			
            while($category = $db->fetch_array($query_cats))
            {
                $categories[$category['lcid']] = $category['name'];
            }

            $form_container->output_row($lang->lexicon_manage_cat." <em>*</em>", "", $form->generate_select_box("lcid", $categories, $mybb->input['lcid']), 'lcid');

            $text_editor = $form->generate_text_area('text', $edit_entry['text'], array(
                    'id' => 'text',
                    'rows' => '25',
                    'cols' => '70',
                    'style' => 'height: 450px; width: 75%'
                )
            );
            $text_editor .= build_mycode_inserter('text');
            $form_container->output_row(
                $lang->lexicon_manage_content,
                $lang->lexicon_manage_entry_title_desc,
                $text_editor,
                'text'
            );
 
            $form_container->end();
            $buttons[] = $form->generate_submit_button($lang->lexicon_manage_submit);
            $form->output_submit_wrapper($buttons);
            $form->end();
            $page->output_footer();

            exit;
        }
       // Delete category
       if ($mybb->input['action'] == "delete_entry") {
            // Get data
            $leid = $mybb->get_input('leid', MyBB::INPUT_INT);
            $query = $db->simple_select("lexicon_entries", "*", "leid={$leid}");
            $del_entry = $db->fetch_array($query);

            // Error Handling
            if (empty($leid)) {
                flash_message($lang->lexicon_manage_error_invalid, 'error');
                admin_redirect("index.php?module=config-lexicon");
            }

            // Cancel button pressed?
            if (isset($mybb->input['no']) && $mybb->input['no']) {
                admin_redirect("index.php?module=config-lexicon");
            }

            if (!verify_post_check($mybb->input['my_post_key'])) {
                flash_message($lang->invalid_post_verify_key2, 'error');
                admin_redirect("index.php?module=config-lexicon");
            }  // all fine
            else {
                if ($mybb->request_method == "post") {
                    
                    $db->delete_query("lexicon_entries", "leid='{$leid}'");

                    $mybb->input['module'] = "lexicon";
                    $mybb->input['action'] = $lang->lexicon_manage_entry_deleted;
                    log_admin_action(htmlspecialchars_uni($del_entry['name']));

                    flash_message($lang->lexicon_manage_entry_deleted, 'success');
                    admin_redirect("index.php?module=config-lexicon");
                } else {
                    $page->output_confirm_action(
                        "index.php?module=config-lexicon&amp;action=delete_entry&amp;leid={$leid}",
                        $lang->lexicon_manage_delete
                    );
                }
            }
            exit;
        }
    }
}

?>
