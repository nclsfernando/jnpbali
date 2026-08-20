/**
 * Update JWT_TOKEN in Cursor MCP config for wordpress-mcp
 * Usage: node scripts/update-wordpress-mcp-token.js "YOUR_NEW_JWT_TOKEN"
 */
const fs = require("fs");
const path = require("path");

const MCP_CONFIG = path.join(
  process.env.USERPROFILE || "",
  ".cursor",
  "mcp.json"
);

const CLAUDE_CONFIG = path.join(
  process.env.LOCALAPPDATA || "",
  "Packages",
  "Claude_pzs8sxrjxfjjc",
  "LocalCache",
  "Roaming",
  "Claude",
  "claude_desktop_config.json"
);

function updateConfig(filePath, token) {
  if (!fs.existsSync(filePath)) {
    return false;
  }
  const config = JSON.parse(fs.readFileSync(filePath, "utf8"));
  const server = config?.mcpServers?.["wordpress-mcp"];
  if (!server?.env) {
    return false;
  }
  server.env.JWT_TOKEN = token;
  if (!server.env.WP_API_URL.endsWith("/")) {
    server.env.WP_API_URL = server.env.WP_API_URL.replace(/\/$/, "") + "/";
  }
  fs.writeFileSync(filePath, JSON.stringify(config, null, 2) + "\n", "utf8");
  return true;
}

const token = process.argv[2];
if (!token || token.length < 20) {
  console.error('Usage: node scripts/update-wordpress-mcp-token.js "YOUR_NEW_JWT_TOKEN"');
  process.exit(1);
}

let updated = 0;
if (updateConfig(MCP_CONFIG, token)) {
  console.log("Updated:", MCP_CONFIG);
  updated++;
}
if (updateConfig(CLAUDE_CONFIG, token)) {
  console.log("Updated:", CLAUDE_CONFIG);
  updated++;
}

if (!updated) {
  console.error("No MCP config files were updated.");
  process.exit(1);
}

console.log("\nDone. Restart Cursor (or reload MCP servers) for the new token to take effect.");
console.log("Verify with: node scripts/test-wordpress-mcp.js");
