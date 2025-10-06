<?php
variables([
	'link-to-section-home' => true,
	'social' => [
		[ 'type' => 'youtube', 'url' => 'https://www.youtube.com/@sinelanguage3417', 'name' => 'Govind YT' ],
	],
	'footer-variation' => 'no-widget',
	//
	'no-seo-info' => true,
	'no-search' => true,
	'sub-theme' => 'academic',
	//'no-header' => true,
	'skip-directory' => true,
]);

function site_before_render() {
	runFeature('engage'); //needed for floating button
	variable('htmlReplaces', [
		'Govind' => '<span class="h5 cursive">' . variable('owned-by') . '</span>',
		'SiteName' => '<span class="h5 cursive">' . variable('name') . '</span>',
	]);
}

function enrichThemeVars($vars, $what) {
	if ($what == 'header') {
		$vars['logo'] = '';
		$vars['person-name'] = variable('owned-by');
		$vars['person-title'] = variable('byline');
		$vars['person-image'] = assetUrl('govindaraj-logo.png', SITEASSETS);

		//TODO: why! setMenuSettings();
		$vars['one-page-menu'] = menu('/', [
			'return' => true,
			'link-to-home' => true,
			'files' => variable('sections'),
		]);
		//setMenuSettings(false);

		$links = []; appendSocial(variable('social'), $links);
		$vars['bottom-menu'] = implode(NEWLINE, $links);
	}

	return $vars;
}

function before_file() {
	sectionId('file', 'container pt-5');
	contentBox('file-cb', '');

	printSpacer(replaceHtml((nodeIs(SITEHOME) ? '%siteName%' : '%section_r%' )
		. ', by ' . variable('owned-by')));
}

function after_file() {
	contentBox('end');
	section('end');

	if (in_array(sectionValue(), ['coffee', 'consulting'])) return;
	sectionId('media', 'also-content p-5');
	contentBox('media-cb', 'w-50 container');
	echo returnLine('%media-corecodesnippet%');
	contentBox('end');
	section('end');
}


function did_site_render_page() {
	$file = variable('file');
	if (!$file && $section = sectionValue()) {
		variable('file', $file = SITEPATH . '/' . $section . '/home.md');
		renderAny($file);
		return true;
	}
	return false;
}
