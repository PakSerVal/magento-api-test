const path = require('path');

module.exports = {
    entry: [
        './src/index.js'
    ],
    output:  {
        filename: 'app.js',
        path:     path.resolve(__dirname + '/../public/js/')
    },
    module:  {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.css$/,
                use: [
                    'style-loader',
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                            modules: true
                        }
                    }
                ]
            }
        ]
    },
};
