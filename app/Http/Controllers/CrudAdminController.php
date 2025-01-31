<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class CrudAdminController extends Controller
{

//KATEGORI
        public function kategoriTambah()
        {
            return view('admin.kategori_tambah');
        }

        public function kategoriSimpan(Request $request)
        {
            $request->validate([
                'nama' => 'required',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $id_toko = auth()->user()->id_toko;
            $nama = $request->nama;
            $foto = $request->file('foto');
            $namafoto = $foto->getClientOriginalName();
            $foto->move(public_path('asset/image/image-admin/kategori'), $namafoto);

            Kategori::create([
                'id_toko' => $id_toko,
                'nama_kategori' => $nama,
                'foto_kategori' => $namafoto,
            ]);

            return redirect()->route('datakategori')->with('success', 'Input data kategori berhasil.');
        }
        public function kategoriHapus($id)
        {
            $kategori = Kategori::find($id);

            if (!$kategori) {
                return redirect()->route('datakategori')->with('error', 'Kategori tidak ditemukan.');
            }

            $kategori->delete();

            return redirect()->route('datakategori')->with('success', 'Kategori berhasil dihapus.');
        }

        public function kategoriShow($id)
        {
            $kategori = Kategori::find($id);

            if (!$kategori) {
                return redirect()->back()->with('error', 'Kategori tidak ditemukan');
            }

            return view('admin.kategori_edit', compact('kategori'));
        }

        public function kategoriUpdate(Request $request, $id)
        {
            $request->validate([
                'nama' => 'required',
            ]);

            $kategori = Kategori::find($id);

            if (!$kategori) {
                return redirect()->back()->with('error', 'Kategori tidak ditemukan');
            }

            $kategori->nama_kategori = $request->nama;
            $kategori->save();

            return redirect()->route('datakategori')->with('success', 'Kategori berhasil diperbarui');
        }


//PRODUK
        public function produkTambah()
        {
            
            $supplier = Supplier::all();
            $kategori = Kategori::all();

            return view('admin.produk_tambah', compact('supplier', 'kategori'));

        }
        public function produkSimpan(Request $request)
        {
            $request->validate([
                    'id_supplier' => 'required',
                    'id_kategori' => 'required',
                    'kode' => 'required',
                    'nama' => 'required',
                    'beli' => 'required',
                    'jual' => 'required',
                    'coret' => 'required',
                    'stock' => 'required',
                    'date' => 'required',
                    'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'keterangan' => 'required',
                ]);

                // Upload foto
                if ($request->hasFile('foto')) {
                    $foto = $request->file('foto');
                    $namaFoto = $foto->getClientOriginalName();
                    $foto->move('asset/image/image-admin/produk', $namaFoto);
                    $fotoPath = 'asset/image/image-admin/produk/' . $namaFoto;
                }
                
                
                // Simpan data produk
                $produk = new Produk();
                $produk->id_supplier = $request->id_supplier;
                $produk->id_kategori = $request->id_kategori;
                $produk->kode_produk = $request->kode;
                $produk->nama_produk = $request->nama;
                $produk->biaya_produk = $request->beli;
                $produk->jual_produk = $request->jual;
                $produk->harga_coret = $request->coret;
                $produk->stock_produk = $request->stock;
                $produk->tanggal_produksi = $request->date;
                $produk->foto_produk = $namaFoto;
                $produk->keterangan_produk = $request->keterangan;
                $produk->save();

                return redirect()->route('dataproduk')->with('success', 'Produk berhasil disimpan');
        }

        public function produkHapus($id){
            $produk = Produk::find($id);

            if (!$produk) {
                return redirect()->route('dataproduk')->with('error', 'Produk tidak ditemukan.');
            }

            $produk->delete();

            return redirect()->route('dataproduk')->with('success', 'Produk berhasil dihapus.');
        }

        public function produkShow($id)
        {
            $supplier = Supplier::all();
            $kategori = Kategori::all();
            $produk = Produk::find($id);

            if (!$produk) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan');
            }

            return view('admin.produk_edit', compact('produk', 'kategori', 'supplier'));
        }

        public function produkUpdate(Request $request, $id){
            $request->validate([
                'id_supplier' => 'required',
                'id_kategori' => 'required',
                'kode' => 'required',
                'nama' => 'required',
                'beli' => 'required',
                'jual' => 'required',
                'coret' => 'required',
                'stock' => 'required',
                'date' => 'required',
                'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'required',
            ]);

            // Upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFoto = $foto->getClientOriginalName();
                $foto->move('asset/image/image-admin/produk', $namaFoto);
                $fotoPath = 'asset/image/image-admin/produk/' . $namaFoto;
            }
            
            $produk = Produk::find($id);

            // if (!$produk) {
            //     return redirect()->back()->with('error', 'Produk tidak ditemukan');
            // }
            // Simpan data produk
            if(isset($namaFoto)){
                $produk->id_supplier = $request->id_supplier;
                $produk->id_kategori = $request->id_kategori;
                $produk->kode_produk = $request->kode;
                $produk->nama_produk = $request->nama;
                $produk->biaya_produk = $request->beli;
                $produk->jual_produk = $request->jual;
                $produk->harga_coret = $request->coret;
                $produk->stock_produk = $request->stock;
                $produk->tanggal_produksi = $request->date;
                $produk->foto_produk = $namaFoto;
                $produk->keterangan_produk = $request->keterangan;
                $produk->save();
            } else{
                $produk->id_supplier = $request->id_supplier;
                $produk->id_kategori = $request->id_kategori;
                $produk->kode_produk = $request->kode;
                $produk->nama_produk = $request->nama;
                $produk->biaya_produk = $request->beli;
                $produk->jual_produk = $request->jual;
                $produk->harga_coret = $request->coret;
                $produk->stock_produk = $request->stock;
                $produk->tanggal_produksi = $request->date;
                $produk->foto_produk = $produk->foto_produk;
                $produk->keterangan_produk = $request->keterangan;
                $produk->save();
            }
            // if (!$produk->save()) {
            //     return redirect()->back()->with('error', 'Gagal menyimpan produk');
            // }
            
            return redirect()->route('dataproduk')->with('success', 'Produk berhasil disimpan');
        }




            public function updateStatus($id_penjualan)
            {
                DB::table('penjualan')
                    ->where('id', $id_penjualan)
                    ->update(['status_pesanan' => 'Lunas']);

                // Lakukan tindakan lain yang diperlukan setelah mengubah status pesanan menjadi "Lunas"

                return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Lunas');
            }
            public function hapusPesanan($id_penjualan)
            {
                // Lakukan proses penghapusan pesanan berdasarkan $id_penjualan
                // Misalnya menggunakan metode delete pada model Penjualan

                $penjualan = Checkout::findOrFail($id_penjualan);
                $penjualan->delete();

                return redirect()->back()->with('success', 'Pesanan berhasil dihapus');
            }



            public function showTransaksiPenjualan($id)
            {
                // Ambil dari tabel Penjualan yang idnya ini
                $penjualan = DB::table('penjualan')
                    ->leftJoin('users', 'penjualan.id_user', '=', 'users.id')
                    ->where('penjualan.id', $id)
                    ->first();

                $produk = DB::table('penjualan_produk')
                    ->where('id_penjualan', $id)
                    ->get();

                return view('admin.transaksi-penjualan-produk', compact('penjualan', 'produk'));
            }
        
        public function userTambah(){
            return view('admin.user_tambah');
        }
        public function userSimpan(Request $request){
                    // Validasi input form
            $request->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'nomer' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'level' => 'required',
                'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Simpan data ke tabel users
            $user = new User;
            $user->nama_user = $request->input('nama');
            $user->alamat_user = $request->input('alamat');
            $user->telepon_user = $request->input('nomer');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->level = $request->input('level');

            // Simpan foto jika ada
            $foto = $request->file('foto');
            $namafoto = $foto->getClientOriginalName();
            $foto->move(public_path('admin/assets/img/avatars'), $namafoto);


            $user->foto_user = $namafoto;
            // Simpan data user
            $user->save();

            // Redirect atau tampilkan pesan sukses
            return redirect()->route('datauser')->with('success', 'Data pengguna berhasil disimpan.');   
        }

        
        public function userUpdate(Request $request, $id)
        {
            $request->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'nomer' => 'required',
                'email' => 'required|email',
                'password' => 'nullable|min:6',
                'foto' => 'image|max:2048',
                'level' => 'required',
            ]);

            $user = User::find($id);

            // Upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFoto = $foto->getClientOriginalName();
                $foto->move('admin/assets/img/avatars', $namaFoto);
                $fotoPath = 'admin/assets/img/avatars' . $namaFoto;

                // // Hapus foto sebelumnya jika ada
                // if ($user->foto_user) {
                //     Storage::delete($user->foto_user);
                // }

                $user->foto_user = $fotoPath;
            }

            // Simpan data user
            $user->nama_user = $request->nama;
            $user->alamat_user = $request->alamat;
            $user->telepon_user = $request->nomer;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->level = $request->level;
            $user->save();

            // Redirect ke halaman lain atau berikan respon sesuai kebutuhan Anda
            return redirect()->back()->with('success', 'Data user berhasil diupdate');
        }
        public function userShow($id)
        {
            $user = User::find($id);

            if (!$user) {
                return redirect()->back()->with('error', 'User tidak ditemukan');
            }

            return view('admin.user_edit', compact('user'));
        }

        public function userRegister(Request $request)
        {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'address' => 'required',
                'telp' => 'required',
                'password' => 'required',
            ]);

            $nama = $request->input('name');
            $email = $request->input('email');
            $alamat = $request->input('address');
            $telp = $request->input('telp');
            $password = Hash::make($request->input('password'));

            $user = new User();
            $user->id_toko = 1; // Ubah sesuai kebutuhan
            $user->nama_user = $nama;
            $user->alamat_user = $alamat;
            $user->telepon_user = $telp;
            $user->email = $email;
            $user->password = $password;
            $user->level = 'User';
            $user->save();

            // Lakukan tindakan lanjutan seperti redirect atau memberikan respons kepada pengguna
            return redirect()->route('login')->with('success', 'Data pengguna berhasil didaftarkan.'); 
        }
        public function registerShow(){
            return view('register');
        }
        public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Lakukan tindakan lanjutan seperti redirect atau memberikan respons kepada pengguna
        return redirect()->route('datauser')->with('success', 'Data pengguna berhasil dihapus'); 
    }
}
