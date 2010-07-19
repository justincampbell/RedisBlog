<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Edit</title>
		<link rel="stylesheet" href="/css/edit.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://localhost/js/jquery.json-2.2.min.js"></script>
		<script type="text/javascript">
		
			var timeout;
			
			$(document).ready(function(){
				$('#form > *').keyup(function(){
					clearTimeout(timeout);
					timeout = setTimeout(function(){
						$.post(
							'/blog/preview',
							$("#form").serialize(),
							function(data) {
								$('#preview').html(data);
							}
						);
					}, 1000);
				});
				$('#body').keyup();
			});
			 
			function UpdateList() {
				$("#list > table").empty();
				$.getJSON('/blog/ajax/getarticles', function(data) {
					$(data).each(function(key, value) {
						$("#list > table").append(
							"<tr id=\"" + value + "\"><td>"
								+ value +
							"</td><td>0</td></tr>"
						);
					});
					$("#list > table > tbody > tr").each(function(index, item) {
						$.getJSON('/blog/ajax/getentry/' + item.id, function(entry) {
							$("#" + item.id).replaceWith(
								"<tr id=\"" + item.id + "\"><td><a href=\"javascript:LoadEntry('" + item.id + "')\">"
									+ entry.title +
								"</a></td><td>" 
									+ entry.created +
								"</td></tr>"
							);
						});
					});
				});
			}
			
			function LoadEntry(id) {
				$.getJSON('/blog/ajax/getentry/' + id, function(entry) {
					$("#title").val(entry.title);
					$("#id").val(entry.id);
					$("#permalink").val(entry.permalink);
					$("#body").val(entry.body);
					$('#body').keyup();
				});
			}
			
		</script>
		<style type="text/css">
			
		</style>
	</head>
	<body>
		<div id="main">
			<div id="editorwrapper">
				<div id="editor">
					<form id="form">
						<input id="title" name="title" value="Title" /><br />
						<input id="id" name="id" value="title" /><br />
						<textarea id="body" name="body">Some text</textarea><br />
					</form>
				</div><!-- editor -->
			</div><!-- editorwrapper -->
			<div id="preview">
			</div>
		</div><!-- main -->
		<div id="list">
			<a href="javascript:UpdateList();">Refresh</a>
			<table><!-- placeholder --></table>
		</div><!-- list -->
		<form method="post" action="/blog/preview">
			<input type="hidden" name="submitAttempted" value="true" />
		</form>
	</body>
</html>