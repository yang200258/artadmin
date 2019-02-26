<template>
  <div class="c-top-bar clearfix">
    <div v-for="(button, idx) in buttons" :class="{button: true, fl: true, first: idx === 0, 'cursor-pointer': true, actived: button.link === '/' ? ($route.path === button.link) : ($route.path.indexOf(button.link + '/') !== -1 || $route.path === button.link)}" :key="button.name" @click.stop="buttonClick(idx, button)">{{button.label}}</div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      buttons: [
        {
          name: 'Home',
          label: '首页',
          link: '/'
        },
        {
          name: 'Enroll',
          label: '考级报名',
          link: '/enroll'
        },
        {
          name: 'Dynamic',
          label: '考级动态',
          link: '/dynamic'
        },
        {
          name: 'Training',
          label: '师资培训',
          link: '/training'
        },
        {
          name: 'Perform',
          label: '艺术团表演',
          link: '/perform'
        },
        {
          name: 'Race',
          label: '大赛动态',
          link: '/race'
        },
        {
          name: 'Book',
          label: '考级教材',
          link: '/book'
        },
        {
          name: 'Us',
          label: '关于我们',
          link: '/us'
        }
      ]
    }
  },
  methods: {
    buttonClick: function (idx, button) {
      console.log('buttonClick', idx, button)
      this.$router.replace({ name: button.name })
    }
  }
}
</script>

<style scoped>
.c-top-bar{
  overflow: hidden;
  padding: 10px 0 15px;
}
.button{
  width: 10.3125%;
  height: 54px;
  line-height: 54px;
  text-align: center;
  margin-left: 2.5%;
  color: #76593D;
  background-image: url('../assets/image/topbar_unactived.png');
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-position: center;
}
.button.first{
  margin-left: 0;
}
.button:hover{
  font-weight: bold;
  box-shadow: 0 0 5px 0 rgba(0,0,0,0.1) inset;
}
.button.actived{
  font-weight: bold;
  color: #fff;
  background-image: url('../assets/image/topbar_actived.png');
}
</style>
