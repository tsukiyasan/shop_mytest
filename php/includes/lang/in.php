<?php
/**
* version #1.0
* package Shopping cart
* date 2012/06
* author bibibobo HSU 
* email bibibobo97@gmail.com
* copyright protected
*/

defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

//-----共通-----//

DEFINE('_YES','Iya');
DEFINE('_NO','Tidak');

DEFINE('_COMMON_COMPONENT_MAINMENU','Menu utama');
DEFINE('_COMMON_COMPONENT_NEWS','berita terbaru');



DEFINE('_COMMON_PARAM_ID','Penomoran');
DEFINE('_COMMON_PARAM_FROM','Nomor awal');
DEFINE('_COMMON_PARAM_TITLE','judul');
DEFINE('_COMMON_PARAM_NAME','nama');
DEFINE('_COMMON_PARAM_PUBLISH','layar');
DEFINE('_COMMON_PARAM_LEVEL','Kelas');
DEFINE('_COMMON_PARAM_BELONGID','Nomor atribusi');
DEFINE('_COMMON_PARAM_PAGETYPE','Jenis halaman');
DEFINE('_COMMON_PARAM_DATABASETAB','Tabel atribusi');
DEFINE('_COMMON_PARAM_DATABASEID','Nomor lembar data');
DEFINE('_COMMON_PARAM_LINKURL','URL tujuan');
DEFINE('_COMMON_PARAM_TARGET','tujuan');
DEFINE('_COMMON_PARAM_CONTENT','kandungan');
DEFINE('_COMMON_PARAM_TEL','telepon');
DEFINE('_COMMON_PARAM_FAX','fax');
DEFINE('_COMMON_PARAM_EMAIL','surel');
DEFINE('_COMMON_PARAM_ADDR','alamat');
DEFINE('_COMMON_PARAM_WEBURL','alamat situs web');
DEFINE('_COMMON_PARAM_WEBTITLE','Header situs web');
DEFINE('_COMMON_PARAM_WEBKEYS','Kata kunci situs');
DEFINE('_COMMON_PARAM_WEBINTRO','Deskripsi situs');
DEFINE('_COMMON_PARAM_ALLRIGHT','Deklarasi hak');
DEFINE('_COMMON_PARAM_MEDIADEC1','Deskripsi media 1');
DEFINE('_COMMON_PARAM_MEDIADEC2','Deskripsi media 2');
DEFINE('_COMMON_PARAM_MEDIADEC3','Deskripsi media 3');
DEFINE('_COMMON_PARAM_MEDIAURL1','Tautan media 1');
DEFINE('_COMMON_PARAM_MEDIAURL2','Tautan media 2');
DEFINE('_COMMON_PARAM_MEDIAURL3','Tautan Media 3');
DEFINE('_COMMON_PARAM_SEARCH_NAME','String pencarian');
DEFINE('_COMMON_PARAM_ODRING','Menyortir');
DEFINE('_COMMON_PARAM_NEWSDATE','Tanggal rilis');
DEFINE('_COMMON_PARAM_PUBDATE','tenggat waktu');
DEFINE('_COMMON_PARAM_NEWS','berita terbaru');
DEFINE('_COMMON_PARAM_HOT','Populer');
DEFINE('_COMMON_PARAM_LOGINID','nomor akun');
DEFINE('_COMMON_PARAM_PASSWD','kata sandi');
DEFINE('_COMMON_PARAM_VR','Nomor acak otentikasi');
DEFINE('_COMMON_PARAM_CV','Kode Otentikasi');
DEFINE('_COMMON_PARAM_LG','Kode bahasa');
DEFINE('_COMMON_PARAM_DLVRPAYCHK','Bayar di tempat');
DEFINE('_COMMON_PARAM_BANKPAYCHK','Transfer bank ATM');
DEFINE('_COMMON_PARAM_CREDITPAYCHK','pembayaran kartu kredit');
DEFINE('_COMMON_PARAM_HOMEDLVRCHK','Pengiriman ke rumah');
DEFINE('_COMMON_PARAM_HOMEDLVRCHK_AMT','Biaya pengiriman rumah');
DEFINE('_COMMON_PARAM_HOMEDLVRGMCHK','Gratis ongkos kirim untuk pesanan penuh');
DEFINE('_COMMON_PARAM_HOMEDLVRGMCHK_AMT','Pesanan penuh');
DEFINE('_COMMON_PARAM_BANKNAME','nama Bank');
DEFINE('_COMMON_PARAM_BANKBRANCH','Cabang (bank)');
DEFINE('_COMMON_PARAM_BANKID','Nama rekening (bank)');
DEFINE('_COMMON_PARAM_BANKNUM','Nomor rekening (bank)');
DEFINE('_COMMON_PARAM_DONATEUNIT','Unit donasi faktur');
DEFINE('_COMMON_PARAM_POSTBRANCH','Kantor Cabang (Kantor Pos)');
DEFINE('_COMMON_PARAM_POSTID','Nama akun (kantor pos)');
DEFINE('_COMMON_PARAM_POSTNUM1','Akun Satu (Kantor Pos)');
DEFINE('_COMMON_PARAM_POSTNUM2','Rekening Dua (Kantor Pos)');
DEFINE('_COMMON_PARAM_DBTABLE','Database web');
DEFINE('_COMMON_PARAM_TYPE','Jenis dari');
DEFINE('_COMMON_PARAM_PROCODE','Kode Produk');
DEFINE('_COMMON_PARAM_STOP_DATE','tenggat waktu');
DEFINE('_COMMON_PARAM_NEW_PRODUCT','produk terbaru');
DEFINE('_COMMON_PARAM_HOT_PRODUCT','Penjualan terbaik');
DEFINE('_COMMON_PARAM_REC_PRODUCT','Produk yang direkomendasikan');
DEFINE('_COMMON_PARAM_STOCKCHK','Pemeriksaan inventaris');
DEFINE('_COMMON_PARAM_STOCKCNT','Inventaris saat ini');
DEFINE('_COMMON_PARAM_INSTOCK','Persediaan keselamatan');
DEFINE('_COMMON_PARAM_HIGHAMT','Harga asli');
DEFINE('_COMMON_PARAM_SALESAMT','Penawaran istimewa');
DEFINE('_COMMON_PARAM_BONUS','dividen');
DEFINE('_COMMON_PARAM_OTHERURL','Tautan luar');
DEFINE('_COMMON_PARAM_PRODUCT_NOTES','Deskripsi Produk');
DEFINE('_COMMON_PARAM_PRODUCT_SUMMARY','Deskripsi singkat');
DEFINE('_COMMON_PARAM_FIELD','Bidang yang dipesan');
DEFINE('_COMMON_PARAM_MEDIANAME','Nama media');
DEFINE('_COMMON_PARAM_MEDIACONT','Deskripsi media');
DEFINE('_COMMON_PARAM_MEDIASOURCE','URL tujuan media');
DEFINE('_COMMON_PARAM_SID','nomor ID');
DEFINE('_COMMON_PARAM_MOBILE','Telepon selular');
DEFINE('_COMMON_PARAM_SEX','jenis kelamin');
DEFINE('_COMMON_PARAM_BIRTHDATE','ulang tahun');
DEFINE('_COMMON_PARAM_COUPON','Gulungan kupon');
DEFINE('_COMMON_PARAM_BONUS','dividen');
DEFINE('_COMMON_PARAM_NEWSLETTER','Buletin');
DEFINE('_COMMON_PARAM_PLAN','Paket diskon');
DEFINE('_COMMON_PARAM_FDATE','Waktu mulai');
DEFINE('_COMMON_PARAM_EDATE','Akhir waktu');
DEFINE('_COMMON_PARAM_PLANCONT','Parameter skema');
DEFINE('_COMMON_PARAM_PRODUCT_STR','Barang dagangan diskon');
DEFINE('_COMMON_PARAM_BILLTYPE','Status pemesanan');
DEFINE('_COMMON_PARAM_CODENUM','Nomor seri');
DEFINE('_COMMON_PARAM_QUANTITY','Kuantitas');

