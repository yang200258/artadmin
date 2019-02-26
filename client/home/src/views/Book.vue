<template>
  <div class="p-book">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="book-body">
      <div v-if="cate && cate.name && cate.id" class="cate-box">
        <block-head title="考级教材" :hide-btn="true" />
        <div class="book-box clearfix">
          <div v-for="(book, idx) in cate.list" :key="book.id" class="book-item fl" :class="{'list-left': idx % 4 === 0}">
            <book :book="book" />
          </div>
        </div>
      </div>
      <div style="margin-top:20px;font-size:0;text-align:center">
        <div style="display:inline-block;">
          <pagination :total="page.total_page || 0" :current="1" @change="pn => fetchCates(pn)" />
        </div>
      </div>
    </div>
    <bottom-info />
  </div>
</template>

<script>
// @ is an alias to /src
import TopInfo from '../components/TopInfo'
import TopBar from '../components/TopBar'
import BottomInfo from '../components/BottomInfo'
import BlockHead from '../components/BlockHead'
import Book from '../components/Book'
import SubNav from '../components/SubNav'
import Pagination from '../components/Pagination'

export default {
  data () {
    return {
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Book', label: '考级教材', link: '/book' }],
      cate: {},
      page: {},
      loading: false
    }
  },
  components: { TopInfo, TopBar, BottomInfo, BlockHead, Book, SubNav, Pagination },
  mounted: function () {
    this.fetchCates(1)
  },
  methods: {
    fetchCates: function (pn) {
      console.log('fetchCates', pn)
      if (this.loading) {
        return false
      }
      let rData = {
        pn
      }
      this.loading = true
      this.$ajax('/msg/category5', { data: rData }).then(res => {
        this.loading = false
        if (res && !res.error) {
          this.cate = res.data.leftCates
          this.page = res.data.page
        }
      }).catch(err => {
        console.log('err', err)
        this.loading = false
      })
    }
  }
}
</script>

<style scoped>
.p-book{
  overflow-x: visible;
}
.book-body{
  min-height: 1000px;
  padding-bottom: 20px;
}
.cate-box{
  margin-top: 30px;
}
.book-item{
  width: 268px;
  height: 398px;
  margin: 20px 0 0 40px;
  border: 1px solid #EFDFC9;
  border-radius: 10px;
  overflow: hidden;
}
.book-item.list-left{
  margin-left: 0;
}
</style>
