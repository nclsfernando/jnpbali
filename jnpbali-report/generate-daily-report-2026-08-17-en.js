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
  title: "Daily Report J&P Bali — August 17, 2026",
  description: "Daily development report for jnpbali.com UI/UX and e-commerce improvements",
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
              text: "jnpbali.com Website Development",
              size: 28,
              italics: true,
            }),
          ],
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { after: 400 },
          children: [
            new TextRun({ text: "Monday, August 17, 2026", size: 22, color: "666666" }),
          ],
        }),

        heading("1. Executive Summary"),
        para(
          "Today's work focused on improving the UI/UX of jnpbali.com for mobile, tablet, and desktop users — especially the custom apparel shopping flow. Tasks included text contrast fixes, product page layout, floating cart, wishlist integration, footer layout, checkout page, and account page fixes."
        ),
        para(
          "Child theme CSS iterations: v15 through v22. The floating cart was implemented as an MU-plugin via cPanel after several WordPress plugin activation attempts failed."
        ),

        heading("2. Completed Work"),

        heading("2.1 Global UI Fixes (ui-fixes.css v15–v21)", HeadingLevel.HEADING_2),
        bullet("Improved text readability on dark backgrounds (#E8E8E8 / #FFFFFF), including checkboxes and radio buttons (gold accent)."),
        bullet("Footer — Payment line no longer overlaps Email (removed CSS height:0 + ::before hack)."),
        bullet("Cleaned up paragraph and list layout in footer/pre-footer (label–value spacing, responsive grid)."),
        bullet("Register/account page (/account-access/) — removed white overlay; form labels are now readable."),
        bullet("Mobile product page — Additional Information labels readable; duplicate cart icon hidden; mobile menu no longer covered by floating cart."),
        bullet("Footer spacing optimized for mobile, tablet, and desktop (Section 9)."),

        heading("2.2 Desktop Product Page (≥1190px)", HeadingLevel.HEADING_2),
        bullet("Two-column layout for gallery + summary (e.g. JP Bali Mug)."),
        bullet("Fixed gallery appearing cropped and excessive empty space (~944px void removed)."),
        bullet("Mobile and tablet layouts unchanged (breakpoints preserved)."),

        heading("2.3 Floating Cart & Wishlist", HeadingLevel.HEADING_2),
        bullet("Floating cart positioned like the WhatsApp CTA (bottom-right corner)."),
        bullet("Add to cart and wishlist → items appear in cart (standard e-commerce behavior)."),
        bullet("Deployed as MU-plugin (wp-content/mu-plugins/) after ZIP plugin activation failed (duplicate versions)."),
        bullet("Cart popup: subtotal, Checkout button, and View full cart link."),
        bullet("Toast notification when items are added."),

        heading("2.4 Checkout & Cart Popup (ui-fixes.css v22)", HeadingLevel.HEADING_2),
        bullet("Checkout labels (Delivery Details, Hotel/Villa, etc.) — fixed white text on white forms to dark (#1a1a1a)."),
        bullet("Delivery helper text (.jnp-ch-intro, .jnp-ch-note) set to readable gray (#444)."),
        bullet("Cart popup — hidden duplicate WooCommerce theme footer; single subtotal + action buttons only."),

        heading("3. Issues Found & Solutions"),
        makeTable(
          ["Issue", "Root Cause", "Solution"],
          [
            [
              "Checkout labels invisible",
              "Global CSS forced light text on white forms",
              "Checkout-specific override on body.woocommerce-checkout (v22)",
            ],
            [
              "View Cart button nearly invisible in popup",
              "rgb(7,7,7) text on dark bg + duplicate theme footer",
              "Hide .woocommerce-mini-cart__buttons from theme",
            ],
            [
              "Payment & Email overlapping on mobile footer",
              ".jnp-payment-note used pseudo-element outside layout",
              "Normal text flow + Section 9 spacing (v21)",
            ],
            [
              "Static cart icon bottom-left on product page",
              "Template cart slot + z-index conflict",
              "Hide .jnp-cart-slot; raise mobile menu z-index",
            ],
            [
              "Floating cart plugin failed to activate",
              "Duplicate plugin folders on unzip",
              "Deploy as MU-plugin via cPanel",
            ],
            [
              "WordPress critical error",
              "Plugin conflict/require issue",
              "Recovery + RECOVERY-CRITICAL-ERROR-ID.md documentation",
            ],
          ]
        ),
        para(""),

        heading("4. Files Created / Modified"),
        makeTable(
          ["File", "Description"],
          [
            ["moderno-child/ui-fixes.css", "CSS fixes v15–v22 (latest: checkout + float cart)"],
            ["moderno-child/functions-snippet.php", "Enqueue ui-fixes.css v22.0.0"],
            ["moderno-child/header-custom.css", "Mobile menu z-index"],
            ["moderno-child/header-custom.js", "Toggle body.jnp-menu-open, Escape/outside click"],
            ["UPLOAD-ke-mu-plugins-jnpbali-float-cart.php", "Floating cart MU-plugin v1.0.5"],
            ["DEPLOY.md / CPANEL-DEPLOY-ID.md", "Deployment guides"],
            ["RECOVERY-CRITICAL-ERROR-ID.md", "Critical error recovery guide"],
          ]
        ),
        para(""),

        heading("5. Deployment Status"),
        makeTable(
          ["Component", "Live Status", "Notes"],
          [
            ["ui-fixes.css", "v21.0.0 (live)", "v22 ready locally — not yet deployed"],
            ["Floating cart MU-plugin", "Deployed via cPanel", "Working on shop; popup active"],
            ["header-custom.js/css", "Needs verification", "Upload if not already on server"],
            ["SpeedyCache", "Clear required", "Mandatory after CSS upload"],
          ]
        ),
        para(""),

        heading("6. Testing"),
        bullet("Checkout page — labels still rgb(232,232,232) on live v21 (fixed in local v22)."),
        bullet("Float cart popup — duplicate subtotal/buttons confirmed; v22 fix verified via browser (display:none)."),
        bullet("Mobile footer — Email/Payment overlap fixed in v21."),
        bullet("Desktop product page — layout and gallery spacing fixed iteratively (browser verified)."),

        heading("7. Next Steps / Pending"),
        bullet("Upload ui-fixes.css v22 + bump functions.php to 22.0.0 + clear SpeedyCache."),
        bullet("Visual verification of checkout, cart popup, and footer on mobile after deploy."),
        bullet("Confirm header-custom.js/css is uploaded on the server."),
        bullet("End-to-end test: browse product → add to cart → checkout → payment method."),

        heading("8. Notes"),
        para(
          "Website: https://jnpbali.com | Stack: WordPress + WooCommerce + moderno-child theme + Elementor. All CSS fixes load after theme/Elementor with enqueue priority 99."
        ),
        para(
          "This document was generated based on development work completed on August 17, 2026."
        ),
      ],
    },
  ],
});

const outputName = "Daily-Report-jnpbali-2026-08-17-EN.docx";
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