DEFINE('_COMMON_PARAM_VALIDATE_NOT_REQUIRED','Tidak boleh kosong');
DEFINE('_COMMON_PARAM_VALIDATE_FORMAT_ERR','format yang salah');

DEFINE('_COMMON_QUERYMSG_ADD_SUS','berhasil ditambahkan');
DEFINE('_COMMON_QUERYMSG_ADD_ERR','Penambahan gagal');
DEFINE('_COMMON_QUERYMSG_UPD_SUS','pembaruan selesai');
DEFINE('_COMMON_QUERYMSG_UPD_ERR','Pembaharuan gagal');
DEFINE('_COMMON_QUERYMSG_DEL_SUS','berhasil dihapus');
DEFINE('_COMMON_QUERYMSG_DEL_ERR','gagal dihapus');


DEFINE('_COMMON_QUERYMSG_HAVE_ACT','Produk ini adalah penawaran khusus di acara tersebut dan tidak dapat dihapus');
DEFINE('_COMMON_QUERYMSG_HAVE_DATA','Masih ada data di bawah item ini, yang tidak dapat dihapus');
DEFINE('_COMMON_QUERYMSG_HAVE_RELATED_DATA','Masih ada proyek yang terkait dengannya dan tidak dapat dihapus');
DEFINE('_COMMON_QUERYMSG_LOGIN_ERROR','Kata sandi akun salah');
DEFINE('_COMMON_QUERYMSG_LOGIN_ERROR2','Saat ini hanya membuka login dealer');
DEFINE('_COMMON_QUERYMSG_HAVE_LOG','Anggota yang ada telah menebus kupon Kupon dan tidak dapat dihapus');
DEFINE('_COMMON_QUERYMSG_SIGNUP_ERROR','Akun ini sudah menjadi anggota, silakan langsung masuk');
DEFINE('_COMMON_QUERYMSG_SIGNUP_SUC','registrasi berhasil');

