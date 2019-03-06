<template>
  <div class="c-perform cursor-pointer clearfix" :style="{height: height}" @click.stop="goDetail(perform)">
    <div class="image" :style="{backgroundImage: 'url(' + (perform.cover_url || defaultImg) + ')', backgroundSize: (perform.cover_url ? 'cover' : '120px 120px')}"></div>
    <div class="content-box">
      <div class="title">{{perform.title}}</div>
      <div class="time">{{perform.time}}</div>
    </div>
  </div>
</template>

<script>
const defaultImg = require('../assets/image/default_img.png')
export default {
  props: {
    perform: {
      type: Object,
      required: true
    },
    height: {
      type: String,
      default: '100%'
    }
  },
  data () {
    return {
      defaultImg: defaultImg
    }
  },
  methods: {
    goDetail: function (dynamic) {
      let { id, category_name: categoryName } = dynamic
      this.$router.push({ path: 'dynamicdetail', query: { id, catename: categoryName } })
    }
  }
}
</script>

<style scoped>
.c-perform{
  background: #FBFBFB;
  overflow: hidden;
}
.c-perform:hover{
  background: rgba(251,241,230,0.4);
}
.content-box{
  padding-top: 5px;
}
.image{
  display: block;
  width: 92%;
  height: 72%;
  background-repeat: no-repeat;
  background-position: center;
  background-color: #FBF1E6;
  margin: 15px auto 0;
}
.title{
  font-size: 20px;
  line-height: 30px;
  color: #76593D;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 0 4%;
}
/* .title:hover{
  color: #F5A623;
  text-decoration: underline;
} */
.time{
  font-size: 16px;
  line-height: 26px;
  color: #9B9B9B;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 0 4%;
}
</style>
