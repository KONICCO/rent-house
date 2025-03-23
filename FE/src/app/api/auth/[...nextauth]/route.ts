import nextauth from "next-auth";
// Mengimpor library `next-auth` yang digunakan untuk mengimplementasikan otentikasi di aplikasi Next.js.

const handler = nextauth({});
// Membuat handler otentikasi menggunakan fungsi `nextauth`. 
// Parameter kosong `{}` berarti konfigurasi default digunakan. 
// Anda dapat menambahkan konfigurasi seperti provider, callback, dll., di dalam objek ini.

export {handler as GET, handler as POST};
// Mengekspor handler otentikasi sebagai metode HTTP `GET` dan `POST`. 
// Ini memungkinkan handler digunakan untuk menangani permintaan GET dan POST di rute ini.