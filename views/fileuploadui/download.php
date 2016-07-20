<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<div class="template-download fade col-xs-4 col-sm-2 item" data-key="{%=file.id%}">
		<span class="preview">
			{% if (file.thumbnailUrl) { %}
				<a href="#mediafile" title="{%=file.name%}" class="thumbnail" data-key="{%=file.id%}">
					<img src="{%=file.thumbnailUrl%}" alt="">
					<span class="glyphicon glyphicon-check checked"></span>
				</a>
			{% } %}
		</span>
		{% if (file.error) { %}
			<div>
				<span class="label label-danger"><?= Yii::t('fileupload', 'Error') ?></span>
				{%=file.error%}
			</div>
		{% } %}
	</div>
{% } %}

</script>
