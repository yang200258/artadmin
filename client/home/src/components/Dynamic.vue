<template>
  <div class="c-dynamic cursor-pointer clearfix" :class="{['mode' + mode]: true}" @click.stop="goDetail(dynamic.id)">
    <div class="image" :class="{fl: mode === '1', ['mode' + mode]: true}" :style="{backgroundImage: 'url(' + (dynamic.cover_url || defaultImg) + ')', backgroundSize: (dynamic.cover_url ? 'cover' : '120px 120px')}"></div>
    <div class="content-box" :class="{fr: mode === '1', ['mode' + mode]: true}">
      <div class="content-top" :class="{['mode' + mode]: true}">
        <p class="title cursor-pointer" :class="{['mode' + mode]: true}">{{dynamic.title}}</p>
        <p v-if="mode === '1'" class="content" :class="{['mode' + mode]: true}">{{dynamic.intro}}</p>
      </div>
      <p v-if="mode === '1'" class="time" :class="{['mode' + mode]: true}">{{dynamic.create_at}}</p>
    </div>
  </div>
</template>

<script>
const defaultImg = require('../assets/image/default_img.png')
export default {
  props: {
    dynamic: {
      type: Object,
      required: true
    },
    mode: {
      type: String,
      default: '1'
    }
  },
  data () {
    return {
      defaultImg: defaultImg
    }
  },
  methods: {
    goDetail: function (id) {
      this.$router.push({ path: 'dynamicdetail', query: { id: id } })
    }
  }
}
</script>

<style scoped>
.c-dynamic.mode1{
  padding: 20px 0;
  border-bottom: 1px solid #EEEEEE;
}
.c-dynamic.mode2{
  padding: 0 0 4px;
  margin-top: 20px;
}
.c-dynamic.mode3{
  padding: 0;
  margin-top: 20px;
}
.c-dynamic:hover{
  background: rgba(251,241,230,0.4);
}
.image{
  display: block;
  background-repeat: no-repeat;
  background-position: center;
  background-color: #FBF1E6;
}
.image.mode1{
  width: 170px;
  height: 160px;
  margin-top: 2px;
}
.image.mode2{
  width: 100%;
  height: 240px;
  margin-top: 2px;
}
.image.mode3{
  width: 100%;
  height: 215px;
  margin-top: 2px;
}
.content-box{
  max-width: 590px;
}
.content-box.mode1{
  width: 590px;
}
.content-top.mode1{
  height: 134px;
}
.title{
  font-size: 20px;
  line-height: 32px;
  color: #76593D;
}
/* .title:hover{
  color: #F5A623;
  text-decoration: underline;
} */
.title.mode1{
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
}
.title.mode2{
  padding-top: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.title.mode3{
  padding-top: 6px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  text-align: center;
}
.content{
  font-size: 16px;
  line-height: 24px;
  color: #4A4A4A;
  text-indent: 32px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  overflow: hidden;
  padding-top: 6px;
}
.time{
  font-size: 16px;
  line-height: 24px;
  color: #76593D;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding-top: 10px;
}
</style>
