<?='<?xml version="1.0" encoding="utf-8"?>' . "\n";?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?=$config['base_url'];?></title><!-- This should be a variable somewhere -->
	<link href="<?=$config['base_url'];?>blog/feed" rel="self" type="application/atom+xml" />
	<link href="<?=$config['base_url'];?>blog" rel="alternate" type="text/html" />
	<id><?=$config['base_url'];?>blog/feed</id>
	<rights>Copyright 2000 and late, Me</rights>
	<updated><?=date(DATE_ATOM,$updated);?></updated>
	<author>
		<name>Me</name>
		<email>My Email</email>
		<uri><?=$config['base_url'];?></uri>
	</author>
	<? foreach($entries as $entry):?>
	<entry>
		<title><?=Smartypants($entry->title);?></title>
		<link href="<?=$entry->permalink;?>" rel="alternate" type="text/html" />
		<id><?=$entry->permalink;?></id>
		<published><?=date(DATE_ATOM,$entry->published);?></published>
		<updated><?=date(DATE_ATOM,$entry->updated);?></updated>
		<author>
				<name>Me</name>
				<email>My Email</email>
        		<uri><?=$config['base_url'];?></uri>
        </author>
		<summary><?=Smartypants($entry->summary);?></summary>
		<content type="html" xml:lang="en">
			<![CDATA[
				<?=Smartypants(Markdown($entry->body));?>
			]]>
		</content>
	</entry>
	<? endforeach; ?>
</feed>