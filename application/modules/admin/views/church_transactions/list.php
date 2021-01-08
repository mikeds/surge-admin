<div class="row"> 
	<div class="col-xl-4"><br/>
		<div class="card">
			<div class="card-header">
				Search
			</div>
			<div class="card-body">
				<form role="form" action="<?=isset($form_url) ? $form_url : '#'?>" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<label>Branch</label>
								<?=isset($branches) ? $branches : ""?>
								<span class="text-danger"><?=form_error('branches')?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-success">Search</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-md-12"><br/>
		<div class="card">
			<div class="card-header">
				<?=isset($title) ? $title : ""?>
			</div>
			<div class="card-body">
				<?php
					if(isset($listing)){
						foreach ($listing as $list) {
							echo $list;
						}
					}
				?>
			</div>
		</div>
	</div>
</div>