DEFINE('_COMMON_ERRORMSG_NET_ERR','Kesalahan jaringan, harap konfirmasi status koneksi Anda');
DEFINE('_COMMON_ERRORMSG_CHECKCODE_ERR','Kesalahan kode verifikasi');
DEFINE('_COMMON_ERRORMSG_LOGINOUT_ERR','Kesalahan keluar');
DEFINE('_COMMON_ERRORMSG_DBPAGE_ERR','Item ini tidak ada');
DEFINE('_COMMON_ERRORMSG_DBPAGE_HASERR','Beberapa proyek menggunakan ini sebagai database web');
DEFINE('_COMMON_ERRORMSG_LOGINID_REPEAT','Akun yang sama sudah ada');


DEFINE('_COMMON_NOW_DATE','Dari sekarang');
DEFINE('_COMMON_NO_END','tidak ada batas');
DEFINE('_COMMON_UNLIMIT','Tak terbatas');

DEFINE('_COMMON_QUERYMSG_LOGIN_CHECKCODE_ERROR','Kesalahan kode verifikasi');
DEFINE('_COMMON_PARAM_CHECKCODE','Kode verifikasi');
DEFINE('_COMMON_AMT','Pergantian');
DEFINE('_COMMON_VIEWCNT','Kali orang');
DEFINE('_COMMON_SALECNT','Kuantitas');


//0707共用
DEFINE("_COMMON_QUERYMSG_SELECT_ERR","資料查詢失敗");


//0707活動專區
DEFINE("_ACTIVE_TITLE","halaman zona aktivitas");
DEFINE("_ACTIVE_ACTIVITY_LIMIT","telah mencapai batas waktu berbelanja di promosi ini");

//0707紅利兌換
DEFINE("_BONUS_NO_PRODUCT","tidak ada produk ini");

