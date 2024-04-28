<div class="row mt-3">
	<div class="col-md-12">
		<h1 class="text-center">Profile</h1>
	</div>
</div>
<div class="row mt-3">

	<div class="col-md-3 mb-3">
		<img src="<?php echo base_url('assets/image/' . $users->photo) ?>" alt="photo" class="img-photo">
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-stripped">
						<thead>
							<tr>
								<td>Nama Kandidat</td>
								<td>:</td>
								<td><?php echo $users->fullname ?></td>
							</tr>
							<tr>
								<td>Posisi Kandidat</td>
								<td>:</td>
								<td><?php echo $users->posisi ?></td>
							</tr>

						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>