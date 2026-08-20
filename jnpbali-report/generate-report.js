const fs = require("fs");
const path = require("path");
const {
  Document,
  Packer,
  Paragraph,
  TextRun,
  HeadingLevel,
  AlignmentType,
  Table,
  TableRow,
  TableCell,
  WidthType,
  BorderStyle,
  ShadingType,
} = require("docx");

const tableBorder = { style: BorderStyle.SINGLE, size: 1, color: "CCCCCC" };
const cellBorders = {
  top: tableBorder,
  bottom: tableBorder,
  left: tableBorder,
  right: tableBorder,
};

function heading(text, level = HeadingLevel.HEADING_1) {
  return new Paragraph({ text, heading: level, spacing: { before: 240, after: 120 } });
}

function para(text, opts = {}) {
  return new Paragraph({
    spacing: { after: 120 },
    ...opts,
    children: [new TextRun({ text, size: 22 })],
  });
}

function bullet(text) {
  return new Paragraph({
    text,
    bullet: { level: 0 },
    spacing: { after: 60 },
  });
}

function boldBullet(label, rest) {
  return new Paragraph({
    spacing: { after: 60 },
    bullet: { level: 0 },
    children: [
      new TextRun({ text: label, bold: true, size: 22 }),
      new TextRun({ text: rest, size: 22 }),
    ],
  });
}

function makeTable(headers, rows) {
  const headerRow = new TableRow({
    tableHeader: true,
    children: headers.map(
      (h) =>
        new TableCell({
          borders: cellBorders,
          width: { size: Math.floor(9000 / headers.length), type: WidthType.DXA },
          shading: { fill: "E8E8E8", type: ShadingType.CLEAR },
          children: [new Paragraph({ children: [new TextRun({ text: h, bold: true, size: 20 })] })],
        })
    ),
  });

  const dataRows = rows.map(
    (row) =>
      new TableRow({
        children: row.map(
          (cell) =>
            new TableCell({
              borders: cellBorders,
              width: { size: Math.floor(9000 / headers.length), type: WidthType.DXA },
              children: [new Paragraph({ children: [new TextRun({ text: cell, size: 20 })] })],
            })
        ),
      })
  );

  return new Table({
    width: { size: 9000, type: WidthType.DXA },
    rows: [headerRow, ...dataRows],
  });
}