//0707購物車
DEFINE("_CART_ERROR_MSG","silakan selesai pembayaran biaya keanggotaan dan verifikasi email terlebih dahulu");
DEFINE("_CART_PAY_SUCCESS_MSG1","beritahuan pembayaran berhasil");
DEFINE("_CART_PAY_SUCCESS_MSG2","detail pesana");
DEFINE("_CART_PAY_SUCCESS_MSG3","administrator yang terhormat");
DEFINE("_CART_PAY_SUCCESS_MSG4","pembayaran baru telah diterima, segera memproses pesanan anda ke sistem administasi");
DEFINE("_CART_PAY_SUCCESS_MSG5","kode pemesanan");
DEFINE("_CART_PAY_SUCCESS_MSG6","No. TEL");
DEFINE("_CART_PAY_SUCCESS_MSG7","alamat");
DEFINE("_CART_PAY_SUCCESS_MSG8","pelayanan pelanggan");
DEFINE("_CART_PAY_ERROR_MSG1","transaksi gagal , silakan lakukan pembayaran ulang");
DEFINE("_CART_PAY_SUCCESS","transaksi berhasil");
DEFINE("_CART_EMPTY","troli belanja anda kosong");
DEFINE("_CART_INSTOCK_ERROR_MSG","stok tidak cukup silakan pilih lagi");
DEFINE("_CART_PASSWORDTEXT_ERROR_MSG1","");
DEFINE("_CART_PASSWORDTEXT_ERROR_MSG2","");
DEFINE("_CART_BONUS_ERROR_MSG","saldo belanja anda tidak cukup");
DEFINE("_CART_NET_ERROR_MSG","kesalahan koneksi internet");
DEFINE("_CART_ORDER_ADD_MSG1","pemberitahuan pesanan berhasil");
DEFINE("_CART_ORDER_ADD_MSG2","");
DEFINE("_CART_ORDER_ADD_MSG3","yang terhormat");
DEFINE("_CART_ORDER_ADD_MSG4","anggota");
DEFINE("_CART_ORDER_ADD_MSG5","informasi pesanan anda telah diterima, terima kasih atas pesanan anda");
DEFINE("_CART_ORDER_ADD_MSG6","informasi pesanan anda adalah sebagai berikut:");
DEFINE("_CART_ORDER_ADD_MSG7","");
DEFINE("_CART_ORDER_ADD_MSG8","tanggal pemesanan");
DEFINE("_CART_ORDER_ADD_MSG9","jumlah pemesanan");
DEFINE("_CART_ORDER_ADD_MSG10","no. tlp");
DEFINE("_CART_ORDER_ADD_MSG11","alamat");
DEFINE("_CART_ORDER_ADD_MSG12","");
DEFINE("_CART_ORDER_ADD_MSG13","");
DEFINE("_CART_ORDER_ADD_MSG14","pembayaran baru telah diterima, segera memproses pesanan anda ke sistem administasi");
DEFINE("_CART_NET_ERROR_MSG2","ada masalah dengan sistem, silakan hubungi layanan pelanggan");


//聯絡我們
DEFINE("_CONTACT_WRITE_MSG","silakan isi informasi yang diperlukan");
DEFINE("_CONTACT_TITLE","hubungi kami");
DEFINE("_CONTACT_NAME","nama");
DEFINE("_CONTACT_TEL","nomor kontak");
DEFINE("_CONTACT_EMAIL","email");
DEFINE("_CONTACT_TYPE","tipe pertanyaan");
DEFINE("_CONTACT_CITY","kota tempat tinggal");
DEFINE("_CONTACT_MSG","informasi");
DEFINE("_CONTACT_SUCCESS_MSG","pesan telah terkirim! Terima kasih atas pesan dan dukungan anda , kami akan segera menghubungi anda.");

//自訂頁面
DEFINE("_DBPAGE_ABOUTUS","tentang homeway");


