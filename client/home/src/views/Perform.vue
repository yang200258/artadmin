<template>
  <div class="p-perform">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="perform-body">
      <div v-if="cate && cate.name && cate.id" class="cate-box">
        <block-head title="艺术团表演" :hide-btn="true" />
        <div class="perform-box clearfix">
          <div v-for="(perform, idx) in cate.list" :key="perform.id" class="perform-item fl" :class="{'list-left': idx % 3 === 0}">
            <perform :perform="perform" />
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
import Perform from '../components/Perform'
import SubNav from '../components/SubNav'
import Pagination from '../components/Pagination'

export default {
  data () {
    return {
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Perform', label: '艺术团表演', link: '/perform' }],
      cate: {},
      page: {},
      loading: false
    }
  },
  components: { TopInfo, TopBar, BottomInfo, BlockHead, Perform, SubNav, Pagination },
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
      this.$ajax('/msg/category3', { data: rData }).then(res => {
        this.loading = false
        console.log('/msg/category3', res)
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
.p-perform{
  overflow-x: visible;
}
.perform-body{
  min-height: 1000px;
  padding-bottom: 20px;
}
.cate-box{
  margin-top: 30px;
}
.perform-item{
  width: 388px;
  height: 330px;
  margin-left: 1.25%;
  margin-top: 20px;
  border: 1px solid #EFDFC9;
  border-radius: 10px;
  overflow: hidden;
}
.perform-item.list-left{
  margin-left: 0;
}
</style>
