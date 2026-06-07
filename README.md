***Laporan UTS Praktikum Pemrograman Web Fullstack***

koperasi-digital

Oleh Developer : Adi Rifai W. ( 2305101034 ) / 6B 

***ERD Database & Relasi***

(<img width="762" height="492" alt="ERD Database   Relasi" src="https://github.com/user-attachments/assets/c010d75e-d2be-4634-bbe6-36586f0c9aed" />

Berdasarkan Foreign Key (FK) yang dirancang pada database, berikut adalah relasi antar tabelnya:

- Users ke Pinjaman (One-to-Many) Satu anggota (users) dapat melakukan banyak kali transaksi setoran uang. Foreign key user_id berada di tabel simpanan. 
- Pinjaman ke Cicilan (One-to-Many) Satu kontrak pinjaman akan dipecah menjadi banyak tagihan cicilan bulanan sesuai tenornya. Foreign key pinjaman_id berada di tabel cicilans.
- Cicilan ke Transaksi (One-to-Many) SSatu tagihan cicilan bisa memiliki histori pembayaran (jika dicicil sebagian atau bertahap). Foreign key cicilan_id berada di tabel transaksi.
- Users ke Simpanan (One-to-Many) Satu anggota dapat menyetor uang simpanan berkali-kali secara historis.	user_id berada di tabel simpanan.

***Daftar Endpoint API***

Sistem ini menyediakan restful API yang dilindungi oleh **Laravel Sanctum**. Untuk mengakses endpoint yang terproteksi (selain Guest), pastikan Anda menyertakan Header berikut pada setiap *request*:

- `Accept: application/json`
- `Authorization: Bearer {access_token}`
  
1. Authentication (Auth)
Mengelola pendaftaran, sesi masuk, dan profil pengguna.

| Method | Endpoint | Akses / Role | Deskripsi | Parameter (Body JSON) |
| :--- | :--- | :--- | :--- | :--- |
| **POST** | `/api/register` | Guest | Mendaftarkan anggota baru | `name`, `email`, `password` |
| **POST** | `/api/login` | Guest | Login dan mendapatkan Token | `email`, `password` |
| **GET** | `/api/user` | Auth | Melihat profil user yang sedang login | *-* |
| **POST** | `/api/logout` | Auth | Menghapus token (Logout) | *-* |

2. Simpanan
Mengelola kas masuk dari anggota (Simpanan Pokok, Wajib, Sukarela).

| Method | Endpoint | Akses / Role | Deskripsi | Parameter (Body JSON) |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | `/api/simpanan` | Anggota | Melihat histori simpanan milik sendiri | *-* |
| **GET** | `/api/admin/simpanan`| Admin | Melihat semua data simpanan koperasi | *-* |
| **POST** | `/api/simpanan` | Admin | Input data setoran simpanan anggota | `user_id`, `jenis_simpanan`, `nominal` |

3. Pinjaman
Mengelola pengajuan dan persetujuan kontrak pinjaman uang.

| Method | Endpoint | Akses / Role | Deskripsi | Parameter (Body JSON) |
| :--- | :--- | :--- | :--- | :--- |
| **POST** | `/api/pinjaman` | Anggota | Mengajukan pinjaman baru | `nominal_pinjam`, `tenor_bulan`, `bunga_persen` |
| **GET** | `/api/pinjaman` | Anggota | Melihat histori pengajuan diri sendiri | *-* |
| **GET** | `/api/admin/pinjaman`| Admin | Melihat semua daftar pengajuan masuk | *-* |
| **PUT** | `/api/pinjaman/{id}/approve` | Admin | Menyetujui/menolak pinjaman | `status_approval` ('approved'/'rejected') |

4. Cicilan & Transaksi
Mengelola tagihan bulanan dan rekam jejak pembayaran.

| Method | Endpoint | Akses / Role | Deskripsi | Parameter (Body JSON) |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | `/api/pinjaman/{id}/cicilan` | Anggota | Melihat daftar tagihan bulanan | *-* |
| **POST** | `/api/transaksi/bayar`| Anggota | Membayar cicilan tagihan tertentu | `cicilan_id`, `nominal_bayar`, `metode_pembayaran` |
| **GET** | `/api/transaksi` | Admin | Melihat histori seluruh transaksi | *-* |


***Testing & Dokumentasi API (Postman)***

1. Auth
   - [POST] /api/register ( Register ) 
     
(<img width="960" height="504" alt="image" src="https://github.com/user-attachments/assets/739a60e3-879b-4bcf-a06d-49e42ca7c455" />)

    -[POST] /api/login ( Login ) 
(<img width="1920" height="1008" alt="image" src="https://github.com/user-attachments/assets/664ac1bb-5d4e-4ee2-969d-6b997553e77d" />) 

    - [POST] /api/pengajuanpinjaman ( Pinjaman )
(<img width="1920" height="1008" alt="image" src="https://github.com/user-attachments/assets/b12255ff-ec9a-4fe1-9e80-b6e86a8ca5c3" />)

    - [POST] /api/pinjaman 
(<img width="1920" height="1008" alt="image" src="https://github.com/user-attachments/assets/8e2be774-91ff-4d00-ab64-e62ecba3aae7" />)
