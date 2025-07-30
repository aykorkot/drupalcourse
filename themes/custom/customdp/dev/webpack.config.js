const path = require('path');

module.exports = {
  entry: {
    style: './dev/assets/scss/style.scss',
    script: './dev/assets/js/script.js'
  },
  output: {
    path: path.resolve(__dirname, '../dist'),
    filename: 'js/script.js'
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: ['style-loader', 'css-loader', 'sass-loader']
      }
    ]
  }
};