//會員中心
DEFINE("_MEMBER_NO_DATA","info tidak ditemukan");
DEFINE("_MEMBER_ERROR_CARD","kartu tidak valid");
DEFINE("_MEMBER_SID_REPEAT","nomer ID sudah digunakan sebelumnya");
DEFINE("_MEMBER_EMAIL_REPEAT","email sudah pernah digunakan sebelumnya");
DEFINE("_MEMBER_SID_ERROR","nomer ID tidak valid");
DEFINE("_MEMBER_EMAIL_ERROR","email tidak valid");
DEFINE("_MEMBER_SC1","Northern Region Joint Service Center");
DEFINE("_MEMBER_SC1_ADDR","11F.-1, No.266, Sec. 1, Wenhua 2nd Rd., Linkou Dist., New Taipei City 244, Taiwan");
DEFINE("_MEMBER_SC2","Hsinchu Service Center");
DEFINE("_MEMBER_SC2_ADDR","14F.-6, No.27, Guanxin Rd., East Dist., Hsinchu City 300, Taiwan");
DEFINE("_MEMBER_SC3","Taichung Service Center");
DEFINE("_MEMBER_SC3_ADDR","8F.-3, No.666, Sec. 2, Wuquan W. Rd., Nantun Dist., Taichung City 408, Taiwan");
DEFINE("_MEMBER_SC4","Yunlin Service Center");
DEFINE("_MEMBER_SC4_ADDR","1F., No.52, Wenke Rd., Huwei Township, Yunlin County 632, Taiwan");
DEFINE("_MEMBER_SC5","Kaohsiung Service Center");
DEFINE("_MEMBER_SC5_ADDR","11F.-2, No.315, Minghua Rd., Gushan Dist., Kaohsiung City 804, Taiwan");
DEFINE("_MEMBER_SC6","Tainan Global Business Headquarter");
DEFINE("_MEMBER_SC6_ADDR","No.23, Gongye 1st Rd., Annan Dist., Tainan City 709, Taiwan");
DEFINE("_MEMBER_SIGNUP_SUCCESS","registrasi sukses");
DEFINE("_MEMBER_HAS_LOGIN","anda sudah berhasil login");
DEFINE("_MEMBER_EMAILCHK_MSG1","surat sertifikasi member GoodARCH");
DEFINE("_MEMBER_EMAILCHK_MSG2","Kepada para member GoodARCH");
DEFINE("_MEMBER_EMAILCHK_MSG3","untuk memastikan email anda sudah benar, mohon melakukan verifikasi disini untuk aktifasi belanja online anda");
DEFINE("_MEMBER_EMAILCHK_MSG4","");
DEFINE("_MEMBER_EMAILCHK_MSG5","metode verifikasi");
DEFINE("_MEMBER_EMAILCHK_MSG6","mohon klik link dibawah ini untuk verifikasi");
DEFINE("_MEMBER_EMAILCHK_MSG7","klik disini untuk verifikasi akun member");
DEFINE("_MEMBER_EMAILCHK_MSG8","email ini dikirim secacra otomatis dan dikirim dari sistem. Mohon untuk tidak membalas email ini, jika anda memiliki pertanyaan, silahkan hubungi customer service kami");
DEFINE("_MEMBER_EMAILCHK_MSG9","kirim email verifikasi");
DEFINE("_MEMBER_ERROR","permintaan tidak valid");
DEFINE("_MEMBER_EMAILCHK_MSG10","verifikasi selesai, anda bisa belanja secara online sekarang");
DEFINE("_MEMBER_NO_MEMBER","info staf tidak ditemukan, mohon hubungi kantor yang bersangkutan");
DEFINE("_MEMBER_PAY_SUCCESS","pembayaran berhasil");
DEFINE("_MEMBER_NO_BONUS","bonus tidak mencukupi");
DEFINE("_MEMBER_SELECT_ORDER","pilih kembali pesanan");
DEFINE("_MEMBER_NO_OEDER","pesanan tidak ditemukan");
DEFINE("_MEMBER_CFM_RECEIPT","konfirmasi penerimaan");
DEFINE("_MEMBER_LOGIN_FIRST","mohon login");
DEFINE("_MEMBER_ENTER_PWD","masukan kata sandi");
DEFINE("_MEMBER_ERROR_MSG","kata sandi sudah diubah, mohon masukan kata sandi baru");
DEFINE("_MEMBER_NO_MEMBER2","member tidak ditemukan");
DEFINE("_MEMBER_USER","Pengguna");
DEFINE("_MEMBER_RESET_PWD_MSG1","konfirmasi pengubahan kata sandi");
DEFINE("_MEMBER_RESET_PWD_MSG2","");
DEFINE("_MEMBER_RESET_PWD_MSG3","halo");
DEFINE("_MEMBER_RESET_PWD_MSG4","klik link dibawah ini untuk mengubah kata sandi anda, jika anda tidak meminta perubahan kata sandi abaikan email ini");
DEFINE("_MEMBER_SEND_SUCCESS","berhasil dikirim");
DEFINE("_MEMBER_EMAIL_USERD","email ini sudah pernah digunakan sebelumnya");
DEFINE("_MEMBER_PWD_ERROR_MSG1","kata sandi lama dan baru tidak bisa dikosongkan");
DEFINE("_MEMBER_PWD_ERROR_MSG2","kata sandi lama eror");
DEFINE("_MEMBER_UPDATE_SUCCESS","berhasil diubah");
DEFINE("_MEMBER_SID_EMPTY","nomer ID tidak bisa dikosongkan");
DEFINE("_MEMBER_SID_REPEAT","nomer ID sudah pernah didaftarkan");
DEFINE("_MEMBER_LOGINID_ENPTY","akun tidak bisa dikosongkan");
DEFINE("_MEMBER_EMAIL_USED","email ini sudah pernah diregistrasi sebelumnya");
DEFINE("_MEMBER_CARD_EMPTY","nomer member tidak bisa dikosongkan");
DEFINE("_MEMBER_CARD_USED","nomer member ini sudah pernah digunakan sebelumnya");
DEFINE("_MEMBER_NO_DISTRIBUTOR","info member tidak ditemukan");
DEFINE("_MEMBER_LOGIN_SUCCESS","login berhasil");
DEFINE("_MEMBER_LOGIN_FAIL","login gagal");

