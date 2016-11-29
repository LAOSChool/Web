<?php
$bl1row = $dbp->getrow("select * from vhs_news where caid=1 and lang='$language' limit 1");
$bl1row['detail'] = truncate_str(strip_tags($bl1row['detail']),500);
echo<<<eot
	<div class="about" id="about">
		<div class="container">
			<div class="about-grids">
				<div class="col-md-6 about-grid-left">
					<img src="$dirfile/images/$bl1row[image]" alt=" " class="img-responsive" />
				</div>
				<div class="col-md-6 about-grid-right">
					<h3>$bl1row[title]</h3>
					<p>$bl1row[detail]</p>
					<div class="more">
						<a data-toggle="modal" data-content = "$bl1row[id]" data-target="#readmoremodal" href="javascript:;" class="hvr-shadow-radial b-link-stripe b-animate-go thickbox thickbox">$lang_learnmore</a>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
eot;

$bl5res = $dbp->query("select * from vhs_news where caid=5 and lang='$language'");
echo<<<eot
<div class="testimonials">
		<div class="container">
			<h3>$lang_testimonials</h3>
			<div class="testimonials-grids">
				<ul id="flexiselDemo1">	
eot;
while($bl5res->fetchInto($bl5row)){
	echo<<<eot
		<li>
			<div class="testimonials-grid">
				<div class="col-xs-5 testimonials-grid-left">
					<img src="$dirfile/images/$bl5row[image]" alt=" " class="img-responsive" />
				</div>
				<div class="col-xs-7 testimonials-grid-right">
					<div class="rating">
						<span>☆</span>
						<span>☆</span>
						<span>☆</span>
						<span>☆</span>
						<span>☆</span>
					</div>
					<p>$bl5row[description]<span>$bl5row[title]</span></p>
				</div>
				<div class="clearfix"> </div>
			</div>
		</li>
eot;
}			

echo<<<eot
</ul>
					<script type="text/javascript">
							$(window).load(function() {
								$("#flexiselDemo1").flexisel({
									visibleItems: 3,
									animationSpeed: 1000,
									autoPlay: true,
									autoPlaySpeed: 3000,    		
									pauseOnHover: true,
									enableResponsiveBreakpoints: true,
									responsiveBreakpoints: { 
										portrait: { 
											changePoint:480,
											visibleItems: 1
										}, 
										landscape: { 
											changePoint:640,
											visibleItems:2
										},
										tablet: { 
											changePoint:768,
											visibleItems: 2
										}
									}
								});
								
							});
					</script>
					<script type="text/javascript" src="$dirfile/js/jquery.flexisel.js"></script>
			</div>
		</div>
	</div>
eot;
?>

<div class="modal fade" id="readmoremodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php lang('msgdetail') ?></h4>
      </div>
		<div class="modal-body">
			
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('close') ?></button>
		</div>
    </div>
  </div>
</div>

<script>

$('#readmoremodal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	loadform('<?php domain(0) ?>/includes/block1detail.php?lang=<?php echo $language ?>&id='+button.data('content'),'.modal-body')
})

</script>