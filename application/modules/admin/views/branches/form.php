<div class="row">
  	<div class="col-xl-8">
    	<form role="form" action="<?=isset($form_url) ? $form_url : '#'?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<?=isset($title) ? $title : ""?>
						</div>
						<div class="card-body">
							<div class="row"> 
								<div class="col-md-12">
									<?=(isset($notification) ? (!empty($notification) ? $notification : '' ) : '') ?>
								</div>     
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label>Branch Name <span class="text-danger">*</span></label>
										<input name="branch-name" class="form-control" placeholder="Branch Name" value="<?=isset($post['branch-name']) ? $post['branch-name'] : ""?>">
										<span class="text-danger"><?=form_error('branch-name')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label>Emaill Address <span class="text-danger">*</span></label>
										<input type="email" name="email-address" class="form-control" placeholder="Email Address" value="<?=isset($post['email-address']) ? $post['email-address'] : ""?>">
										<span class="text-danger"><?=form_error('email-address')?></span>
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>Mobile No. </label>
										<input name="mobile-no" class="form-control" placeholder="09xxxxxxxxx" value="<?=isset($post['mobile-no']) ? $post['mobile-no'] : ""?>">
										<span class="text-danger"><?=form_error('mobile-no')?></span>
									</div>
								</div>
							</div>
							<?php if (isset($is_update)) { ?>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-control">
										<input type="checkbox" id="status" name="status" value="1" <?=isset($post["status"]) ? ($post["status"] == 1 ? "checked" : "") : ""?>>
										<label for="status">&nbsp; Uncheck to deactivate.</label>
									</div>
								</div>
							</div><br>
							<?php } ?>
							<div class="row">
								<div class="col-xl-4">
									<div class="form-group">
										<button type="submit" class="btn btn-block btn-success">SAVE</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
  	</div>
</div>