//最新消息
DEFINE("_NEWS_NO_DATA","tidak ada pesan itu");

//商品
DEFINE("_PRODUCT_NO_DATA","barang tidak ditemukan");

//EWAYS
DEFINE("_EWAYS_NO_VIDEO","video tidak tersedia");
DEFINE("_EWAYS_NO_AD","iklan tidak tersedia");
DEFINE("_EWAYS_NO_ADVROLLS_IMAGE","carousel belum menupload");
DEFINE("_EWAYS_SELECT_TAKETYPE","pilih metode pengambilan");
DEFINE("_EWAYS_SELECT_PAYTYPE","pilih metode pembayaran");
DEFINE("_EWAYS_SELECT_PRODUCT","pilih barang");
DEFINE("_EWAYS_CART_MSG1","ada barang bonus di keranjang belanja anda, kosongkan keranjang belanja anda dulu");
DEFINE("_EWAYS_CART_MSG2","ada barang normal di keranjang belanja anda, kosongkan keranjang belanja anda dulu");
DEFINE("_EWAYS_SUCCESS","operasi berhasil");
DEFINE("_EWAYS_ADDPROD","tambahan");
DEFINE("_EWAYS_CART_EMPTY","keranjang belanja kosong");
DEFINE("_EWAYS_TAKE_TYPE1","pengiriman ke rumah");
DEFINE("_EWAYS_TAKE_TYPE2","ambil sendiri");
DEFINE("_EWAYS_TAKE_TYPE3","bayar ditempat");
DEFINE("_EWAYS_PAY_TYPE1","bayar ditempat");
DEFINE("_EWAYS_PAY_TYPE2","transfer ATM");
DEFINE("_EWAYS_PAY_TYPE3","kartu kredit online");
DEFINE("_EWAYS_PAY_TYPE4","ATM virtual account");
DEFINE("_EWAYS_PAY_TYPE5","bayar di toko");
DEFINE("_EWAYS_PAY_TYPE6","kartu kredit online");
DEFINE("_EWAYS_PAY_TYPE7","transfer ATM");
DEFINE("_EWAYS_ESIGNUO_MSG1","Pemberitahuan keanggotaan sukses e-member");
DEFINE("_EWAYS_ESIGNUO_MSG2","selamat datang");
DEFINE("_EWAYS_ESIGNUO_MSG3","akun");
DEFINE("_EWAYS_ESIGNUO_MSG4","kata sandi");
DEFINE("_EWAYS_ESIGNUO_MSG5","no member");
DEFINE("_EWAYS_ESIGNUO_MSG6","mohon ingat nomer member anda, jika anda membutuhkan cari fisik member, anda bisa mengisi formulir permintaan kartu dan serahkan ke dealer terdekat atau kantor kami. Terima kasih");
DEFINE("_EWAYS_ESIGNUO_MSG7","");
DEFINE("_EWAYS_ESIGNUO_MSG8","email ini dikirim secacra otomatis dan dikirim dari sistem. Mohon untuk tidak membalas email ini, jika anda memiliki pertanyaan, silahkan hubungi customer service kami");



