<?php

class Produk_model extends CI_Model
{

	function get_kategori()
	{

		return $this->db->get('kategori')->result();
	}

	function create_produk($data_produk)
	{
		$this->db->insert('produk', $data_produk);
	}

	function get_produk()
	{
		$this->db->select('p.id,p.image_produk,p.nama_produk,k.nama_kategori,p.kategori_id,p.harga_beli,p.harga_jual,p.stok_produk');
		$this->db->from('produk as p');
		$this->db->join('kategori as k', ' k.id = p.kategori_id', 'inner');
		return $this->db->get()->result();
	}

	function get_produk_by_kategori($kat_id)
	{
		$this->db->select('p.id,p.image_produk,p.nama_produk,k.nama_kategori,p.kategori_id,p.harga_beli,p.harga_jual,p.stok_produk');
		$this->db->from('produk as p');
		$this->db->where('kategori_id', $kat_id);
		$this->db->join('kategori as k', ' k.id = p.kategori_id', 'inner');
		return $this->db->get()->result();
	}

	function delete_produk($id)
	{
		$result = $this->db->delete('produk', array('id' => $id));
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	function search($nama_produk)
	{
		$query = "SELECT * FROM `produk` WHERE nama_produk LIKE '%$nama_produk%'";
		return $this->db->query($query)->row();
	}

	function get_produk_id($id)
	{
		return $this->db->where('id', $id)->get('produk')->row();
	}

	function update_produk($id, $data_produk)
	{
		$this->db->where('id', $id)->update('produk', $data_produk);
	}
}
