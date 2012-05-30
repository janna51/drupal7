<style>
.wellesley-slider {
	height:250px;
	overflow:hidden;
}
.wellesley-nav {
	font-size:32px;
	text-align:center;
	/*margin:21px 0 0;*/
}
	.wellesley-nav a {
		color:#f1f1f1 !important;
		text-decoration:none;
		margin:0 4px 0 5px;
	}
	.wellesley-nav a.active, .wellesley-nav a:hover {
		color:#024a92 !important;
		text-decoration:none;

	}
</style>
<script type="text/javascript" src="http://drupal-test.wellesley.edu/misc/slider.js"></script>
<div class="wellesley-slider">
  <?php
	foreach($node->field_slider['und'] as $image){
		$width = round($image['width'] * 250 / $image['height']);
		print theme_image_style(array('style_name' => 'slider', 'path' => $image['uri'], 'width'=>$width, 'height' => 250));
	}
	
	?>
</div>
<div class="wellesley-nav">
</div>
<?php
if(node_access("update",$node)){
	print l("edit slider","node/".$node->nid.'/edit');
}
?>