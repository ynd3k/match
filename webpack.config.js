const path = require('path');

module.exports = {
    entry: path.join(__dirname, 'public/assets/js/app.js'),
    output: {
        path: path.join(__dirname, 'public/assets/js/dist'),
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    resolve: {
        modules: [path.join(__dirname, 'public'), 'node_modules'],
        extensions: ['.js'],
        alias: {
            vue: 'vue/dist/vue.esm.js'
        }
    }
};