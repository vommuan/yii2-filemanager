<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<div class="gallery-items__item media-file" data-key="{%=file.id%}">
		{% if (file.thumbnailUrl) { %}
			<a href="#mediafile" class="media-file__link">
				<img src="{%=file.thumbnailUrl%}" alt="">
				<div class="checker">
					<span class="glyphicon glyphicon-ok"></span>
				</div>
			</a>
		{% } %}
		{% if (file.error) { %}
			<div>
				<span class="label label-danger"><?= Yii::t('fileupload', 'Error') ?></span>
				{%=file.error%}
			</div>
		{% } %}
	</div>
{% } %}

</script>
