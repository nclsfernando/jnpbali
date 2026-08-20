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

function para(text) {
  return new Paragraph({
    spacing: { after: 120 },
    children: [new TextRun({ text, size: 22 })],
  });
}

function bullet(text) {
  return new Paragraph({ text, bullet: { level: 0 }, spacing: { after: 60 } });
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
  title: "Daily Report J&P Bali — 17 Agustus 2026",
  description: "Laporan harian pengembangan UI/UX dan fitur e-commerce jnpbali.com",
  sections: [
    {
      properties: {},
      children: [
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 200 },
          children: [new TextRun({ text: "DAILY REPORT", bold: true, size: 36 })],
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 120 },
          children: [
            new TextRun({
              text: "Pengembangan Website jnpbali.com",
              size: 28,
              italics: true,
            }),
          ],
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 400 },
          children: [
            new TextRun({ text: "Senin, 17 Agustus 2026", size: 22, color: "666666" }),
          ],
        }),

        heading("1. Ringkasan Eksekutif"),
        para(
          "Hari ini fokus utama pada perbaikan UI/UX website jnpbali.com agar lebih mudah digunakan di mobile, tablet, dan desktop — terutama untuk alur belanja custom apparel. Pekerjaan mencakup perbaikan kontras teks, layout halaman produk, floating cart, integrasi wishlist, footer, halaman checkout, dan halaman akun."
        ),
        para(
          "Total iterasi CSS tema child: v15 → v22. Floating cart diimplementasikan sebagai MU-plugin via cPanel setelah beberapa percobaan plugin WordPress gagal di-activate."
        ),

        heading("2. Pekerjaan yang Diselesaikan"),

        heading("2.1 Perbaikan UI Global (ui-fixes.css v15–v21)", HeadingLevel.HEADING_2),
        bullet("Teks di background hitam dibuat lebih terbaca (#E8E8E8 / #FFFFFF), termasuk checkbox dan radio button (accent gold)."),
        bullet("Footer — baris Payment tidak lagi menumpuk/overlap dengan Email (menghapus hack CSS height:0 + ::before)."),
        bullet("Tata letak paragraf dan list di footer/pre-footer dirapikan (spacing label–value, grid responsif)."),
        bullet("Halaman register/account (/account-access/) — overlay putih dihilangkan, label form terbaca."),
        bullet("Mobile product page — label Additional Information (shop_attributes) terbaca; icon cart duplikat disembunyikan; menu mobile tidak tertutup floating cart."),
        bullet("Footer spacing dioptimalkan untuk mobile, tablet, dan desktop (Section 9)."),

        heading("2.2 Halaman Produk Desktop (≥1190px)", HeadingLevel.HEADING_2),
        bullet("Layout 2 kolom untuk gallery + summary (contoh: JP Bali Mug)."),
        bullet("Perbaikan gallery yang terlihat ter-crop dan ruang kosong berlebihan (~944px void dihilangkan)."),
        bullet("Mobile dan tablet tidak diubah (breakpoint dijaga)."),

        heading("2.3 Floating Cart & Wishlist", HeadingLevel.HEADING_2),
        bullet("Floating cart mengambang seperti CTA WhatsApp (pojok kanan bawah)."),
        bullet("Add to cart dan wishlist → item masuk keranjang (perilaku e-commerce standar)."),
        bullet("Deploy via MU-plugin (wp-content/mu-plugins/) karena plugin ZIP gagal activate (duplikat versi)."),
        bullet("Popup cart: subtotal, Checkout, dan link View full cart."),
        bullet("Toast notifikasi saat item ditambahkan."),

        heading("2.4 Checkout & Cart Popup (ui-fixes.css v22)", HeadingLevel.HEADING_2),
        bullet("Label checkout (Delivery Details, Hotel/Villa, dll.) — teks putih di form putih diperbaiki menjadi gelap (#1a1a1a)."),
        bullet("Teks bantuan delivery (.jnp-ch-intro, .jnp-ch-note) dibuat terbaca (#444)."),
        bullet("Popup cart — footer duplikat dari tema WooCommerce disembunyikan; hanya satu set subtotal + tombol aksi."),

        heading("3. Masalah yang Ditemukan & Solusi"),
        makeTable(
          ["Masalah", "Penyebab", "Solusi"],
          [
            [
              "Label checkout tidak terlihat",
              "CSS global memaksa teks terang di form putih",
              "Override khusus body.woocommerce-checkout (v22)",
            ],
            [
              "Tombol View Cart di popup hampir invisible",
              "Warna rgb(7,7,7) di background gelap + duplikat footer tema",
              "Sembunyikan .woocommerce-mini-cart__buttons dari tema",
            ],
            [
              "Payment & Email numpuk di mobile footer",
              ".jnp-payment-note pakai pseudo-element di luar layout",
              "Normal text flow + spacing Section 9 (v21)",
            ],
            [
              "Cart statis kiri bawah di halaman produk",
              "Template cart slot + z-index conflict",
              "Sembunyikan .jnp-cart-slot; naikkan z-index menu mobile",
            ],
            [
              "Plugin floating cart gagal activate",
              "Duplikat folder plugin saat unzip",
              "Deploy sebagai MU-plugin via cPanel",
            ],
            [
              "Critical error WordPress",
              "Konflik/require plugin",
              "Recovery + dokumentasi RECOVERY-CRITICAL-ERROR-ID.md",
            ],
          ]
        ),
        para(""),

        heading("4. File yang Dibuat / Dimodifikasi"),
        makeTable(
          ["File", "Keterangan"],
          [
            ["moderno-child/ui-fixes.css", "CSS fixes v15–v22 (terbaru: checkout + float cart)"],
            ["moderno-child/functions-snippet.php", "Enqueue ui-fixes.css v22.0.0"],
            ["moderno-child/header-custom.css", "Z-index mobile menu"],
            ["moderno-child/header-custom.js", "Toggle body.jnp-menu-open, Escape/outside click"],
            ["UPLOAD-ke-mu-plugins-jnpbali-float-cart.php", "MU-plugin floating cart v1.0.5"],
            ["DEPLOY.md / CPANEL-DEPLOY-ID.md", "Panduan deploy"],
            ["RECOVERY-CRITICAL-ERROR-ID.md", "Panduan recovery error kritis"],
          ]
        ),
        para(""),

        heading("5. Status Deploy"),
        makeTable(
          ["Komponen", "Status Live", "Catatan"],
          [
            ["ui-fixes.css", "v21.0.0 (live)", "v22 siap upload — belum deploy"],
            ["Floating cart MU-plugin", "Terdeploy via cPanel", "Berfungsi di shop; popup sudah ada"],
            ["header-custom.js/css", "Perlu verifikasi", "Upload jika belum"],
            ["SpeedyCache", "Perlu clear", "Wajib setelah upload CSS baru"],
          ]
        ),
        para(""),

        heading("6. Pengujian"),
        bullet("Checkout page — label masih rgb(232,232,232) di live v21 (diperbaiki di v22 lokal)."),
        bullet("Float cart popup — duplikat subtotal/buttons terkonfirmasi; fix v22 disimulasikan via browser (display:none)."),
        bullet("Mobile footer — overlap Email/Payment diperbaiki di v21."),
        bullet("Product page desktop — layout dan spacing gallery diperbaiki iteratif (browser verify)."),

        heading("7. Tindak Lanjut (Besok / Pending)"),
        bullet("Upload ui-fixes.css v22 + bump functions.php ke 22.0.0 + clear SpeedyCache."),
        bullet("Verifikasi visual checkout, cart popup, dan footer di mobile setelah deploy."),
        bullet("Pastikan header-custom.js/css sudah ter-upload di server."),
        bullet("Uji end-to-end: browse produk → add to cart → checkout → payment method."),

        heading("8. Catatan"),
        para(
          "Website: https://jnpbali.com | Stack: WordPress + WooCommerce + tema moderno-child + Elementor. Semua perbaikan CSS dirancang load setelah tema/Elementor dengan prioritas enqueue 99."
        ),
        para(
          "Dokumen ini dibuat otomatis berdasarkan pekerjaan development pada 17 Agustus 2026."
        ),
      ],
    },
  ],
});

const outputName = "Daily-Report-jnpbali-2026-08-17.docx";
const outputPaths = [
  path.join("C:\\Users\\NICHOLAS FERNANDO\\Documents", outputName),
  path.join("C:\\Users\\NICHOLAS FERNANDO\\Documents\\jnpbali-theme-fix", outputName),
];

Packer.toBuffer(doc).then((buffer) => {
  for (const out of outputPaths) {
    fs.writeFileSync(out, buffer);
    console.log("Created:", out, "(" + buffer.length + " bytes)");
  }
});
