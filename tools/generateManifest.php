<?php

/* generate manifest. */

$basedir = realpath(dirname(__FILE__) . '/..');
$webapp = realpath($basedir . '/webapp');
$framework = realpath($basedir . '/lib');

$webapp = generateList($webapp);
$framework = generateList($framework);


//$manifest = array_merge($webapp, $framework);


//file_put_contents('lib/manifest.php', $result);

function generateList($directory)
{
	$manifest = array();
	
	$cur = new RecursiveDirectoryIterator($directory);
	
	foreach (new RecursiveIteratorIterator($cur) as $filename => $cur)
	{
		$array = token_get_all(file_get_contents($filename));
	
		foreach($array as $k => $v)
		{
			if($v[0] == 353)
			{
				$k++;
				$nextArr = $array[$k++];
				$nextArr2 = $array[$k++];
			
				if($nextArr[0] == 371 && $nextArr2[0] == 307)
				{
					$manifest[$nextArr2[1]] = $filename;
				}
			}
			
			if($v[0] == 354)
			{
				$k++;
				$nextArr = $array[$k++];
				$nextArr2 = $array[$k++];
			
				if($nextArr[0] == 371 && $nextArr2[0] == 307)
				{
					$manifest[$nextArr2[1]] = $filename;
				}
			}
		}
	}
	
	$result = '<?php' . PHP_EOL . 'return ' . var_export($manifest, true) . PHP_EOL . '?>';
	
	file_put_contents($directory . '/manifest.php', $result);
	
	return true;
}