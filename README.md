Laporan UTS Praktikum Pemrograman Web Fullstack

koperasi-digital

Oleh Developer : Adi Rifai W. ( 2305101034 ) / 6B 

ERD Database & Relasi 


(<img width="762" height="492" alt="ERD Database   Relasi" src="https://github.com/user-attachments/assets/c010d75e-d2be-4634-bbe6-36586f0c9aed" />


Berdasarkan Foreign Key (FK) yang dirancang pada database, berikut adalah relasi antar tabelnya:

- Users ke Pinjaman (One-to-Many) Satu anggota (users) dapat melakukan banyak kali transaksi setoran uang. Foreign key user_id berada di tabel simpanan. 
- Pinjaman ke Cicilan (One-to-Many) Satu kontrak pinjaman akan dipecah menjadi banyak tagihan cicilan bulanan sesuai tenornya. Foreign key pinjaman_id berada di tabel cicilans.
- Cicilan ke Transaksi (One-to-Many) SSatu tagihan cicilan bisa memiliki histori pembayaran (jika dicicil sebagian atau bertahap). Foreign key cicilan_id berada di tabel transaksi.
- Users ke Simpanan (One-to-Many) Satu anggota dapat menyetor uang simpanan berkali-kali secara historis.	user_id berada di tabel simpanan.

Daftar Endpoint API 

Testing & Dokumentasi API (Postman) 

1. Auth
   - [POST] /api/register
     
(<img width="960" height="504" alt="image" src="https://github.com/user-attachments/assets/739a60e3-879b-4bcf-a06d-49e42ca7c455" />
)
