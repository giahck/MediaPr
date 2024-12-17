const path = require("path");

module.exports = {
  entry: "./front/js/index.js", // Punto di ingresso principale
  output: {
    path: path.resolve(__dirname, "dist"), // Cartella di output
    filename: "bundle.js", // Nome del file di output (senza hash, semplice)
  },
  mode: process.env.NODE_ENV || "development", // Usa NODE_ENV per determinare la modalità
  devServer: {
    static: path.resolve(__dirname, "dist"), // Cartella da cui il server fornirà i file statici
    port: 8080, // La porta su cui il server girerà
    hot: true, // Abilita Hot Module Replacement (HMR)
    open: true, // Apre automaticamente il browser
    client: {
      overlay: true, // Mostra errori sul browser
    },
    headers: {
      "Access-Control-Allow-Origin": "*", // Aggiungi l'header CORS
      "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
      "Access-Control-Allow-Headers": "X-Requested-With, content-type, Authorization"
    },
  },
  module: {
    rules: [
      {
        test: /\.html$/, // Gestione file HTML
        use: ["html-loader"],
      },
      {
        test: /\.js$/, // Gestione file JavaScript
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
};