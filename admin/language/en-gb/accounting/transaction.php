<?php
// Heading
$_['heading_title']     		= 'Transaksi';
		
// Text		
$_['text_success']       		= 'Success: You have modified transaction!';
$_['text_account']           	= 'Akun Transaksi';
$_['text_add']           		= 'Tambah Transaksi';
$_['text_confirm_print']        = 'Are you sure?\nTransaksi yang telah dicetak akan ditandai telah divalidasi dan tidak bisa diedit maupun dihapus lagi.';
$_['text_edit']          		= 'Edit Transaksi';
$_['text_list']         		= 'Daftar Transaksi';
$_['text_print_list']         	= 'Cetak Transaksi';
$_['text_total']     			= 'Total';
$_['text_reference'] 			= 'No Referensi';
$_['text_result'] 				= 'Jumlah: %d transaksi.';
$_['text_signature'] 			= 'Dicetak oleh %s pada tanggal %s.';
$_['text_success_print'] 		= 'Success: You have print transaction and signed as validated!';
		
// Column		
$_['column_date'] 				= 'Tanggal';
$_['column_account_credit']		= 'Akun Kredit';
$_['column_account_debit']		= 'Akun Debet';
$_['column_description']		= 'Keterangan';
$_['column_reference']			= 'No Referensi';
$_['column_customer_name']		= 'Nama Klien';
$_['column_amount']				= 'Jumlah';
$_['column_transaction_type']	= 'Jenis Transaksi';
$_['column_validated']			= 'Validated';
$_['column_username']			= 'Username';
$_['column_action']				= 'Action';
		
// Entry		
$_['entry_account']  			= 'Rekening Akun';
$_['entry_amount']    			= 'Jumlah';
$_['entry_credit']    			= 'Kredit';
$_['entry_customer_name']    	= 'Nama Klien';
$_['entry_date']    			= 'Tanggal Transaksi';
$_['entry_date_start']  		= 'Date Start';
$_['entry_date_end']    		= 'Date End';
$_['entry_debit']    			= 'Debet';
$_['entry_description']    		= 'Keterangan';
$_['entry_order_id']    		= 'Order ID';
$_['entry_reference']    		= 'No Referensi';
$_['entry_transaction_type']  	= 'Jenis Transaksi';
$_['entry_validated']   		= 'Validation';
$_['entry_username']   		 	= 'Username';

// Error
$_['error_warning']             = 'Warning: Please check the form carefully for errors!';
$_['error_permission']   		= 'Warning: You do not have permission to modify transactions!';
$_['error_account']    			= 'Warning: Lengkapi Rekening Akun! (Minimum 2 akun berbeda)';
$_['error_account_amount']    	= 'Warning: Total Debet dan Kredit harus sama dan lebih besar dari 0!';
$_['error_account_credit']    	= 'Tentukan Akun Kredit!';
$_['error_account_debit']    	= 'Tentukan Akun Debet!';
$_['error_date']    			= 'Tanggal Transaksi harus diisi!';
$_['error_description']    		= 'Keterangan harus diisi 5 - 256 karakter!';
$_['error_amount']    			= 'Jumlah harus diisi dan hrs lebih besar dari 0!';
$_['error_not_found']	 		= 'Warning: Transaksi tidak ditemukan!';
$_['error_order']    			= 'Warning: Transaksi tidak bisa diedit/dihapus karena merupakan transaksi otomatis!';
$_['error_order_permission']    = 'Warning: Tidak diijinkan untuk memberi akses edit untuk Transaksi Otomatis!';
$_['error_order_status']		= 'Warning: Transaksi tidak bisa diedit/dihapus karena status pesanan telah "Complete"!';
// $_['error_reprinted']			= 'Warning: Transaksi telah dicetak sebelumnya. Klik Set as Unprint untuk cetak ulang!';
$_['error_transaction_type']	= 'Pilih Jenis Transaksi';
$_['error_lock_transaction']	= 'Warning: Pengubahan status validasi transaksi tidak diijinkan!';
$_['error_validated']			= 'Warning: Transaksi tidak bisa diedit/dihapus karena telah tervalidasi!';

// Button
$_['button_account_add']    	= 'Add Transaction Account';
$_['button_edit_lock']    		= 'Lock Edit Permission';
$_['button_edit_unlock']    	= 'Give Edit Permission';

// Help
$_['help_amount']        		 = 'Isi dengan nilai positif.';
