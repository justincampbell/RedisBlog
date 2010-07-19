<html>
	<head>
		<title><?=$entry->title;?></title>
		<link rel="stylesheet" href="/css/entry.css" />
		<link rel="alternate" type="application/atom+xml" href="/blog/feed" />
	</head>
	<body>
		<div id="wrapper">
		<h1><a href="<?=$entry->permalink;?>"><?=Smartypants($entry->title);?></a></h1>
		<?=Smartypants(Markdown($entry->body));?>
		</div><!-- wrapper -->
		<div id="footer">
			<?=$views->unique;?> visitor<? if($views->unique > 1) echo "s"; ?> / <?=$views->total;?> view<? if($views->total > 1) echo "s"; ?>
		</div><!-- footer -->
	</body>
</html>