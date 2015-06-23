<?php
global $LoggedIn;

$Hiearchy = array(
	"/admin/" => array(
		"Template" => "default",
		"Config" => array(
			"Title" => "{_'pages_admin_title'}",
			"NavigationShow" => array(
				"RightNavigation" => array(
					"Visible" => true,
					"Weight" => 10,
					"Title" => "Admin Homepage"
				)
			)
		),
		"Content" => array(
			"main" => array(
			)
		),

		"/admin/api/" => array(
			"Config" => array(),
			"Template" => "api",
			"Content" => array(
				"main" => array(
					array("module" => "admin/api/dispatcher")			
				)
			)
		),

		"/admin/pages/" => array(
			"Config" => array(
				"Title" => "{_'pages_pages_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 20
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),

		"/admin/templates/" => array(
			"Config" => array(
				"Title" => "{_'pages_templates_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 30
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),

		"/admin/stringtables/" => array(
			"Config" => array(
				"Title" => "{_'pages_stringtables_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 40
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),

		"/admin/catalog/" => array(
			"Config" => array(
				"Title" => "{_'pages_catalog_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 50
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),

		"/admin/tags/" => array(
			"Config" => array(
				"Title" => "{_'pages_tags_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 60
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),

		"/admin/settings/" => array(
			"Config" => array(
				"Title" => "{_'pages_settings_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 80
					)
				)
			),
			"Content" => array(
				"main" => array(
					array("module" => "admin/settings/categories")
				)
			),

			"/admin/settings/node-rights/" => array(
				"Config" => array(
					"Title" => "{_'pages_settings-node-rights_title'}"
				),
				"Content" => array(
					"main" => array(
						array("module" => "admin/settings/noderights")
					)
				)
			),

			"/admin/settings/module-rights/" => array(
				"Config" => array(
					"Title" => "{_'pages_settings-module-rights_title'}"
				),
				"Content" => array(
					"main" => array(
						array("module" => "admin/settings/modulerights")
					)
				)
			),

			"/admin/settings/api-rights/" => array(
				"Config" => array(
					"Title" => "{_'page_settings-api-rights_title'}"
				),
				"Content" => array(
					"main" => array(
						array("module" => "admin/settings/apirights")
					)
				)
			),			

			"/admin/settings/pages/" => array(
				"Config" => array(
					"Title" => "{_'pages_settings-pages_title'}"
				),
				"Content" => array(
					"main" => array(

					)
				)
			)						
		),

		"/admin/users/" => array(
			"Config" => array(
				"Title" => "{_'pages_users_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 70
					)
				)
			),
			"Content" => array(
				"main" => array(

				)
			)
		),												

		"/admin/login/" => array(
			"Template" => "login",
			"Config" => array(
				"Title" => "{_'pages_login_title'}"
			),
			"Content" => array(
				"main" => array(

				)
			)
		)
	)
);

?>