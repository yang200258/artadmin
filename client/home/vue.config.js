// vue.config.js
module.exports = {
  // 选项...
  publicPath: '/home/',
  outputDir: '../../web/home',
  assetsDir: 'assets',
  devServer: {
    disableHostCheck: true,
    proxy: 'http://arthometest.fantuan.cn'
  }
}
