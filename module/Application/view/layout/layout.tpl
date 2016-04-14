<!DOCTYPE html>
<html lang='en'>
	{include 'layout/partials/_head.tpl'}
	{* <body class="NWHistory" data-nwHistory-contentContainerId="content"> *}
	<body>
		{include 'layout/partials/_header.tpl'}
		<div id="content" class="content">
			{$this->nwFlashMessenger()}
			{$this->content}
		</div>
	</body>
</html>