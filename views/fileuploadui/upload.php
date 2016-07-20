<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload fade col-xs-4 col-sm-2 item">
		<div class="preview thumbnail">
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="progress-bar progress-bar-success" style="width:0%;"></div>
			</div>
		</div>
    </div>
{% } %}
</script>