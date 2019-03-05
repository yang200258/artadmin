// vue.config.js
const CompressionWebpackPlugin = require('compression-webpack-plugin')
const productionGzipExtensions = ['js', 'css']
// const HtmlWebpackPlugin = require('html-webpack-plugin')
// const path = require('path')

module.exports = {
  // 选项...
  publicPath: process.env.NODE_ENV === 'production' ? 'https://www.hnyskj.net/home' : '/',
  outputDir: '../../web/home',
  assetsDir: 'static',
  devServer: {
    https: true,
    disableHostCheck: true,
    proxy: 'https://www.hnyskj.net'
  },
  // chainWebpack: config => {
  //   config.plugins.delete('prefetch')
  // },
  configureWebpack: config => {
    if (process.env.NODE_ENV === 'production') { // 生产环境
      config.plugins.push(
        new CompressionWebpackPlugin({
          // asset: '[path].gz[query]',
          algorithm: 'gzip',
          test: new RegExp('\\.(' + productionGzipExtensions.join('|') + ')$'),
          threshold: 10240,
          minRatio: 0.8
        })
      )
    } else { // 开发环境

    }
    // config.plugins.push(
    //   new HtmlWebpackPlugin({
    //     filename: 'index.html',
    //     template: './public/index.html',
    //     inject: true,
    //     favicon: path.resolve(__dirname, './public/favicon.ico')
    //   })
    // )
  }
}
