<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'app'
}
</script>

<style>
@import url('./assets/style/normalize.css');
body{
  overflow-x: hidden;
}
#app {
  max-width: 1200px;
  margin: 0 auto;
  overflow-x: visible;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #333;
}
.cursor-pointer{
  cursor: pointer;
}
.clearfix{
  overflow: hidden;
}
.clearfix:after {
  content: "";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}
.fl {
  float: left;
}
.fr {
  float: right;
}
h1,h2,h3,h4,h5,h6,p{
  padding: 0;
  margin: 0;
}
input{
  outline: none;
  padding: 0;
  margin: 0;
}
</style>
