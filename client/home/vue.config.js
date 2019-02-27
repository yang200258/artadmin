// vue.config.js
const CompressionWebpackPlugin = require('compression-webpack-plugin')
const productionGzipExtensions = ['js', 'css']

module.exports = {
  // 选项...
  publicPath: process.env.NODE_ENV === 'production' ? '/home/' : '/',
  outputDir: '../../web/home',
  assetsDir: 'assets',
  devServer: {
    https: true,
    disableHostCheck: true,
    proxy: 'https://arthometest.fantuan.cn'
  },
  chainWebpack: config => {
    config.plugins.delete('prefetch')
  },
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
  }
}
