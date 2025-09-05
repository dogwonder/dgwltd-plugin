const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        ...defaultConfig.entry,
        'dgwltd-plugin-editor': path.resolve(__dirname, 'admin/scripts/dgwltd-plugin-editor.js'),
        'dgwltd-plugin-variations': path.resolve(__dirname, 'admin/scripts/dgwltd-plugin-variations.js'),
        'dgwltd-plugin-theme': path.resolve(__dirname, 'public/scripts/dgwltd-plugin-theme.js'),
    },
     output: {
        path: path.resolve( __dirname, 'build' ), filename: '[name].js',
    },
};