<template>
  <div class="c-book cursor-pointer clearfix" :style="{height: height}" @click.stop="goDetail(book)">
    <div class="image" :style="{backgroundImage: 'url(' + (book.cover_url || defaultImg) + ')', backgroundSize: (book.cover_url ? 'cover' : '120px 120px')}"></div>
    <div class="title">{{book.title}}</div>
  </div>
</template>

<script>
const defaultImg = require('../assets/image/default_img.png')
export default {
  props: {
    book: {
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
.c-book{
  background: #FBFBFB;
  overflow: hidden;
}
.c-book:hover{
  background: rgba(251,241,230,0.4);
}
.content-box{
  padding-top: 5px;
}
.image{
  display: block;
  width: 88%;
  height: 82.5%;
  background-repeat: no-repeat;
  background-position: center;
  background-color: #FBF1E6;
  margin: 15px auto 0;
}
.title{
  font-size: 20px;
  line-height: 38px;
  color: #76593D;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 10px 6% 0;
}
/* .title:hover{
  color: #F5A623;
  text-decoration: underline;
} */
</style>
