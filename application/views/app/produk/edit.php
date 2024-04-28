<div class="row mt-3">
	<div class="col-md-12">
		<h1 class="text-center">Form Edit Produk</h1>
		<?php if ($this->session->flashdata('error')) : ?>
			<div class="alert alert-danger" role="alert">
				<strong><?php echo $this->session->flashdata('error'); ?></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
		<?php endif; ?>
	</div>
</div>

<div class="row mt-3">
	<div class="col-md-6 ml-auto mr-auto">
		<div class="card">
			<div class="card-body">
				<form action="<?php echo base_url('produk/update/' . $produk->id) ?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<input type="file" class="form-control" name="image_produk">
					</div>
					<div class="form-group">
						<label>Nama Produk</label>
						<input type="text" name="nama_produk" class="form-control" value="<?php echo $produk->nama_produk ?>">
					</div>
					<div class="form-group">
						<label>Kategori</label>
						<select name="kategori_id" class="form-control">
							<?php
							foreach ($items as $val) {
							?>
								<option value="<?php echo $val->id ?>" <?php echo ($val->id == $produk->kategori_id ? 'selected' : ''); ?>><?php echo $val->nama_kategori ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group">
						<label>Harga Beli</label>
						<input type="number" name="harga_beli" class="form-control" value="<?php echo $produk->harga_beli ?>">
					</div>

					<div class="form-group">
						<label>Stok</label>
						<input type="number" name="stok_produk" class="form-control" value="<?php echo $produk->stok_produk ?>">
					</div>

					<div class="form-group">
						<label>Image Preview</label><br>
						<img src="<?php echo base_url('assets/image/' . $produk->image_produk) ?>" alt="Tidak ada photo" class="img-thumbnail">
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-danger btn-block">Update</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>