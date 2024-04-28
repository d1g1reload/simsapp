<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
class Produk extends CI_Controller
{
	function __construct()
	{

		parent::__construct();
		if (!$this->session->userdata('is_loggedin')) {

			$this->session->set_flashdata('failed', 'Silahkan login terlebih dahulu');

			redirect('main');
		}
	}

	function index()
	{
		$email = $this->session->userdata('email');
		$data['items'] = $this->Produk_model->get_kategori();
		$data['produk'] = $this->Produk_model->get_produk();
		$data['users'] = $this->User_model->get_user($email);
		$data['content'] = "app/produk/produk";
		$this->load->view('layouts/main', $data);
	}

	function page_add()
	{
		$data['items'] = $this->Produk_model->get_kategori();
		$data['content'] = "app/produk/add";
		$this->load->view('layouts/main', $data);
	}

	function submit_produk()
	{
		$nama_produk   	= $this->input->post('nama_produk', TRUE);
		$kategori_id   	= $this->input->post('kategori_id', TRUE);
		$harga_beli   	= $this->input->post('harga_beli', TRUE);
		$harga_jual   	= ((30 / 100) * $harga_beli) + $harga_beli;
		$stok   		= $this->input->post('stok_produk', TRUE);

		$search_produk = $this->Produk_model->search($nama_produk);

		if ($search_produk == null) {

			if ($_FILES and $_FILES['image_produk']['name']) {
				$config = array(
					'upload_path' => './assets/image/',
					'allowed_types' => 'jpeg|jpg|png|JPG|PNG|JPEG',
					'max_size' => 100,
					'encrypt_name' => true,
					'remove_spaces' => true
				);

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('image_produk')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect('produk/add');
				} else {
					$file = $this->upload->data();
					$data_produk = array(
						'image_produk' 	=> $file['file_name'],
						'nama_produk' 	=> $nama_produk,
						'kategori_id' 	=> $kategori_id,
						'harga_beli' 	=> $harga_beli,
						'harga_jual' 	=> $harga_jual,
						'stok_produk' 	=> $stok
					);

					if ($data_produk) {
						$this->Produk_model->create_produk($data_produk);
						$this->session->set_flashdata('success', 'Tambah Produk Berhasil.');
						redirect('produk');
					} else {

						$this->session->set_flashdata('error', 'Gagal Tambah Produk.');
						redirect('produk');
					}
				}
			}
		} else {
			$this->session->set_flashdata('error', 'Nama Produk Sudah Terdaftar.');
			redirect('produk/add');
		}
	}


	function delete_produk($id)
	{
		$this->Produk_model->delete_produk($id);
		$this->session->set_flashdata('success', 'Hapus data berhasil');
		redirect('produk');
	}


	function page_edit($id = null)
	{
		$data['items'] = $this->Produk_model->get_kategori();
		$data['produk'] = $this->Produk_model->get_produk_id($id);
		$data['content'] = "app/produk/edit";
		$this->load->view('layouts/main', $data);
	}

	function update_produk($id = null)
	{
		$item = $this->Produk_model->get_produk_id($id);
		$nama_produk   	= $this->input->post('nama_produk', TRUE);
		$kategori_id   	= $this->input->post('kategori_id', TRUE);
		$harga_beli   	= $this->input->post('harga_beli', TRUE);
		$harga_jual   	= ((30 / 100) * $harga_beli) + $harga_beli;
		$stok   		= $this->input->post('stok_produk', TRUE);


		if ($_FILES and $_FILES['image_produk']['name']) {
			$config = array(
				'upload_path' => './assets/image/',
				'allowed_types' => 'jpeg|jpg|png|JPG|PNG|JPEG',
				'max_size' => 100,
				'encrypt_name' => true,
				'remove_spaces' => true
			);

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('image_produk')) {
				$errors = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata($errors);
				redirect('produk/edit/' . $item->id);
			} else {
				unlink('./assets/image/' . $item->image_produk);

				//upload file
				$file = $this->upload->data();

				$data_produk = array(
					'image_produk' 	=> $file['file_name'],
					'nama_produk' 	=> $nama_produk,
					'kategori_id' 	=> $kategori_id,
					'harga_beli' 	=> $harga_beli,
					'harga_jual' 	=> $harga_jual,
					'stok_produk' 	=> $stok
				);

				$this->Produk_model->update_produk($id, $data_produk);
			}
		} else {

			// No file upload

			$data_produk = array(
				'nama_produk' 	=> $nama_produk,
				'kategori_id' 	=> $kategori_id,
				'harga_beli' 	=> $harga_beli,
				'harga_jual' 	=> $harga_jual,
				'stok_produk' 	=> $stok
			);

			$this->Produk_model->update_produk($id, $data_produk);
		}
		$this->session->set_flashdata('success', 'Update Data Produk Berhasil');
		redirect('produk');
	}

	/**
	 * export excel
	 */

	function export_excel()
	{

		$excel = new PHPExcel();

		$kat_id = $this->input->post('kategori_id', true);


		if ($kat_id == 0) {

			$excel->getProperties()->setCreator('Septiadi Rahman')
				->setLastModifiedBy('SIMSAPP')
				->setTitle("Data Laporan Produk")
				->setSubject("Data Laporan Produk")
				->setDescription("Data Laporan Produk")
				->setKeywords("Data Laporan Produk");


			// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
			$style_col = array(
				'font' => array('bold' => true), // Set font nya jadi bold
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
			$style_row = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LAPORAN PRODUK"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA PRODUK"); // Set kolom B3 dengan tulisan "NIS"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "KATEGORI PRODUK"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "HARGA BARANG"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "HARGA JUAL"); // Set kolom E3 dengan tulisan "ALAMAT"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "STOK PRODUK"); // Set kolom E3 dengan tulisan "ALAMAT"

			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);

			$items = $this->Produk_model->get_produk();
			$no = 1;
			$numrow = 4;
			foreach ($items as $data) {
				$excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->nama_produk);
				$excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->nama_kategori);
				$excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, number_format($data->harga_beli));
				$excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, number_format($data->harga_jual));
				$excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->stok_produk);

				$excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);

				$no++; // Tambah 1 setiap kali looping
				$numrow++; // Tambah 1 setiap kali looping
			}

			// Set width kolom
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom E

			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Siswa");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Laporan Produk.xls"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			$write->save('php://output');
			exit();
		} else {
			$excel->getProperties()->setCreator('Septiadi Rahman')
				->setLastModifiedBy('SIMSAPP')
				->setTitle("Data Laporan Produk")
				->setSubject("Data Laporan Produk")
				->setDescription("Data Laporan Produk")
				->setKeywords("Data Laporan Produk");


			// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
			$style_col = array(
				'font' => array('bold' => true), // Set font nya jadi bold
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
			$style_row = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LAPORAN PRODUK"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA PRODUK"); // Set kolom B3 dengan tulisan "NIS"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "KATEGORI PRODUK"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "HARGA BARANG"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "HARGA JUAL"); // Set kolom E3 dengan tulisan "ALAMAT"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "STOK PRODUK"); // Set kolom E3 dengan tulisan "ALAMAT"

			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);

			$items = $this->Produk_model->get_produk_by_kategori($kat_id);
			$no = 1;
			$numrow = 4;
			foreach ($items as $data) {
				$excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->nama_produk);
				$excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->nama_kategori);
				$excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, number_format($data->harga_beli));
				$excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, number_format($data->harga_jual));
				$excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->stok_produk);

				$excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);

				$no++; // Tambah 1 setiap kali looping
				$numrow++; // Tambah 1 setiap kali looping
			}

			// Set width kolom
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom E

			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Siswa");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Laporan Produk.xls"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			$write->save('php://output');
			exit();
		}
	}
}
