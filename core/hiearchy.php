<?php
global $LoggedIn, $Path;

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
				array("module" => "admin/gadget/show")
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
			"Scripts" => array(
				array("assets/admin/style/script/application-modules/pagelist.js")
			),
			"Content" => array(
				"main" => array(
					array("module" => "admin/page/controls"),
					array("module" => "admin/page/api/form.loader", "class" => "collapse page-form-element", "dependencies" => array("admin/page/folders")),					
					array("module" => "admin/project/api/form.loader", "class" => "collapse project-form-element", "dependencies"=> array("admin/page/folders")),
					array("module" => "admin/page/api/folders.loader"),
					array("module" => "admin/project/api/form.quickedit.loader", "dependencies" => array("admin/page/folders"))					
				)
			)
		),

		"/admin/api-test/" => array(
			"Config" => array(
				"Title" => "{_'pages_api_test_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 23
					)
				)
			),
			"Scripts" => array(
				array("assets/admin/style/script/application-modules/pagelist.js")
			),
			"Content" => array(
				"main" => array(
					array("module" => "admin/page/controls"),
					array("module" => "admin/page/api/form.loader", "class" => "collapse page-form-element", "dependencies" => array("admin/page/folders")),
					array("module" => "admin/project/form", "class" => "collapse project-form-element"),
					array("module" => "admin/page/api/folders.loader")
				)
			)
		),

		"/admin/projects/" => array(
			"Config" => array(
				"Title" => "{_'pages_projects_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 25
					)
				)
			),
			"Scripts" => array(
				array("assets/admin/style/script/application-modules/projectlist.js")
			),
			"Content" => array(
				"main" => array(
					array("module" => "admin/project/controls"),
					array("module" => "admin/project/api/form.loader", "class" => "collapse project-form-element", "dependencies" => array("admin/project/list")),
					array("module" => "admin/project/api/list.loader")
				)
			),

			"/admin/projects/edit/*/" => array(
				"Config" => array(
					"Title" => "{_'pages_projects_edit_title'}"
				),
				"Scripts" => array(
					array("assets/admin/style/script/application-modules/workflow.js"),
					array("assets/admin/style/script/application-modules/projectdetails.js"),
					array("assets/admin/style/script/application-modules/pagelistflat.js")
				),
				"Content" => array(
					"main" => array(
						array("module" => "admin/project/api/workflow.loader", "dependencies" => array("admin/project/details")),
						array("module" => "admin/project/api/details.loader", "projectAuth" => true),
						array("module" => "admin/page/api/pages.loader", "projectAuth" => true),
						array("module" => "admin/project/api/form.loader", "class" => "collapse project-form-element", "type" => "edit", "message" => 0, "dependencies" => array("admin/project/details", "admin/page/pages"))
					)
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
					array("module" => "admin/template/api/controls.loader", "dependencies" => array()),
					array("module" => "admin/template/api/list.loader")
				)
			)
		),

		"/admin/layouts/" => array(
			"Config" => array(
				"Title" => "{_'pages_layouts_title'}",
				"NavigationShow" => array(
					"RightNavigation" => array(
						"Visible" => true,
						"Weight" => 35
					)
				)
			),
			"Scripts" => array(
				array(
					"assets/admin/style/script/application-modules/layoutlist.js"/*,
					"assets/admin/style/script/application-modules/layoutform.create.js"*/
				)
			),
			"Content" => array(
				"main" => array(
					array("module" => "admin/layout/api/controls.loader"),
					array("module" => "admin/layout/api/form.loader", "class" => "collapse layout-form-element", "dependencies" => array("admin/layout/list")),					
					array("module" => "admin/layout/api/list.loader"),
					array("module" => "admin/layout/api/delete.loader", "dependencies" => array("admin/layout/list", "admin/layout/api/form"))
				)
			),

			"/admin/layouts/edit/*/" => array(
				"Config" => array(
					"Title" => "{_'pages_layouts_edit_title'}"
				),
				"Scripts" => array(
					array(
						"assets/admin/style/script/application-modules/layoutdetail.js",
						"assets/admin/style/script/application-modules/layoutform.slot.js"
					)
				),
				"Content" => array(
					"main" => array(
						array("module" => "admin/layout/api/controls.detail.loader"),
						array("module" => "admin/layout/api/slots.loader"),
						array("module" => "admin/layout/api/form.loader", "class" => "collapse project-form-element", "type" => "edit", "message" => 0),
						array("module" => "admin/layout/api/delete.slot.loader", "dependencies" => array("admin/layout/slots")),
						array("module" => "admin/layout/api/form.slot.loader", "dependencies" => array("admin/layout/slots"))				
					)
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
		),

		"/admin/404/" => array(
			"Config" => array(
				"Title" => "{_'pages_404_title'}"
			),
			"Content" => array(
				"main" => array(

				)
			)
		)
	)
);

?>