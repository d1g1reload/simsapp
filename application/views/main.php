<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Test Aplikasi Codeigniter</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<style>
		.forgot {
			color: #B4B4B8 !important;
		}
	</style>
</head>

<body>

	<section class="account">
		<div class="container">
			<div class="row justify-content-center mt-3">
				<div class="col-md-6">
					<div class="card card-login">
						<div class="card-body">
							<h3 class="text-center">Login</h3>

							<?php if ($this->session->flashdata('failed')) : ?>
								<div class="alert alert-danger" role="alert">
									<strong><?php echo $this->session->flashdata('failed'); ?></strong>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>

								</div>
							<?php endif; ?>

							<form action="<?php echo base_url('login') ?>" method="post">
								<div class="form-group">
									<label for="inputEmail">Email</label>
									<input type="email" class="form-control" name="email" required>
								</div>

								<div class="form-group">
									<label for="inputPassword">Password</label>
									<input type="password" class="form-control" name="password" required>

								</div>

								<div class="form-group">
									<button class="btn btn-danger btn-block" type="submit">Login</button>
								</div>
							</form>



						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
	</script>


</body>

</html>