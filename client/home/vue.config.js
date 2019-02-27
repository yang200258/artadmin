// vue.config.js
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
  }
}
