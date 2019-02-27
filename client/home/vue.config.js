// vue.config.js
module.exports = {
  // 选项...
  outputDir: '../../web/home',
  assetsDir: 'assets',
  devServer: {
    disableHostCheck: true,
    proxy: 'http://arthometest.fantuan.cn'
  }
}
