<div class="row mt-3 mb-5">
	<div class="col-md-12">
		<h1 class="text-center">Daftar Produk</h1>
		<?php if ($this->session->flashdata('success')) : ?>
			<div class="alert alert-success" role="alert">
				<strong><?php echo $this->session->flashdata('success'); ?></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
		<?php endif; ?>
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


<div class="row mb-4 mt-3">
	<div class="col-md-12">
		<form class="form-inline" method="post" action="produk/export">
			<div class="form-group mx-sm-3 ">
				<select name="kategori_id" class="form-control" id="">
					<option value="0">Semua</option>
					<?php foreach ($items as $val) : ?>
						<option value="<?php echo $val->id ?>"><?php echo $val->nama_kategori ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<button type="submit" class="btn btn-success mx-sm-3 "><i class="fa fa-file-excel"></i> Export Excel</button>
			<a href="<?php echo base_url('produk/add') ?>" class="btn btn-danger"><i class="fa fa-plus"></i> Tambah Produk</a>
		</form>
	</div>
</div>


<!-- Content Row -->
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered" id="produkTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Foto Produk</th>
						<th>Nama Produk</th>
						<th>Kategori Produk</th>
						<th>Harga Beli (Rp)</th>
						<th>Harga Jual (Rp)</th>
						<th>Stok Produk</th>
						<th>Aksi</th>

					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($produk as $item) {

					?>
						<tr>
							<td><?php echo $no++ ?></td>
							<td><img src="<?php echo base_url('assets/image/' . $item->image_produk) ?>" style="widht:80px;height:80px;" alt="Tidak ada Photo"></td>
							<td><?php echo $item->nama_produk ?></td>
							<td><?php echo $item->nama_kategori ?></td>
							<td><?php echo number_format($item->harga_beli, 2) ?></td>
							<td><?php echo number_format($item->harga_jual, 2) ?></td>
							<td><?php echo $item->stok_produk ?></td>
							<td>
								<a href="<?php echo base_url('produk/edit/' . $item->id) ?>"><i class="fa fa-edit"></i></a>
								<a href="#" data-toggle="modal" data-target="#produk<?php echo $item->id ?>"><i class="fa fa-trash text-danger"></i></a>

							</td>

						</tr>

						<!-- Modal delete-->
						<div class="modal fade" id="produk<?php echo $item->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">

										Anda yakin ingin hapus data <b><?php echo strtoupper($item->nama_produk) ?></b>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
										<form action="<?php echo base_url('produk/delete/' . $item->id) ?>" method="post">
											<button type="submit" class="btn btn-danger">Hapus</a>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>