//後台員工
DEFINE("_ADMINMANAGERS_SAME_USER","catatan karyawan dengan akun yang sama sudah ada");
DEFINE("_ADMINMANAGERS_NO_SELECT","tidak ada barang yang dipilih");

//後台-快速連結
DEFINE("_BOTTOMMENU_USED_NOT_DELETE","halaman ini sudah digunakan dan tidak bisa dihapus");


//後台-員工管理
DEFINE("_MEMBERS_EXPORT_DATA","export data member");
DEFINE("_MEMBERS_AUDIT_MSG1","lulus peninjauan distributor");
DEFINE("_MEMBERS_AUDIT_MSG2","bonus kumulatif belum mencapai jumlah minimum");
DEFINE("_MEMBERS_SALESCHK0","member umum");
DEFINE("_MEMBERS_SALESCHK3","tinjauan distributor");
DEFINE("_MEMBERS_SALESCHK2","tinjauan distributor sedang berlangsung");
DEFINE("_MEMBERS_SALESCHK1","member distributor resmi");
DEFINE("_MEMBERS_MEMTYPE1","e-daftar");
DEFINE("_MEMBERS_MEMTYPE2","umum");
DEFINE("_MEMBERS_EXCEL_TITLE","member ID, nomer kartu identitas, nama member, alamat surat menyurat, telepon, telepon genggam, alamat, email, tanggal lahir, tanggal pendaftaran (tanggal pembayaran), ID upline, nama upline, nomer telepon upline, nomer telepon genggam upline");
DEFINE("_MEMBERS_SAME_EMAIL","email yang sama sudah ada");
DEFINE("_MEMBERS_SAME_CARD_NO","nomer kartu member yang sama sudah ada");
DEFINE("_MEMBERS_SAME_NO","member ID yang sama sudah ada");
DEFINE("_MEMBERS_LOGOUT","keluar");
DEFINE("_MEMBERS_LOGIN","masuk");


//後台-訂單
DEFINE("_ORDER_ORDER_EMPTY","pesanan ini tidak ada");
DEFINE("_ORDER_UPDATE_PAYDATE","perbaharui tanggal pembayaran");
DEFINE("_ORDER_UPDATE_INVOICE_INFO","perbaharui info resi");
DEFINE("_ORDER_UPDATE_RECEIVE","perbaharui info penerima");
DEFINE("_ORDER_UNABLE_MERGE","tidak bisa menggabungkan pesanan");
DEFINE("_ORDER_UNABLE_MERGE_MSG1","tidak bisa menggabungkan pembayaran ditempat");
DEFINE("_ORDER_UNABLE_MERGE_MSG2","tidak bisa menggabungkan pembayaran pesanan");
DEFINE("_ORDER_UNABLE_MERGE_MSG3","tidak bisa menggabungkan pemesanan member yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG4","Tidak dapat menggabungkan pesanan metode pembayaran yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG5","Tidak dapat menggabungkan urutan metode pengumpulan yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG6","Tidak dapat menggabungkan pesanan status pesanan yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG7","Tidak dapat menggabungkan pesanan dengan waktu pengiriman yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG8","Tidak dapat menggabungkan pesanan penerima yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG9","Tidak dapat menggabungkan pesanan kontak penerima yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG10","Tidak dapat menggabungkan pesanan alamat penerima yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG11","Tidak dapat menggabungkan pesanan tanggal pengiriman yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG12","Tidak dapat menggabungkan pesanan tagihan yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG13","Tidak dapat menggabungkan pesanan kop tagihan yang berbeda");
DEFINE("_ORDER_UNABLE_MERGE_MSG14","tidak bisa menggabungkan tagihan nomer serial pemesanan");
DEFINE("_ORDER_SHIPPING_MSG","catatan pengiriman");
DEFINE("_ORDER_SHIPPING_MSG1","");
DEFINE("_ORDER_SHIPPING_MSG2","");
DEFINE("_ORDER_SHIPPING_MSG3","");
DEFINE("_ORDER_SHIPPING_MSG4","");
DEFINE("_ORDER_SHIPPING_MSG5","");
DEFINE("_ORDER_SHIPPING_MSG6","");
DEFINE("_ORDER_SHIPPING_MSG7","");
DEFINE("_ORDER_SHIPPING_MSG8","");
DEFINE("_ORDER_SHIPPING_MSG9","");
DEFINE("_ORDER_SHIPPING_MSG10","");
DEFINE("_ORDER_SHIPPING_MSG11","");
DEFINE("_ORDER_SHIPPING_MSG12","");
DEFINE("_ORDER_SHIPPING_MSG13","");
DEFINE("_ORDER_INVOICETYPESTR0","");
DEFINE("_ORDER_INVOICETYPESTR1","");
DEFINE("_ORDER_INVOICETYPESTR2","");

