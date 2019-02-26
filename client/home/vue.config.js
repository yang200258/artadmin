// vue.config.js
module.exports = {
  // 选项...
  assetsDir: 'assets',
  devServer: {
    disableHostCheck: true,
    proxy: 'http://arthometest.fantuan.cn'
  }
}
