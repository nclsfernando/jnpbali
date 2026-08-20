/**
 * Test WordPress MCP JWT token against jnpbali.com
 * Usage: node scripts/test-wordpress-mcp.js [optional-jwt-token]
 */
const fs = require("fs");
const path = require("path");

const MCP_CONFIG = path.join(
  process.env.USERPROFILE || "",
  ".cursor",
  "mcp.json"
);

function loadTokenFromConfig() {
  const raw = fs.readFileSync(MCP_CONFIG, "utf8");
  const config = JSON.parse(raw);
  return config?.mcpServers?.["wordpress-mcp"]?.env?.JWT_TOKEN || "";
}

function decodeJwt(token) {
  try {
    const payload = token.split(".")[1];
    return JSON.parse(Buffer.from(payload, "base64url").toString("utf8"));
  } catch {
    return null;
  }
}

async function testToken(token) {
  const url = "https://jnpbali.com/wp-json/wp/v2/wpmcp";
  const res = await fetch(url, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      jsonrpc: "2.0",
      id: 1,
      method: "tools/list",
      params: {},
    }),
  });
  const body = await res.text();
  return { status: res.status, body };
}

async function main() {
  const token = process.argv[2] || loadTokenFromConfig();
  if (!token) {
    console.error("No JWT token found. Pass one as an argument or set JWT_TOKEN in .cursor/mcp.json");
    process.exit(1);
  }

  const decoded = decodeJwt(token);
  if (decoded?.exp) {
    const exp = new Date(decoded.exp * 1000);
    const expired = Date.now() > decoded.exp * 1000;
    console.log(`Token expires: ${exp.toISOString()} (${expired ? "EXPIRED" : "valid"})`);
  }

  console.log("Testing https://jnpbali.com/wp-json/wp/v2/wpmcp ...");
  const result = await testToken(token);

  if (result.status === 200 && result.body.includes('"tools"')) {
    console.log("OK — WordPress MCP connection works.");
    process.exit(0);
  }

  console.log(`Failed — HTTP ${result.status}`);
  console.log(result.body.slice(0, 300));

  if (result.body.includes("Expired token")) {
    console.log("\nFix: generate a new token in WordPress admin:");
    console.log("  Settings → WordPress MCP → Authentication Tokens → Generate New Token");
    console.log("Then run:");
    console.log('  node scripts/update-wordpress-mcp-token.js "YOUR_NEW_TOKEN"');
  }

  process.exit(1);
}

main().catch((err) => {
  console.error(err.message);
  process.exit(1);
});