DEFINE("_ORDER_EXPORT_STR1","Jumlah order");
DEFINE("_ORDER_EXPORT_STR2","Tanggal pemesanan");
DEFINE("_ORDER_EXPORT_STR3","jumlah yang dikecualikan pajak pesanan");
DEFINE("_ORDER_EXPORT_STR4","pesanan Pajak termasuk jumlah");
DEFINE("_ORDER_EXPORT_STR5","Tanda Anggota");
DEFINE("_ORDER_EXPORT_STR6","Pembeli");
DEFINE("_ORDER_EXPORT_STR7","total PV");
DEFINE("_ORDER_EXPORT_STR8","total BV");
DEFINE("_ORDER_EXPORT_STR9","Pembeli sebenarnya");
DEFINE("_ORDER_EXPORT_STR10","alamat pengiriman");
DEFINE("_ORDER_EXPORT_STR11","Jumlah order");
DEFINE("_ORDER_EXPORT_STR12","Barang");
DEFINE("_ORDER_EXPORT_STR13","Nomor produk");
DEFINE("_ORDER_EXPORT_STR14","Nama Produk");
DEFINE("_ORDER_EXPORT_STR15","Kuantitas");
DEFINE("_ORDER_EXPORT_STR16","patokan harga");
DEFINE("_ORDER_EXPORT_STR17","Jumlah tidak termasuk pajak");
DEFINE("_ORDER_EXPORT_STR18","Jumlah termasuk pajak");
DEFINE("_ORDER_EXPORT_STR19","Penerima");
DEFINE("_ORDER_EXPORT_STR20","Telepon penerima");
DEFINE("_ORDER_EXPORT_STR21","warna");
DEFINE("_ORDER_EXPORT_STR22","ukuran");
DEFINE("_ORDER_EXPORT_STR23","Harga satuan asli");
DEFINE("_ORDER_EXPORT_STR24","jenis aktivitas");
DEFINE("_ORDER_EXPORT_STR25","Empat digit terakhir nomor kartu");
DEFINE("_ORDER_EXPORT_STR26","Catatan");
DEFINE("_ORDER_EXPORT_MSG","Hasil penelusuran terlalu banyak, harap tetapkan kriteria filter sebelum mengekspor");
DEFINE("_ORDER_EXPORT_STR27","hadiah");
DEFINE("_ORDER_EXPORT_STR28","ongkos kirim");


//後台-商品管理
DEFINE("_PRODUCTS_SELECT_FILE","Silakan pilih file");
DEFINE("_PRODUCTS_EXCEL_FILE","Batasan format file Excel: xls, xlsx");
DEFINE("_PRODUCTS_IMPORT_MSG1","Barang");
DEFINE("_PRODUCTS_IMPORT_MSG2","");
DEFINE("_PRODUCTS_IMPORT_MSG3","Beberapa file tidak dapat diimpor, periksa dan unggah lagi");
DEFINE("_PRODUCTS_IMPORT_MSG4","Impor item selesai, silakan masuk ke halaman produk untuk mengisi kolom lain dan aktifkan item");
DEFINE("_PRODUCTS_COPY_SUCCESS","berhasil disalin");
DEFINE("_PRODUCTS_ROOT","Direktori root");
DEFINE("_PRODUCTS_DELETE_ERROR","Ada pesanan yang berisi item ini, tidak dapat dihapus");


//後台-庫存管理
DEFINE("_PROINSTOCK_DANGER","bahaya");
DEFINE("_PROINSTOCK_SAFE","aman");


//雜項
DEFINE("_SET_PM_MIN","Minimal must bigger than 25");


























?>