const doc = new Document({
  creator: "Cursor Agent",
  title: "Laporan UI/UX jnpbali.com",
  description: "Laporan UI/UX perspektif turis untuk jnpbali.com",
  sections: [
    {
      properties: {},
      children: [
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 200 },
          children: [
            new TextRun({ text: "LAPORAN UI/UX", bold: true, size: 36 }),
          ],
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 120 },
          children: [
            new TextRun({ text: "jnpbali.com — Perspektif Turis", size: 28, italics: true }),
          ],
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 400 },
          children: [
            new TextRun({ text: "Tanggal: 16 Agustus 2026", size: 22, color: "666666" }),
          ],
        }),

        heading("Ringkasan Eksekutif"),
        para(
          "Saya meninjau jnpbali.com dengan skenario: turis di Bali ingin memesan kaos custom dari foto liburan, dan ingin tahu harga, proses, waktu, serta cara bayar — tanpa menebak-nebak."
        ),
        para(
          "Konsep bisnis jelas dan menarik: foto liburan → desain custom → dikirim ke hotel. Namun pengalaman website masih membingungkan untuk turis, terutama karena: (1) alur pemesanan di homepage tidak sesuai alur sebenarnya, (2) harga dan informasi pembayaran tidak konsisten, (3) terlalu banyak fokus B2B (affiliate, corporate), dan (4) produk custom terasa seperti toko biasa padahal prosesnya multi-langkah."
        ),
        para("Skor UX turis (perkiraan): 5.5/10 — ide bagus, eksekusi perlu disederhanakan.", {
          children: [new TextRun({ text: "Skor UX turis (perkiraan): 5.5/10 — ide bagus, eksekusi perlu disederhanakan.", bold: true, size: 22 })],
        }),

        heading("Apa yang Sudah Bagus", HeadingLevel.HEADING_2),
        makeTable(
          ["Aspek", "Keterangan"],
          [
            ["Value proposition", "Hero \"Turn Your Bali Memories Into Wearable Art\" langsung menjelaskan manfaat"],
            ["Halaman How It Works", "Detail 7 langkah di /how-it-works/ cukup lengkap"],
            ["FAQ", "Menjawab banyak pertanyaan turis (upload setelah bayar, revisi, deadline sebelum pulang)"],
            ["Gallery", "Konsep before/after (The Trip → The Custom Print) membantu bayangan hasil"],
            ["Pilihan desain", "11 gaya (AI + Original Design) memberi variasi"],
            ["Delivery ke hotel", "Selling point kuat untuk turis — perlu lebih menonjol"],
          ]
        ),
        new Paragraph({ spacing: { after: 200 } }),

        heading("Masalah Kritis (Prioritas Tinggi)", HeadingLevel.HEADING_2),

        heading("1. Alur di Homepage Menyesatkan", HeadingLevel.HEADING_3),
        para("Homepage (4 langkah): Send Your Photo → Choose a Style → Approve the Proof → Delivered to You"),
        para("Alur sebenarnya (FAQ + halaman produk): Pilih produk & bayar → Upload foto via link pribadi → Terima proof (2–3 hari kerja) → Approve/revisi → Produksi & kirim ke hotel"),
        para("Dampak: Turis mengira harus upload foto dulu sebelum order. Ini salah urutan dan bisa bikin frustrasi."),
        para("Rekomendasi: Samakan homepage dengan alur nyata:"),
        bullet("Pilih produk & bayar"),
        bullet("Upload foto liburan Anda"),
        bullet("Review & approve desain"),
        bullet("Dikirim ke hotel sebelum pulang"),
        para("Tambahkan timeline: \"Proof dalam 2–3 hari kerja setelah upload\"."),

        heading("2. Harga $0.01 — Merusak Kepercayaan", HeadingLevel.HEADING_3),
        para("Hampir semua produk (Custom Trip Tee, Mug, Hoodie) menampilkan $0.01, yang terlihat seperti placeholder/testing."),
        para("Dampak: Turis ragu ini website resmi atau belum siap jual."),
        bullet("Pasang harga nyata (mis. $35–55 untuk tee custom)"),
        bullet("Tampilkan \"From $XX\" jika harga bervariasi per gaya"),
        bullet("Bedakan harga Original Design (gratis) vs Premium AI (add-on) di dropdown, bukan hanya di FAQ"),

        heading("3. Informasi Pembayaran Bertentangan", HeadingLevel.HEADING_3),
        makeTable(
          ["Lokasi", "Informasi"],
          [
            ["Footer", "Payment: Direct Bank Transfer"],
            ["Halaman produk", "Tombol Pay with PayPal"],
            ["Shop", "Pre-order, konfirmasi via WhatsApp"],
          ]
        ),
        new Paragraph({ spacing: { after: 120 } }),
        para("Dampak: Turis internasional bingung — bayar lewat PayPal, transfer bank, atau WhatsApp?"),
        para("Rekomendasi — satu blok jelas di homepage & checkout:"),
        bullet("PayPal & kartu kredit diterima"),
        bullet("Transfer bank (IDR/USD) tersedia"),
        bullet("Konfirmasi via WhatsApp setelah order"),

        heading("4. CTA dan Navigasi Membingungkan", HeadingLevel.HEADING_3),
        makeTable(
          ["Tombol", "Tujuan"],
          [
            ["Start Your Design", "/shop/"],
            ["Start Your Project", "/request-a-quote/ (B2B/group)"],
            ["Become a Partner", "Affiliate"],
          ]
        ),
        new Paragraph({ spacing: { after: 120 } }),
        para("Menu ramai: About Us, Services, Gallery, How It Works, Shop, Affiliate, Contacts — 7 item plus Login/Register."),
        para("Rekomendasi untuk turis:"),
        bullet("CTA utama: \"Order Custom Souvenir\" → langsung ke Custom Trip Tee"),
        bullet("Sembunyikan atau pindahkan Affiliate ke footer"),
        bullet("Tambahkan ikon keranjang di header"),
        bullet("Pertimbangkan floating WhatsApp button (sangat familiar di Bali)"),

        heading("5. Tidak Ada Upload Foto di Halaman Produk", HeadingLevel.HEADING_3),
        para("Di halaman Custom Trip Tee tidak ada field upload, padahal kategori produk bertuliskan \"Photo Upload Enabled\". Informasi upload ada di tab Description, tapi turis jarang scroll ke sana."),
        para("Rekomendasi — banner di atas tombol Add to Cart:"),
        para("\"Foto diupload SETELAH pembayaran. Anda akan dapat link pribadi via email/WhatsApp.\""),

        heading("Masalah Menengah", HeadingLevel.HEADING_2),

        heading("6. Pilihan Design Style Tanpa Preview Visual", HeadingLevel.HEADING_3),
        para("11 gaya hanya berupa dropdown teks (Anime, Graffiti, Watercolor, dll.) tanpa contoh gambar."),
        para("Rekomendasi: Grid thumbnail per gaya (bisa dari Gallery), atau modal \"See examples\" per style."),

        heading("7. Informasi Stok Tidak Masuk Akal", HeadingLevel.HEADING_3),
        para("Produk custom menampilkan \"981 in stock\" — tidak relevan untuk made-to-order."),
        para("Rekomendasi: Ganti dengan \"Made to order — production starts after design approval\" atau sembunyikan stok."),

        heading("8. Shop vs Produk — Pesan Bertentangan", HeadingLevel.HEADING_3),
        makeTable(
          ["Shop page", "Product page"],
          [
            ["Konfirmasi size & color via WhatsApp", "Ada dropdown Size & color picker"],
          ]
        ),
        new Paragraph({ spacing: { after: 120 } }),
        para("Rekomendasi: Pilih satu alur. Jika sudah ada selector di website, hapus kalimat WhatsApp dari shop intro."),

        heading("9. Dua Nomor Telepon Berbeda Negara", HeadingLevel.HEADING_3),
        bullet("+1 712-339-9294 (US)"),
        bullet("+62 877-4009-8436 (Indonesia)"),
        para("Rekomendasi: Untuk turis di Bali, prioritaskan nomor Indonesia + WhatsApp. Nomor US bisa di halaman About/Contact saja."),

        heading("10. Konten Brand Terlalu \"Inside Baseball\"", HeadingLevel.HEADING_3),
        para("Frasa seperti \"DHB (Double Helix Brothers)\", \"One Tribe. Same Vibe\", \"Mood Mode\" (coming soon) kurang relevan untuk turis yang cuma mau kaos souvenir."),
        para("Rekomendasi — hero & shop fokus bahasa turis:"),
        bullet("\"Your Bali photo on a premium tee\""),
        bullet("\"Free delivery to your hotel\""),
        bullet("\"Ready before you fly home\""),

        heading("11. Kurang Social Proof", HeadingLevel.HEADING_3),
        bullet("Reviews (0) di semua produk"),
        bullet("Gallery masih sedikit contoh"),
        bullet("Tidak ada testimoni turis"),
        para("Rekomendasi: Foto pelanggan + hotel delivery, rating Google/TripAdvisor, atau \"500+ trip tees delivered in Bali\"."),

        heading("12. Login/Register Terasa Wajib", HeadingLevel.HEADING_3),
        para("Tombol \"Login / Register\" menonjol di header, padahal turis biasanya ingin guest checkout."),
        para("Rekomendasi: Guest checkout default; login opsional untuk tracking order."),

        heading("Masalah Kecil / Polish", HeadingLevel.HEADING_2),
        makeTable(
          ["Issue", "Saran"],
          [
            ["Cookie banner besar & persisten", "Persempit; jangan tutupi CTA utama"],
            ["Tag teknis (photo-merch, Photo Upload Enabled)", "Sembunyikan dari tampilan user"],
            ["Wishlist", "Kurang relevan untuk turis one-time"],
            ["Lightbox gambar produk", "Pastikan tidak mengganggu klik color picker"],
            ["Bahasa", "Pastikan Bahasa Indonesia tersedia — penting untuk turis lokal & guide"],
            ["FAQ hanya di footer", "Link FAQ dari halaman produk & checkout"],
            ["Perbedaan 4 vs 7 langkah How It Works", "Gunakan versi yang sama di semua halaman"],
          ]
        ),
        new Paragraph({ spacing: { after: 200 } }),

        heading("User Journey Ideal untuk Turis", HeadingLevel.HEADING_2),
        bullet("Homepage → Custom Trip Tee"),
        bullet("Pilih style + size + warna"),
        bullet("Add to Cart → Checkout + bayar"),
        bullet("Upload foto via link pribadi"),
        bullet("Approve proof via WhatsApp"),
        bullet("Terima di hotel"),
        para("Setiap langkah perlu satu pesan jelas: \"Anda di langkah X dari 5\"."),

        heading("Prioritas Perbaikan (Quick Wins)", HeadingLevel.HEADING_2),
        makeTable(
          ["#", "Perbaikan", "Effort", "Impact"],
          [
            ["1", "Perbaiki harga placeholder ($0.01)", "Rendah", "Sangat tinggi"],
            ["2", "Koreksi urutan How It Works di homepage", "Rendah", "Sangat tinggi"],
            ["3", "Banner \"Upload foto setelah bayar\" di produk", "Rendah", "Tinggi"],
            ["4", "Satu pesan payment yang konsisten", "Rendah", "Tinggi"],
            ["5", "CTA tunggal untuk turis + kurangi menu", "Sedang", "Tinggi"],
            ["6", "Preview visual design styles", "Sedang", "Tinggi"],
            ["7", "WhatsApp floating button", "Rendah", "Sedang"],
            ["8", "Social proof & testimoni", "Sedang", "Sedang"],
          ]
        ),
        new Paragraph({ spacing: { after: 200 } }),

        heading("Kesimpulan", HeadingLevel.HEADING_2),
        para(
          "jnpbali.com punya positioning unik yang cocok untuk turis Bali: custom apparel dari foto liburan + delivery ke hotel. Itu diferensiator kuat."
        ),
        para(
          "Yang perlu diperbaiki bukan konsepnya, melainkan kejelasan alur, kepercayaan (harga & pembayaran), dan fokus UX untuk turis — bukan affiliate/corporate di jalur utama pembelian."
        ),
        para(
          "Jika tiga hal ini diselesaikan dulu — harga nyata, alur homepage benar, dan banner upload setelah bayar — pengalaman turis bisa naik signifikan tanpa redesign penuh."
        ),
        new Paragraph({
          spacing: { before: 300 },
          children: [
            new TextRun({
              text: "Laporan ini berdasarkan peninjauan langsung website jnpbali.com per 16 Agustus 2026.",
              italics: true,
              size: 20,
              color: "666666",
            }),
          ],
        }),
      ],
    },
  ],
});

const outputPath = path.join(
  process.env.USERPROFILE,
  "Documents",
  "Laporan-UI-UX-jnpbali.com.docx"
);

Packer.toBuffer(doc).then((buffer) => {
  fs.writeFileSync(outputPath, buffer);
  console.log("Created:", outputPath);
});
