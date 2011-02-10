<?php
date_default_timezone_set('Europe/Warsaw');
class kompresuj {
	/*static function start($wersja) {
		$nr = api::get('Wpisz wersje', false);
		echo '	wpisales wersje: '.$nr;
		$ok = api::get('Ok? (T/n)', array('T', 't', '', 'N', 'n'));
		if($ok == 'T' OR $ok == '' OR $ok == 't') {
			generuj::generate($wersja, $nr);
		} else {
			generuj::start($wersja);
		}
	}
	static function generate($type, $nr) {
		generuj::generate_chrome($nr);
	}
	static function generate_chrome($nr) {
		global $chrome_manifest;
		mkdir('chrome_'.$nr);
		$dir = 'chrome_'.$nr.'/';
		$chrome_manifest['version'] = $nr;
		file_put_contents($dir.'manifest.json', json_encode($chrome_manifest));
	}*/
	static function js() {
		$files = array('main.js', 'funkcje.js', 'home.js', 'user_menu.js', 'rankingi.js', 'popup.js', 'browse.js', 'link.js',
					'thread.js', 'box.js', 'user.js', 'FusionCharts.js', 'tag.js', 'bwtabs.js', 'request.js', 'blipniecie.js', 'menu.js',
					'news.js', 'overlib.js', 'oblipworld.js');
		foreach($files as $f) {
			$fi = file_get_contents('blipworld.pl/www/js/'.$f);
			if(!$fi) {
				echo '	Blad oczytywania pliku '.$f."\n";
			}
			$body .= $fi."\n";
		}
		$put = file_put_contents('blipworld.pl/www/js/compressed.js', $body);
		exec('"C:\Program Files (x86)\Java\jre6\bin\java" -jar compiler.jar --js=blipworld.pl/www/js/compressed.js --js_output_file=blipworld.pl/www/js/compressed2.js');
		echo '	zakonczono kompresowanie'."\n";
		$get = file_get_contents('blipworld.pl/www/js/compressed2.js');
		unlink('blipworld.pl/www/js/compressed2.js');
		$put = '// Generated: '.time().' ('.date('Y-m-d H:i:s').')'."\n";
		$put .= '// fragmenty kodu pochodzą ze strony http://phpjs.org copyright Kevin van Zonneveld (http://kevin.vanzonneveld.net) i inni; pobrano 03.02.2011'."\n";
		$put .= '// wykorzystano bibliotekę overLIB; homepage: http://www.bosrup.com/web/overlib/; pobrano 03.02.2011'."\n";
		$put .= 'var loadedfromcompressed = true;'."\n".$get;
		$put = file_put_contents('blipworld.pl/www/js/compressed.js', $put);
		echo '	zapisano skopmresowane'."\n";
		main::start();
	}
	static function css() {
		$files = array('main.css', 'home.css', 'user_menu.css', 'rankingi.css', 'popup.css', 'browse.css', 'link.css', 'thread.css',
					'box.css', 'user.css', 'tag.css', 'bwtabs.css', 'blipniecie.css', 'blipniecie_skin.css', 'menu.css',
					'news.css', 'oblipworld.css');
		foreach($files as $f) {
			$fi = file_get_contents('blipworld.pl/www/css/'.$f);
			if(!$fi) {
				echo '	Blad oczytywania pliku '.$f."\n";
			}
			$body .= $fi."\n";
		}
		$put = file_put_contents('blipworld.pl/www/css/compressed.css', $body);
		exec('"C:\Program Files (x86)\Java\jre6\bin\java" -jar yuicompressor-2.4.2.jar blipworld.pl/www/css/compressed.css -o blipworld.pl/www/css/compressed2.css --charset utf-8');
		echo '	zakonczono kompresowanie'."\n";
		$get = file_get_contents('blipworld.pl/www/css/compressed2.css');
		unlink('blipworld.pl/www/css/compressed2.css');
		$put = '/* Generated: '.time().' ('.date('Y-m-d H:i:s').')*/'."\n".$get;
		$put = file_put_contents('blipworld.pl/www/css/compressed.css', $put);
		echo '	zapisano skopmresowane'."\n";
		main::start();
	}
	static function manifest() {
		echo '	generuje manifesty'."\n";
		$prefix = array(
		    array('dev_ssl', '/dev.blipworld.pl/'),
		    array('stable_ssl', '/blipworld.pl/'),
		    array('stable', '/')
		);
		$files = array('js/compressed.js', 'css/compressed.css', 'img/background.jpg', 'img/glass.png', 'img/avatar.png', 'img/logo.png');

		foreach($prefix as $p) {
			$m = 'CACHE MANIFEST'."\n\n".'# Generated: '.time().' ('.date('Y-m-d H:i:s').')'."\n\n".'CACHE:'."\n";
			foreach($files as $f) {
				$m .= $p[1].$f."\n";
			}
			$m .= "\n".'NETWORK:'."\n".'/'."\n".'*';
			$s = file_put_contents('blipworld.pl/www/'.$p[0].'.manifest', $m);
			if($s) {
				echo '	zapisano manifest '.$p[0].'.manifest'."\n";
			}
		}
		echo '	zakonczono generowanie'."\n";
		main::start();
	}
}
?>