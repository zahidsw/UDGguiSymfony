const package = require("./package.json");
const path = require("path");
const MODULE_FILE_NAME = package.name;

console.log(path.resolve(__dirname, "node_modules"));

module.exports = {
  entry: "./index.js",
  devtool: "source-map",
  output: {
    filename: MODULE_FILE_NAME + ".min.js",
    path: path.resolve(__dirname, "dist")
  },
  resolve: {
    alias: {
      "/node_modules": path.resolve(__dirname, "node_modules")
    }
  }
};
