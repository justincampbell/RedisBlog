<html>
	<head>
		<title><?=$config['base_url'];?></title>
		<link rel="stylesheet" href="/css/index.css" />
		<link rel="alternate" type="application/atom+xml" href="/blog/feed" />
	</head>
	<body>
		<h1><?=$config['base_url'];?></h1>
		<? if (isset($entries)) { ?>
		<div id="wrapper">
		<? foreach($entries as $entry):?>
		<div class="entry">
		<h3><a href="<?=$entry->permalink; ?>"><?=Smartypants($entry->title); ?></a></h3>
		<?=Smartypants(Markdown($entry->summary)); ?>
		</div><!-- undefined -->
		<? endforeach; ?>
		</div><!-- #wrapper -->
		<? } else { ?><div id="null">NULL</div><? } ?>
		<div id="footer">
		Powered by <a href="http://codeigniter.com">CodeIgniter</a> and <a href="http://code.google.com/p/redis/">Redis</a>.<br />
		<!-- <?=$views->unique;?> visitor<? if($views->unique > 1) echo "s"; ?> / <?=$views->total;?> view<? if($views->total > 1) echo "s"; ?> -->
		</div><!-- footer -->
	</body>
</html>